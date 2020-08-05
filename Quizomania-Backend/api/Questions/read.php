<?php

    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: GET');

    require_once __DIR__ . '/../../config/Database.php';
    require_once __DIR__ . '/../../models/Question.php';
    require_once __DIR__ . '/../../models/Subject.php';
    require_once __DIR__ . '/../../auth/Validation/validate.php';

    // Checking the request method type.
    if($_SERVER['REQUEST_METHOD'] !== 'GET')
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

    // Creating a new database connection...
    $database = new Database();
    $dbConn = $database->connect();

    // Creating new Question object
    $question = new Question($dbConn);

    if (isset($_GET['id']))
    {
        // Validating type of ID.
        validateData('ID(GET)', $_GET['id'], 'NUMERIC');

        $question->id = (int)$dbConn->real_escape_string($_GET['id']);

        // Getting question having particular ID.
        $result = $question->getSingleQuestion(true);

        if($result)
        {
            // Question Found
            returnResponse($message = 'Question Found!', $data = $result);
        }
        else
        {
            // Question with this id could not be found.
            throwError($message = 'No Question Found!');
        }
    }
    else if(isset($_GET['subID']))
    {
        // Validating type of ID.
        validateData('SubId(GET)', $_GET['subID'], 'NUMERIC');

        // Checking whether subject with this id exist.
        $subject = new Subject($dbConn);

        $subject->id = (int)$dbConn->real_escape_string($_GET['subID']);

        $chkSub = $subject->checkID();

        if(!$chkSub)
        {
            throwError($error = 'Invalid Subject ID');
        }

        $question->subID = $subject->id;

        // Getting all questions of a particular subject.
        $result = $question->getQuestions(true);

        if($result)
        {
            // Question Found

            $numOfRows = $result->num_rows;
            $data = array();
            for ($i=0; $i < $numOfRows; $i++)
                array_push($data, $result->fetch_assoc());

            returnResponse($message = 'Question Found!', $data = $data);
        }
        else
        {
            // Question with this id could not be found.
            throwError($message = 'No Questions Found!');
        }
    
    }
    else
    {
        // Getting all the questions.
        $result = $question->getQuestions(true);

        if($result)
        {
            // Questions(s) found.
            
            $numOfRows = $result->num_rows;
            $data = array();
            for ($i=0; $i < $numOfRows; $i++)
                array_push($data, $result->fetch_assoc());
    
            returnResponse($message = $numOfRows . ' Questions Found!', $data = $data);
        }
        else
        {
            // No Questions could be found.
            throwError($message = 'No Questions/Records Found!');
        }
    }
    
?>