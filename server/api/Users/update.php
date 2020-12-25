<?php

    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: PUT');

    require_once '../../config/Database.php';
    require_once '../../models/User.php';
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


    // Creating a new database connection.
    $database = new Database();
    $dbConn = $database->connect();

    // Creating a new user.
    $user = new User($dbConn);

    // Getting raw posted data and converting it into an array.
    $data = json_decode(file_get_contents('php://input'), true);

    // Checking the received data.

    // Validating the keys.
    validateKeys($data, ['ID', 'Property', 'NewVal']);

    // Validating the received data.
    validateData('ID', $data['ID'], 'NUMERIC');
    validateData('Property', $data['Property'], 'STRING');
    validateData('NewVal', $data['NewVal'], 'STRING');


    // * Only admin and the user itself can access this. Therefore, verifying the user.
    
    // print_r($tokenUser);
    // echo "\n".$tokenUser['ID']."\n".$data['ID']."\n";

    if(!$tokenUser->isAdmin && $tokenUser->id != $data['ID'])
    {
        // The verified user is neither an admin nor the same user whose info is being changed.
        throwError($error = 'Area Forbidden', $message = 'You do not have permissions to access this resource.');
    }

    // * All things set, valid and verified.

    // Filtering the data.
    $user->id = (int)$dbConn->real_escape_string($data['ID']);
    $data['property'] = $dbConn->real_escape_string(strtolower($data['Property']));
    $data['NewVal'] = $dbConn->real_escape_string($data['NewVal']);

    // Checking Whether ID is valid.
    $chkID = $user->checkID();

    if($chkID)
    {
        // * ID exists --- Updation Allowed.

        // If property is Email then checking whether that email is already is taken.
        if($data['Property'] === 'email')
        {
            $user->email = $dbConn->real_escape_string($data['NewVal']);
            $chkUser = $user->checkEmail();

            if(!$chkUser)
            {
                // Updating the user's information
                $result = $user->updateUser("Email", $data['NewVal']);

                if($result)
                    returnResponse($message = 'Updated Successfuly');
                else
                    throwError($error = 'Database error', $message = 'User Not Updated');
            }
            else
            {
                // * Email already taken.
                throwError($error = 'Email already taken', $message = 'That email is already taken. Choose another email.');
            }
        }
        else
        {
            // Checking if Property is legal.
            // If it is legal, preparing it for database updation by making sure that the cases are correct.
            switch($data['property'])
            {
                case 'name' : $data['Property'] = 'Name'; break;
                case 'email' : $data['Property'] = 'Email'; break;
                case 'pass' :
                case 'password' : $data['Property'] = 'Password'; break;
                default : $data['Property'] = false; break;
            }


            if($data['Property'])
            {
                // Valid Property
                // Updating the user's information
                $result = $user->updateUser($data['Property'], $data['NewVal']);

                if($result)
                    returnResponse($message = 'Updated Successfuly');
                else
                    throwError($error = 'Database error', $message = 'User Not Updated');

            }
            else
            {
                // ! Invalid Property
                throwError($error = 'Invalid property Name');
            }
        }
    }
    else
    {
        // ! ID does not exists.
        throwError($error = 'Invalid ID', $message = 'User with this ID does not exist.');
    }

?>