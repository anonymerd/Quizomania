<?php

    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');

    require_once '../../config/Database.php';
    require_once '../../models/Question.php';
    require_once '../../models/Subject.php';
    require_once '../../auth/Validation/validate.php';

    // Checking the request type.
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

    $database = new Database();
    $dbConn = $database->connect();

    $question = new Question($dbConn);
    $subject = new Subject($dbConn);

    // Getting raw posted data
    $data = json_decode(file_get_contents("php://input"), true);

    // Checking the received data.

    // Validating the keys.
    validateKeys($data, ['Question', 'OptionA', 'OptionB', 'OptionC', 'OptionD', 'Answer', 'SubID']);

    // Validating the received data.
    validateData('Question', $data['Question'], 'STRING');
    validateData('OptionA', $data['OptionA'], 'STRING');
    validateData('OptionB', $data['OptionB'], 'STRING');
    validateData('OptionC', $data['OptionC'], 'STRING');
    validateData('OptionD', $data['OptionD'], 'STRING');
    validateData('Answer', $data['Answer'], 'STRING');
    validateData('SubID', $data['SubID'], 'NUMERIC');

    // The answer should contain one the four options--- [A, B, C, D].
    if(!in_array($data['Answer'], array('A', 'B', 'C', 'D')))
        throwError($error = 'Invalid value in the answer field.', $message = 'The answer should contain one the four options --- [A, B, C, D]');


    // Assigning all the data recieved after filtering.
    $question->question = $dbConn->real_escape_string($data['Question']);
    $question->optionA = $dbConn->real_escape_string($data['OptionA']);
    $question->optionB = $dbConn->real_escape_string($data['OptionB']);
    $question->optionC = $dbConn->real_escape_string($data['OptionC']);
    $question->optionD = $dbConn->real_escape_string($data['OptionD']);
    $question->answer = $dbConn->real_escape_string($data['Answer']);
    $question->subID = $dbConn->real_escape_string($data['SubID']);

    // ! This functionality is not available currently. 
    // Checking whether question already exist.
    // $chkQuestion = $question->checkBody();

    $chkQuestion = false; // * Delete this if above funcionality becomes available.

    if(!$chkQuestion)
    {
        // Checking the subject id.
        $subject->id = $question->subID;

        if(!$subject->checkID())
        {
            // Invalid ID.
            throwError($error = 'Invalid Subject ID', $error = 'Subject with this ID is not available.');
        }

        // Creating/Adding new Question.
        $result = $question->addQuestion();

        if($result)
            returnResponse($message = 'Added Successfuly');
        else
            throwError($error = 'Database error', $message = 'Question Not Added');

    }
    // else
    // {
    //     // ! This else only executes when checkBody() funtionality becomes available.

    //     // This question (body) already exists.

    //     throwError($error = 'Question with this body already.', $message = 'Question Not Added.')
    // }


?>