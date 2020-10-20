<?php

    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: PUT');

    if($_SERVER['REQUEST_METHOD'] !== 'PUT')
    {
        // ! Invalid HTTP Request Method
        header($_SERVER["SERVER_PROTOCOL"]." 405 Method Not Allowed", true, 405);
        exit();
    }

    include_once '../../config/Database.php';
    include_once '../../models/User.php';

    $database = new Database();
    $dbConn = $database->connect();

    $user = new User($dbConn);

    $data = json_decode(file_get_contents('php://input'), true);

    $output = array('message' => 'Updation Failed', 'result' => false); // Presetting the values. These values will be updated on successfull update attempt.

    // Checking the received data.

    if(!isset($data['id']))
        $output['error'] = 'ID not Available';
    else if(!isset($data['property']))
        $output['error'] = 'Property not Available';
    else if(!isset($data['newVal']))
        $output['error'] = 'New Val not Available.';
    else
    {
        // Filtering the data.
        $user->id = (int)$dbConn->real_escape_string($data['id']);
        $data['property'] = $dbConn->real_escape_string($data['property']);
        $data['newVal'] = $dbConn->real_escape_string($data['newVal']);

        // Checking Whether ID is valid.
        $chkID = $user->checkID();

        if($chkID)
        {
            // * ID exists --- Updation Allowed.

            // If property is Email then checking whether that email is already is taken.
            if($data['property'] === 'Email')
            {
                $user->email = $dbConn->real_escape_string($data['newVal']);
                $chkUser = $user->checkEmail();
    
                if(!$chkUser)
                {
                    // Updating the user's information
                    $result = $user->updateUser($data['property'], $data['newVal']);
    
                    $output['message'] = $result ? 'Updated Successfuly' : 'Updation Failed';
                    $output['result'] = $result;
    
                    if(!$result)
                        $output['error'] = $dbConn->error;
                }
                else
                {
                    // * Email already taken.
                    $output['message'] = "Email Not Updated";
                    $output['error'] = "This email is already taken.";
                }
            }
            else
            {
                // Updating the user's information
                $result = $user->updateUser($data['property'], $data['newVal']);
        
                $output['message'] = $result ? 'Updated Successfuly' : 'Updation Failed';
                $output['result'] = $result;
        
                if(!$result)
                    $output['error'] = $dbConn->error;
    
            }

        }
        else
        {
            // ! ID does not exists.
            $output['error'] = "Invalid ID / User with this ID does not exists.";
        }

    }

    echo json_encode($output);

?>