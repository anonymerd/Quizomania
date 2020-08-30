<?php

    header('Content-Type: application/json');
    header('Access-Control-Allow-Method: GET');
    header('Access-Control-Allow-Origin: http://127.0.0.1:5500');
    header('Access-Control-Allow-Headers: Origin, Authorization');

    require_once __DIR__ . '/../../config/Database.php';
    require_once __DIR__ . '/../../models/Subject.php';
    require_once __DIR__ . '/../../auth/Validation/validate.php';

    // echo json_encode(array('fuckoff' => $_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']));

    // Checking the request method type.
    if($_SERVER['REQUEST_METHOD'] !== 'GET')
    {
        // ! Invalid HTTP Request Method
        header($_SERVER["SERVER_PROTOCOL"]." 405 Method Not Allowed", true, 405);
        throwError($error = '405 Method Not Allowed');
    }

    /**
     *  Validating the token.
     * This will only return if the token is successfully verified.
     * The returned value are the details about the verified user as associative array.
     */

    $tokenUser = validateAccessToken(); // Since any valid user can access all the subject name, therefore no need to check further.

    // Creating a new database connection...
    $database = new Database();
    $dbConn = $database->connect();

    // Creating new Subject object
    $subject = new Subject($dbConn);

    $output = array('message' => '', 'data' => array());

    if (isset($_GET['id']))
    {
        // Validating type of ID.
        validateData('ID(GET)', $_GET['id'], 'NUMERIC');

        $subject->id = (int)$dbConn->real_escape_string($_GET['id']);

        // Getting subject having particular ID.
        $result = $subject->getSingleSubject();
    
        if($result)
        {
            // subject Found
                returnResponse($message = 'Subject Found!', $data = $result);
        }
        else
        {
            // subject with this id could not be found.
                throwError($message = 'No Subject/Record Found!');
        }
        
    }
    else
    {
        $result = $subject->getSubjects();
    
        if($result)
        {
            // Subject(s) found.
            
            $numOfRows = $result->num_rows;
            $data = array();
            for ($i=0; $i < $numOfRows; $i++)
                array_push($data, $result->fetch_assoc());
    
            returnResponse($message = $numOfRows . ' Subjects Found!', $data = $data);
        }
        else
        {
            // No Subjects could be found.
            throwError($message = 'No Subjects/Records Found!');
        }
    }

?>