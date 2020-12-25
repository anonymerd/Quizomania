<?php

    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: DELETE');

    require_once '../../config/Database.php';
    require_once '../../models/Question.php';
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
     * The returned value are the details about the verified Question as associative array.
     */
    $tokenQuestion = validateAccessToken();

    // Only admin can access this. Therefore, verifying the admin.
    if(!$tokenQuestion->isAdmin)
    {
        // The verified Question is not an admin.
        throwError($error = 'Area Forbidden', $message = 'Only admins have access to this resource');
    }

    // * All things set, valid and verified.

    $database = new Database();
    $dbConn = $database->connect();

    $question = new Question($dbConn);

    $data = json_decode(file_get_contents('php://input'), true);

   // Checking the received data.

    // Validating the keys.
    validateKeys($data, ['ID']);

    // Validating the received data.
    validateData('ID', $data['ID'], 'NUMERIC');

    // Assing the data received after filtering.
    $question->id = $data['ID'];

    // Checking whether userID exists in the database.
    $chkID = $question->checkID();
    
    if($chkID)
    {
        // * Question available ------ Deletion Allowed.

        $result = $question->deleteQuestion();

        if($result)
            returnResponse($message = 'Question Deleted Successfuly');
        else
            throwError($error = 'Database error', $message = 'Question Not Deleted');
    }
    else
    {
        // Question does Not exist 
        throwError($error = 'Invalid ID', $message = 'Question with this ID does not exist');
    }


?>