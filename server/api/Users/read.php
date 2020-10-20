<?php

    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: GET');

    require_once __DIR__ . '/../../config/Database.php';
    require_once __DIR__ . '/../../models/User.php';
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

    // echo getType($tokenUser['isAdmin']) . "\n";

    // Creating a new database connection...
    $database = new Database();
    $dbConn = $database->connect();

    // Creating new User object
    $user = new User($dbConn);
    
    if (isset($_GET['id']))
    {
        // Validating type of ID.
        validateData('ID(GET)', $_GET['id'], 'NUMERIC');

        $user->id = (int)$dbConn->real_escape_string($_GET['id']);

        // Getting single user using the ID.
        $result = $user->getSingleUser();
    
        if($result)
        {
            // User Found
                returnResponse($message = 'User Found!', $data = $result);
        }
        else
        {
            // User with this id could not be found.
                throwError($message = 'No User/Record Found!');
        }
        
    }
    else
    {
        $result = $user->getUsers();
    
        if($result)
        {
            // User(s) found.
            
            $numOfRows = $result->num_rows;
            $data = array();
            for ($i=0; $i < $numOfRows; $i++)
                array_push($data, $result->fetch_assoc());
    
            returnResponse($message = $numOfRows . ' Users Found!', $data = $data);
        }
        else
        {
            // No users could be found.
            throwError($message = 'No Users/Records Found!');
        }
    }

?>