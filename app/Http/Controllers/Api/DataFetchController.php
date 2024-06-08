<?php

namespace App\Http\Controllers\Api;

use App\Helpers\UsersMigrator;
use App\Http\Controllers\Controller;

class DataFetchController extends Controller
{
    public function index()
    {

        $url = "https://jih-app-server-prod.azurewebsites.net";
        $migrator = new UsersMigrator($url);
        $migrator->loadFromServer();
        $users = $migrator->getUsers();
        dd($users);
        $migrator->exportToCSV("users.csv", $users);
        // $migrator->importCSVToDB('test1', 'user', 'pwd', 'db', 'table', 'users.csv');
    }
}