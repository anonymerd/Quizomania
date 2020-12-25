<?php

    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Origin: *');

    require_once __DIR__ . '/../../config/Database.php';
    require_once __DIR__ . '/../../models/User.php';
    require_once __DIR__ . '/../../auth/Validation/validate.php';
    
    // Checking the request method type.
    if($_SERVER['REQUEST_METHOD'] !== 'POST')
    {
        // ! Invalid HTTP Request Method
        header($_SERVER["SERVER_PROTOCOL"]." 405 Method Not Allowed", true, 405);
        throwError($error = '405 Method not Allowed');
    }

    $database = new Database();
    $dbConn = $database->connect();

    $user = new User($dbConn);

    // Getting raw posted data and converting it into an array.
    $data = json_decode(file_get_contents("php://input"), true);

    // Checking the recieved data.

    // Validate Keys.
    validateKeys($data, ['Name', 'Email', 'Password']);

    // Validating the received data.
    validateData('Name', $data['Name'], STRING);
    validateData('Email', $data['Email'], STRING); // TODO:- Validate Email to be an email using regular expression and not as a string later.
    validateData('Password', $data['Password'], STRING);

    // Assigning all the data recieved after filtering.
    $user->name = $dbConn->real_escape_string($data['Name']);
    $user->email = $dbConn->real_escape_string($data['Email']);
    $user->pass = $dbConn->real_escape_string($data['Password']);
    $user->isAdmin = 0;

    // Checking whether user already exist.
    $chkUser = $user->checkEmail();

    if(!$chkUser)
    {
        // Creating/Adding new user.
        $result = $user->addUser();

        if($result)
            returnResponse($message = 'Added Successfuly');
        else
            throwError($error = 'Database error ' . $dbConn->error, $message = 'User Not Added');

    }
    else
    {
        // User with this email already exists.
        throwError($error = 'User with this email already exists.', $message = 'User Not Added.');
    }

?>