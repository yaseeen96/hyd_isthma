<?php

require_once ("User.php");

class UsersMigrator
{
    private string $url;
    private array $users;
    private array $userNames;
    const userCache = "userCache.csv";
    const getUsersAPIPath = "api/v1/users/get-users";
    const getUserAPIPath = "api/v1/users/appUser";
    const getUserAPIQueryParameter = "userSearchDto.userNumber";

    public function __construct($url)
    {
        $this->url = rtrim(trim($url), "/");
        $this->users = array();
    }

    public function initialize(): bool
    {
        // Get all users from server
        $allUserNames = $this->getUsersFromServer();
        if (!empty($allUserNames)) {
            echo (new DateTime())->format('Y-m-d h:i:s') . " Total no of users retrieved from the server " . count($allUserNames) . PHP_EOL;
            $this->userNames = $allUserNames;
            return true;
        }

        return false;
    }

    public function getUsersCount(): int
    {
        return count($this->userNames);
    }

    public function getUsersInformation($pageNo, $pageCount): ?array
    {
        // Call intialize, if get-users API is not called
        if (empty($this->userNames)) {
            $this->initialize();
        }

        // Get the sub array for the current page requested
        $usersToGet = array_slice($this->userNames, $pageNo * $pageCount, $pageCount);
        if (empty($usersToGet)) {
            echo (new DateTime())->format('Y-m-d h:i:s') . " Invalid page number and page count." . PHP_EOL;
            return null;
        }

        // Check if these users are already present in the cache
        $cachedUsers = $this->loadUsersFromCache();
        if (empty($cachedUsers)) {
            echo (new DateTime())->format('Y-m-d h:i:s') . " Empty cache. Get from the server." . PHP_EOL;
        }

        $cachedUserNumbers = array();
        foreach ($cachedUsers as $cachedUser) {
            $cachedUserNumbers[] = $cachedUser[0];
        }

        $pagedUsers = array();
        $missingUsers = array();
        foreach ($usersToGet as $userToGet) {
            $userName = $userToGet['userName'];
            if (!in_array($userName, $cachedUserNumbers)) {
                $newUserInfo = $this->getUserInfoFromServer($userName);
                if (!is_null($newUserInfo)) {
                    $pagedUsers[] = $newUserInfo;
                    $missingUsers[] = $newUserInfo;
                }
            } else {
                $pagedUsers[] = $this->getUserFromArrayEntry($cachedUsers[$userName]);
            }
        }

        if (count($missingUsers) > 0 && !$this->saveUsersToCache($missingUsers)) {
            echo (new DateTime())->format('Y-m-d h:i:s') . " Failed to save users info to cache file " . self::userCache . PHP_EOL;
        }

        return $pagedUsers;
    }

    private function saveUsersToCache($usersInfo): bool
    {
        $fp = fopen(self::userCache, 'a+');
        if (!$fp) {
            echo (new DateTime())->format('Y-m-d h:i:s') . " Failed to open cache file " . self::userCache . PHP_EOL;
            return false;
        }

        foreach ($usersInfo as $user) {
            $userRow = (array) $user;
            fputcsv($fp, $userRow);
        }

        fclose($fp);
        echo (new DateTime())->format('Y-m-d h:i:s') . " Successfully saved data to cache file." . PHP_EOL;
        return true;
    }

    public function loadUsersFromCache(): ?array
    {
        $cachedUsers = array();
        if (!file_exists(self::userCache)) {
            echo (new DateTime())->format('Y-m-d h:i:s') . " Cache file does not exist. Create it." . PHP_EOL;
        }

        $fd = fopen(self::userCache, 'a+');
        if (!$fd) {
            echo (new DateTime())->format('Y-m-d h:i:s') . " Failed to open file " . self::userCache . PHP_EOL;
            return null;
        }

        // Get cached users from the csv file
        while (($line = fgetcsv($fd)) !== FALSE) {
            $user = (array) $line;
            $userNumber = $user[0];
            $cachedUsers[$userNumber] = $user;
        }

        fclose($fd);
        return $cachedUsers;
    }

    // Gets all the users from the server. Call getUsers() method to get those
    // Additionally, caches the retrieved users to a csv file
    private function loadFromServer(): void
    {
        // Get all users from server
        $allUsers = $this->getUsersFromServer();
        if (!empty($allUsers)) {
            echo (new DateTime())->format('Y-m-d h:i:s') . " Total no of users retrieved from the server " . count($allUsers) . PHP_EOL;
            foreach ($allUsers as $aUser) {
                $userNumber = $aUser['userName'];
                // For each user, get the user details from the server
                $userInfo = $this->getUserInfoFromServer($userNumber);
                if (!is_null($userInfo)) {
                    $this->users[$userNumber] = $userInfo;
                }
            }
        }

        echo (new DateTime())->format('Y-m-d h:i:s') . " Saving users to the cache" . PHP_EOL;
        if (file_exists(self::userCache)) {
            echo (new DateTime())->format('Y-m-d h:i:s') . " Cache file already exists, delete it." . PHP_EOL;
            unlink(self::userCache);
        }

        $this->exportToCSV(self::userCache, $this->users);
    }

    // Get users from the server
    private function getUsersFromServer(): ?array
    {
        try {
            $getUsersURL = $this->url . "/" . self::getUsersAPIPath;
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_URL, $getUsersURL);
            curl_setopt($curl, CURLOPT_SSH_COMPRESSION, true);

            echo (new DateTime())->format('Y-m-d h:i:s') . " Loading users information from " . $getUsersURL . PHP_EOL;
            $result = curl_exec($curl);
            $resultsJson = json_decode($result, true);
            if ($resultsJson['code'] == 200) {
                echo (new DateTime())->format('Y-m-d h:i:s') . " Successfully retrieved all users. Retrieving details of each user from server" . PHP_EOL;
                return $resultsJson['data'];
            } else {
                echo (new DateTime())->format('Y-m-d h:i:s') . " Loading users information failed " . PHP_EOL;
            }
        } catch (\Exception $ex) {
            echo (new DateTime())->format('Y-m-d h:i:s') . " Exception caught in get users" . PHP_EOL;
        } finally {
            curl_close($curl);
        }

        return null;
    }

    // Given a user id, get user details from the server
    private function getUserInfoFromServer($userNumber): ?object
    {
        try {
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_SSH_COMPRESSION, true);

            $getUserURL = $this->url . "/" . self::getUserAPIPath . '?' . self::getUserAPIQueryParameter . "=";
            $getUserURLByID = $getUserURL . $userNumber;

            curl_setopt($curl, CURLOPT_URL, $getUserURLByID);

            if ($userNumber !== '') {
                echo (new DateTime())->format('Y-m-d h:i:s') . " Processing user " . $userNumber . PHP_EOL;

                $userResult = curl_exec($curl);
                $userResponse = json_decode($userResult, true);
                if ($userResponse['code'] == 200) {
                    $userInfo = $userResponse['data'][0];
                    return $this->getUserFromResponse($userInfo);
                }
            }
        } catch (\Exception $ex) {
            echo (new DateTime())->format('Y-m-d h:i:s') . " Exception caught in get user info" . PHP_EOL;
        } finally {
            curl_close($curl);
        }

        return null;
    }

    private function getUserFromResponse($userResponse): User
    {
        $user = new User();
        $user->user_number = $userResponse['userNumber'];
        $user->name = $userResponse['name'];
        $user->phone = $userResponse['contactNumber'];
        $user->email = $userResponse['emailId'];
        $user->unit_name = $userResponse['unitName'];
        $user->zone_name = $userResponse['zoneName'];
        $user->division_name = $userResponse['divisionName'];
        $user->dob = $userResponse['dateOfBirth'];
        $user->gender = $userResponse['gender'];
        $user->status = $userResponse['status'];
        $user->created_at = date('Y-m-d h:m:s');
        $user->updated_at = date('Y-m-d h:m:s');

        return $user;
    }

    private function getUserFromArrayEntry($cachedUser): User
    {
        $user = new User();
        $user->user_number = $cachedUser[0];
        $user->name = $cachedUser[1];
        $user->phone = $cachedUser[2];
        $user->email = $cachedUser[3];
        $user->unit_name = $cachedUser[4];
        $user->zone_name = $cachedUser[5];
        $user->division_name = $cachedUser[6];
        $user->dob = $cachedUser[7];
        $user->gender = $cachedUser[8];
        $user->status = $cachedUser[9];

        return $user;
    }

    // Export the users array to a csv
    public function exportToCSV($csvPath, $users): void
    {
        $fp = fopen($csvPath, 'w');
        if (!$fp) {
            echo (new DateTime())->format('Y-m-d h:i:s') . " Failed to open file " . $csvPath . PHP_EOL;
            exit();
        }

        foreach ($users as $user) {
            $userRow = (array) $user;
            fputcsv($fp, $userRow);
        }

        fclose($fp);
        echo (new DateTime())->format('Y-m-d h:i:s') . " Successfully exported data to csv file." . PHP_EOL;
    }

    // Insert the users array to a db table
    public function insertIntoDB($dbhost, $dbuser, $dbpass, $dbname, $table, $newUsers): bool
    {
        $db = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
        if ($db->connect_errno) {
            echo (new DateTime())->format('Y-m-d h:i:s') . " Failed to connect to database" . PHP_EOL;
            return false;
        }

        try {
            foreach ($newUsers as $newUser) {
                $query = "INSERT INTO $table (" .
                    implode(',', array_keys(get_object_vars($newUser))) .
                    ") VALUES ('" . implode("','", get_object_vars($newUser)) . "')";

                echo (new DateTime())->format('Y-m-d h:i:s') . " Executing SQL Query..." . PHP_EOL;
                echo $query;
                if ($db->query($query)) {
                    echo (new DateTime())->format('Y-m-d h:i:s') . " Import successfully completed. " . PHP_EOL;
                    return true;
                } else {
                    echo (new DateTime())->format('Y-m-d h:i:s') . " Failed to import data ." . PHP_EOL;
                }
            }
        } catch (\Exception $ex) {
            echo (new DateTime())->format('Y-m-d h:i:s') . " Exception caught in insert user info to db" . PHP_EOL;
        } finally {
            $db->close();
        }

        return false;
    }

    // Import the data in the csv file to a db table
    public function importCSVToDB($dbhost, $dbuser, $dbpass, $dbname, $table, $csvFile): bool
    {
        $db = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
        if ($db->connect_errno) {
            echo (new DateTime())->format('Y-m-d h:i:s') . " Failed to connect to database" . PHP_EOL;
            return false;
        }

        try {
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

            echo (new DateTime())->format('Y-m-d h:i:s') . " Executing SQL Query..." . PHP_EOL;
            echo $sql;
            if ($db->query($sql)) {
                echo (new DateTime())->format('Y-m-d h:i:s') . " Import successfully completed. " . PHP_EOL;
                return true;
            } else {
                echo (new DateTime())->format('Y-m-d h:i:s') . " Failed to import data ." . PHP_EOL;
            }
        } catch (\Exception $ex) {
            echo (new DateTime())->format('Y-m-d h:i:s') . " Exception caught when importing user info to db" . PHP_EOL;
        } finally {
            $db->close();
        }

        return false;
    }

    public function getUsers(): array
    {
        return $this->users;
    }

    // Gets new users from the server
    private function getNewUsersFromServer(): ?array
    {
        $newUsers = array();
        $cachedUserNumbers = array();
        if (!file_exists(self::userCache)) {
            echo (new DateTime())->format('Y-m-d h:i:s') . " Cache file does not exist. This is the first call, get all users from the server." . PHP_EOL;
            $this->loadFromServer();

            return $this->users;
        }

        $fd = fopen(self::userCache, 'r');
        if (!$fd) {
            echo (new DateTime())->format('Y-m-d h:i:s') . " Failed to open file " . self::userCache . PHP_EOL;
            return null;
        }

        // Get cached user id from the csv file
        while (($line = fgetcsv($fd)) !== FALSE) {
            $user = (array) $line;
            $cachedUserNumbers[] = $user[0];
        }

        // Get all the users from the server
        $allUsers = $this->getUsersFromServer();
        foreach ($allUsers as $user) {
            $userName = $user['userName'];
            // Get user info for the user missing in the cache
            if (!in_array($userName, $cachedUserNumbers)) {
                $newUserInfo = $this->getUserInfoFromServer($userName);
                if (!is_null($newUserInfo)) {
                    $newUsers[] = $newUserInfo;
                }
            }
        }

        return $newUsers;
    }
}