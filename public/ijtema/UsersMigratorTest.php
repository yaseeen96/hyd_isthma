<?php
require_once ("UsersMigrator.php");

$url = "https://jih-app-server-prod.azurewebsites.net";
$migrator = new UsersMigrator($url);
$migrator->initialize();
$pageNo = 0;
$pageCount = 1; // 20
$totalUsers = $migrator->getUsersCount();

do {
    $pagedUsers = $migrator->getUsersInformation($pageNo, $pageCount);
    $migrator->insertIntoDB('mysql', 'root', '', 'ijtema_backend', 'members', $pagedUsers);

    $pageNo++;
} while (($pageNo * $pageCount) < $totalUsers);

// This should be called once - Get all users from the server, export to csv and then can be imported separately
/*$migrator->loadFromServer();
$migrator->exportToCSV("exportedUsers.csv", $migrator->getUsers());
$migrator->importCSVToDB('test', 'test', 'test', 'db', 'usertbl', "users.csv");*/

// This should be called periodically, to just get the new users
/*$newUsers = $migrator->getNewUsersFromServer();
$migrator->insertIntoDB('test', 'test', 'test', 'db', 'usertbl', $newUsers);*/