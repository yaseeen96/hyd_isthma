<?php

namespace App\Helpers;

use App\Helpers\User;
use Exception;
use Illuminate\Support\Facades\Storage;

class UsersMigrator
{
    private string $url;
    private array $users;
    const getUsersAPIPath = "api/v1/users/get-users";
    const getUserAPIPath = "api/v1/users/appUser";
    const getUserAPIQueryParameter = "userSearchDto.userNumber";

    public function __construct($url)
    {
        $this->url = rtrim(trim($url), "/");
        $this->users = array();
    }

    public function loadFromServer()
    {
        $getUsersURL = $this->url . "/" . self::getUsersAPIPath;
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_URL, $getUsersURL);
        curl_setopt($curl, CURLOPT_SSH_COMPRESSION, true);

        // echo "Loading users information from " . $getUsersURL . PHP_EOL;
        $result = curl_exec($curl);
        $resultsJson = json_decode($result, true);
        if ($resultsJson['code'] == 200) {
            // echo "Successfully retrieved all users. Retrieving details of each user from server" . PHP_EOL;
            $allUsers = $resultsJson['data'];

            $getUserURL = $this->url . "/" . self::getUserAPIPath . '?' . self::getUserAPIQueryParameter . "=";
            $counter = 1;
            foreach ($allUsers as $aUser) {
                $userNumber = $aUser['userName'];
                if ($userNumber !== '') {
                    $counter++;
                    // echo "Processing user " . $userNumber . PHP_EOL;
                    $getUserURLByID = $getUserURL . $userNumber;
                    curl_setopt($curl, CURLOPT_URL, $getUserURLByID);

                    $userResult = curl_exec($curl);
                    $userResponse = json_decode($userResult, true);
                    if ($userResponse['code'] == 200) {
                        $userInfo = $userResponse['data'][0];
                        $this->users[$userNumber] = $this->getUserFromResponse($userInfo);
                    }
                }
                if ($counter == 3) {
                    break;
                }

            }
            // echo "Number of users retrieved from the server " . count($this->users) . PHP_EOL;
        } else {
            // echo "Loading users information failed " . PHP_EOL;
        }
        curl_close($curl);
    }

    private function getUserFromResponse($userResponse): User
    {
        $user = new User();
        $user->setId($userResponse['userNumber']);
        $user->setName($userResponse['name']);
        $user->setContactNumber($userResponse['contactNumber']);
        $user->setEmailId($userResponse['emailId']);
        $user->setUnitName($userResponse['unitName']);
        $user->setZoneName($userResponse['zoneName']);
        $user->setDivisionName($userResponse['divisionName']);
        $user->setDob($userResponse['dateOfBirth']);
        $user->setGender($userResponse['gender']);
        $user->setStatus($userResponse['status']);
        return $user;
    }

    public function exportToCSV($csvPath, $users)
    {
        // if (!file_exists($csvPath)) {
        //     echo "File does not exist" . PHP_EOL;
        //     exit();
        // }

        // $fp = fopen($csvPath, 'w');
        // if (!$fp) {
        //     echo "Failed to open file " . $csvPath . PHP_EOL;
        //     exit();
        // }

        // foreach ($users as $user) {
        //     $userRow = (array) $user;
        //     fputcsv($fp, $userRow);
        // }

        // fclose($fp);
        // echo "Successfully exported data to csv file." . PHP_EOL;

        if (!Storage::exists('private')) {
            Storage::makeDirectory('private');
        }

        // Define the full path to the CSV file
        $path = storage_path('app/private/' . $csvPath);

        $file = fopen($path, 'w');

        if ($file === false) {
            throw new Exception('Could not open file for writing: ' . $path);
        }

        foreach ($users as $user) {
            $userRow = (array) $user;
            fputcsv($file, $userRow);
        }
        fclose($file);

        return $path;
    }

    public function getUsers(): array
    {
        return $this->users;
    }

    public function importCSVToDB($dbhost, $dbuser, $dbpass, $dbname, $table, $csvFile)
    {
        $db = new \mysqli($dbhost, $dbuser, $dbpass, $dbname);
        if ($db->connect_errno) {
            // echo "Failed to connect to database" . PHP_EOL;
            exit();
        }

        $sql = "LOAD DATA INFILE '$csvFile'
        INTO TABLE '$table'
        FIELDS TERMINATED BY ','
        OPTIONALLY ENCLOSED BY '\"' 
        LINES TERMINATED BY ',,,\\r\\n' 
        (id, @name, @contactNumber, @emailID, @unitName, @zoneName, @divisionName, @dob, @gender, @status)
        SET name = NULLIF(@name, 'null'),
            contactNumber  = NULLIF(@contactNumber, 'null'),
            emailID = NULLIF(@emailID, 'null'),
            unitName = NULLIF(@unitName, 'null'),
            zoneName = NULLIF(@zoneName, 'null'),
            divisionName = NULLIF(@divisionName, 'null'),
            dob = NULLIF(@dob, 'null'),
            gender = NULLIF(@gender, 'null'),
            status = NULLIF(@status, 'null'),";

        // echo "Executing SQL Query..." . PHP_EOL;
        echo $sql;
        if ($db->query($sql)) {
            // echo "Import successfully completed. " . PHP_EOL;
        } else {
            // echo "Failed to import data ." . PHP_EOL;
        }

        $db->close();
    }
}