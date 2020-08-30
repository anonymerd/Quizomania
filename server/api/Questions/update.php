<?php

    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: PUT');

    require_once '../../config/Database.php';
    require_once '../../models/Question.php';
    require_once '../../models/Subject.php';
    require_once '../../auth/Validation/validate.php';

    // Checking the request type.
    if($_SERVER['REQUEST_METHOD'] !== 'PUT')
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

    $question = new Question($dbConn);
    $subject = new Subject($dbConn);

    $data = json_decode(file_get_contents('php://input'), true);

    // Checking the received data.

    // Validating the keys.
    validateKeys($data, ['ID', 'Property', 'NewVal']);

    // Validating the received data.
    validateData('ID', $data['ID'], 'NUMERIC');
    validateData('Property', $data['Property'], 'STRING');
    validateData('NewVal', $data['NewVal'], 'STRING');

    // Filtering the data.
    $question->id = (int)$dbConn->real_escape_string($data['ID']);
    $data['Property'] = strtolower($dbConn->real_escape_string($data['Property']));
    $data['NewVal'] = $dbConn->real_escape_string($data['NewVal']);

    // Checking Whether ID is valid.
    $chkID = $question->checkID();

    if($chkID)
    {
        // * ID exists --- Updation Allowed.
        
        // Checking if Property is legal.
        // If it is legal, preparing it for database updation by making sure that the cases are correct.
        switch($data['Property'])
        {
            case 'question' : $data['Property'] = 'Question'; break;
            case 'optiona' : $data['Property'] = 'OptionA'; break;
            case 'optionb' : $data['Property'] = 'OptionB'; break;
            case 'optionc' : $data['Property'] = 'OptionC'; break;
            case 'optiond' : $data['Property'] = 'OptionD'; break;
            case 'answer' : $data['Property'] = 'Answer'; break;
            case 'subid' : $data['Property'] = 'SubID'; break;
            default : $data['Property'] = false; break;
        }

        if($data['Property'] === 'Answer')
        {
            // The answer should contain one the four options--- [A, B, C, D].
            if(!in_array($data['Answer'], array('A', 'B', 'C', 'D')))
                throwError($error = 'Invalid value in the answer field.', $message = 'The answer should contain one the four options --- [A, B, C, D]');
        }
        else if($data['Property'] === 'SubID')
        {
            // Checking the subject ID.
            $subject->id = $data['NewVal'];

            if(!$subject->checkID())
            {
                // Invalid ID.
                throwError($error = 'Invalid Subject ID', $error = 'Subject with this ID is not available.');
            }
            
        }
        else if(!$data['Property'])
        {
            // ! Invalid Property
            throwError($error = 'Invalid property Name');
        }

        // Valid Property
        // Updating the question's information
        $result = $question->updateQuestion($data['Property'], $data['NewVal']);

        if($result)
            returnResponse($message = 'Updated Successfuly');
        else
            throwError($error = 'Database error', $message = 'Question Not Updated');

    }
    else
    {
        // ! ID does not exists.
        throwError($error = 'Invalid ID', $message = 'Question with this ID is not available.');
    }


?>