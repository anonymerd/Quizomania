<?php

    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: DELETE');

    require_once '../../config/Database.php';
    require_once '../../models/Subject.php';
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

    $database = new Database();
    $dbConn = $database->connect();

    $subject = new Subject($dbConn);

    $data = json_decode(file_get_contents('php://input'), true);

    // Checking the recieved data.

    // Validating the keys.
    validateKeys($data, ['ID']);

    // Validating the received data.
    validateData('ID', $data['ID'], 'NUMERIC');

    // Assing the data received after filtering.
    $subject->id = (int)$dbConn->real_escape_string($data['ID']);

    $chkID = $subject->checkID();
    
    if($chkID)
    {
        // * Subject available ------ Deletion Allowed.

        $result = $subject->deleteSubject();

        if($result)
            returnResponse($message = 'Subject Deleted Successfuly');
        else
            throwError($error = 'Database error', $message = 'Subject Not Deleted');
    }
    else
    {
        // Subject does Not exist 
        throwError($error = 'Invalid ID', $message = 'Subject with this ID does not exist');
    }

?>