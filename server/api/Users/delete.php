<?php

    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: DELETE');

    require_once '../../config/Database.php';
    require_once '../../models/User.php';
    require_once '../../auth/Validation/validate.php';

    // Checking the request type.
    if($_SERVER['REQUEST_METHOD'] !== 'DELETE')
    {
        // ! Invalid HTTP Request Method
        header($_SERVER["SERVER_PROTOCOL"]." 405 Method Not Allowed", true, 405);
        throwError($error = '405 Method not Allowed');    
    }

    /**
     *  Validating the token.
     * This will only return if the token is successfully verified.
     * The returned value are the details about the verified user as associative array.
     */
    $tokenUser = validateAccessToken();

    // Only admin can access this. Therefore, verifying the admin.
    if(!$tokenUser->isAdmin)
    {
        // The verified user is not an admin.
        throwError($error = 'Area Forbidden', $message = 'Only admins have access to this resource');
    }

    // * All things set, valid and verified.

    // Creating a new database connection.
    $database = new Database();
    $dbConn = $database->connect();

    // Creating a new user object.
    $user = new User($dbConn);

    // Getting raw posted data and converting it into an array.
    $data = json_decode(file_get_contents('php://input'), true);

    // Checking the received data.

    // Validating the keys.
    validateKeys($data, ['ID']);

    // Validating the received data.
    validateData('ID', $data['ID'], 'NUMERIC');

    // Assing the data received after filtering.
    $user->id = $dbConn->real_escape_string($data['ID']);

    // Checking whether userID exists in the database.
    $chkID = $user->checkID();
    
    if($chkID)
    {
        // * User available ------ Deletion Allowed.

        // Deleting the user.
        $result = $user->deleteUser();

        if($result)
            returnResponse($message = 'User Deleted Successfuly');
        else
            throwError($error = 'Database error', $message = 'User Not Deleted');
    }
    else
    {
        // User does Not exist 
        throwError($error = 'Invalid ID', $message = 'User with this ID does not exist');
    }

?>