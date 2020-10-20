<?php

    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');

    require_once __DIR__ . '/../../config/Database.php';
    require_once __DIR__ . '/../../models/Subject.php';
    require_once __DIR__ . '/../../auth/Validation/validate.php';
    
    // Checking the request method type.
    if($_SERVER['REQUEST_METHOD'] !== 'POST')
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

    // echo getType($tokenUser['isAdmin']) . "\n";

    $database = new Database();
    $dbConn = $database->connect();

    $subject = new Subject($dbConn);

    // Getting raw posted data
    $data = json_decode(file_get_contents("php://input"), true);

    // Checking the recieved data.

    // Validate Keys.
    validateKeys($data, ['Name']);

    // Validating the received data.
    validateData('Name', $data['Name'], STRING);

    // Assigning all the data recieved after filtering.
   
    // Assigning all the data recieved after filtering.
    $subject->name = $dbConn->real_escape_string($data['Name']);
    $subject->totalQues = 0;
    $subject->createdBy = $tokenUser->id;


    // Checking whether subject already exist.
    $chkSubject = $subject->checkName();

    if(!$chkSubject)
    {
        // Creating/Adding new subject.
        $result = $subject->addSubject();

        if($result)
            returnResponse($message = 'Added Successfuly');
        else
            throwError($error = 'Database error', $message = 'Subject Not Added');
    }
    else
    {
        // Subject with this name already exists.
        throwError($error = 'Subject with this name already exists.', $message = 'Subject not added.');
    }

?>