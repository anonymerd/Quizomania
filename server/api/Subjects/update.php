<?php

    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: PUT');

    require_once '../../config/Database.php';
    require_once '../../models/Subject.php';
    require_once '../../auth/Validation/validate.php';

    // Checking the request type.
    if($_SERVER['REQUEST_METHOD'] !== 'PUT')
    {
        // ! Invalid HTTP Request Method
        header($_SERVER["SERVER_PROTOCOL"]." 405 Method Not Allowed", true, 405);
        throwError($error = '405 Method not Allowed');
    }

    $database = new Database();
    $dbConn = $database->connect();

    $subject = new Subject($dbConn);

    $data = json_decode(file_get_contents('php://input'), true);

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

    // Checking the received data.

    // Validating the keys.
    validateKeys($data, ['ID', 'Property', 'NewVal']);

    // Validating the received data.
    validateData('ID', $data['ID'], 'NUMERIC');
    validateData('Property', $data['Property'], 'STRING');
    validateData('NewVal', $data['NewVal'], 'STRING');



    // Filtering the data.
    $subject->id = (int)$dbConn->real_escape_string($data['ID']);
    $data['Property'] = strtolower($dbConn->real_escape_string($data['Property']));
    $data['NewVal'] = $dbConn->real_escape_string($data['NewVal']);

    // Checking Whether ID is valid.
    $chkID = $subject->checkID();

    if($chkID)
    {
        // * ID exists --- Updation Allowed.

        // If property is Name then checking whether that email is already is taken.
        if($data['Property'] === 'name')
        {
            $subject->name = strtolower($dbConn->real_escape_string($data['NewVal']));
            $chkSubject = $subject->checkName();

            if(!$chkSubject)
            {
                // Updating the subject's information
                $result = $subject->updateSubject("SubjectName", $data['NewVal']);

                if($result)
                    returnResponse($message = 'Updated Successfuly');
                else
                    throwError($error = 'Database error', $message = 'Subject Name Not Updated');
            }
            else
            {
                // * Name already taken.
               throwError($error = 'Subject Name already taken', $message = 'That name is already taken. Choose another name.');
            }
        }
        else
        {

            // * Currently only Name of a subject can be updated.
            
            // Invalid Property
            throwError($error = 'Invalid property Name');


            // ****************************** To update any other property other than Name UNCOMMENT the following code *********************************


            // // Checking if property is legal.
            // switch($data['Property'])
            // {
            //     case 'name' : $data['Property'] = 'Name'; break;
            //     default : $data['Property'] = false; break;
            // }


            // if($data['Property'])
            // {
            //     // Valid Property
            //     // Updating the subject's information
            //     $result = $subject->updateSubject($data['Property'], $data['NewVal']);
        
            //     if($result)
            //         returnResponse($message = 'Updated Successfuly');
            //     else
            //         throwError($error = 'Database error', $message = 'Subject Not Updated');;

            // }
            // else
            // {
            //     // ! Invalid Property
            //     throwError($error = 'Invalid property Name');
            // }

        }

    }
    else
    {
        // ! ID does not exists.
        throwError($error = 'Invalid ID', $message = 'Subject with this ID does not exist.');
    }

?>