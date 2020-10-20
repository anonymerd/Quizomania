<?php

    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: GET');

    require_once __DIR__ . '/../../config/Database.php';
    require_once __DIR__ . '/../../models/Game.php';
    require_once __DIR__ . '/../../auth/Validation/validate.php';

    // Checking the request method type.
    if($_SERVER['REQUEST_METHOD'] !== 'GET')
    {
        // ! Invalid HTTP Request Method.
        header($_SERVER['SERVER_PROTOCOL'].' 405 Method Not Allowed', true, 405);
        throwError($error = '405 Method Not Allowed');
    }

    // Creating a new database connection...
    $database = new Database();
    $dbConn = $database->connect();

    // Creating new Game object
    $game = new Game($dbConn);

    // Fetching the leaderboard.
    $result = $game->getLeaderboard();

    if($result)
    {
        // Leaderboard fetched successfuly.

        $numOfRows = $result->num_rows;
        $data = array();
        // print_r($result);
        for($i = 0; $i < $numOfRows; $i++)
            array_push($data, $result->fetch_assoc());

        returnResponse($message = $numOfRows . ' Entries Found!', $data = $data);
    }
    else
    {
        // LeaderBoard could not be fetched.
        throwError($message = 'Leaderboard could not be found!.');
    }








?>