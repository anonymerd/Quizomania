<?php

    // API to log a user in.

    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Origin: *');

    require_once __DIR__ . "/../../config/Database.php";
    require_once __DIR__ . "/../../models/User.php";
    require_once __DIR__ . "/../../auth/Validation/validate.php";

    // Checking the request type.
    if($_SERVER['REQUEST_METHOD'] !== 'POST')
    {
        // ! Invalid HTTP Request Method
        header($_SERVER["SERVER_PROTOCOL"]." 405 Method Not Allowed", true, 405);
        throwError($error = '405 Method not Allowed');    
    }

    // Creating a new database connecti__DIR__ . o
    $database = new Database();
    $dbConn = $database->connect();

    // Creating a new user object.
    $user = new User($dbConn);

    // Getting raw posted data and converting it into an array.
    $data = json_decode(file_get_contents('php://input'), true);

    // Checking the received data.

    // Validating the keys.
    validateKeys($data, ['Email', 'Password']);

    // Validating the received data.
    validateData('Email', $data['Email'], 'STRING'); // TODO:- Validate Email to be an email using regular expression and not as a string later.
    validateData('Password', $data['Password'], 'STRING');

    // Assing the data received after filtering.
    $user->email = $dbConn->real_escape_string($data['Email']);
    $password = $dbConn->real_escape_string($data['Password']);

    // Checking whether user exist.
    $chkEmail = $user->checkEmail();

    if(!$chkEmail)
    {
        // User with this email does not exist.
        throwError($error = 'Invalid Email', $message = 'User with this email does not exist.');
    }

    $data = $user->getSingleUser();
    
    if(!$data)
    {
        // User with this email does not exist.
        throwError($error = 'Invalid Email', $message = 'User with this email does not exist.');
    }
    
    // Checking Password.
    if($data['Password'] !== $password)
    {
        // Passwords do not match.
        throwError($error = 'Invalid Password', $message = 'Passwords do not match.');

    }

    // Generating token.
    $token = generateToken($user->id, $user->isAdmin);

    returnResponse($message = 'Login successful', $data = ['token' => $token]);

?>