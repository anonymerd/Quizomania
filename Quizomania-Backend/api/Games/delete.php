<?php

    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: DELETE');

    if($_SERVER['REQUEST_METHOD'] !== 'DELETE')
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

    $output = array('message' => 'Deletion Failed', 'result' => false); // Presetting the values. These values will be updated on successfull update attempt.

    // Checking the recieved data.
    if(!isset($data['id']))
        $output['error'] = 'ID not Available';
    else
    {
        $user->id = $data['id'];

        $chkID = $user->checkID();
        
        if($chkID)
        {
            // * User available ------ Deletion Allowed.

            $result = $user->deleteUser();

            $output['message'] = $result ? 'Deleted Successfuly' : 'Deletion Failed';
            $output['result'] = $result;

            if(!$result)
                $output['error'] = $dbConn->error; 
        }
        else
        {
            // User does Not exist 
            $output['error'] = 'Invalid ID / User does not exist.';
        }

    }

    echo json_encode($output);

?>