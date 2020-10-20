<?php

    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: GET');

    require_once __DIR__ . '/../../config/Database.php';
    require_once __DIR__ . '/../../models/Game.php';
    require_once __DIR__ . '/../../models/User.php';
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

    // Creating new Game object
    $game = new Game($dbConn);
    $user = new User($dbConn);
    $subject = new Subject($dbConn);

    if (isset($_GET['id']))
    {
        // Validating type of ID.
        validateData('ID(GET)', $_GET['id'], 'NUMERIC');

        $game->id = $dbConn->real_escape_string($_GET['id']);

        // Getting game having particular ID.
        $result = $game->getSingleGame();
    
        if($result)
        {
            // Game Found
            returnResponse($message = 'Game Found!', $data = $result);
        }
        else
        {
            // Game with this id could not be found.
            throwError($message = 'No Game Found!');
        }
        
    }
    else
    {
        if(isset($_GET['playerID']))
        {
            // Validating type of ID.
            validateData('ID(GET)', $_GET['playerID'], 'NUMERIC');

            // Checking whether player/user with this id exist.

            $user->id = (int)$dbConn->real_escape_string($_GET['playerID']);

            $chkUser = $user->checkID();

            if(!$chkUser)
            {
                throwError($error = 'Invalid User ID');
            }

            $game->playerID = $user->id;
        }

        if(isset($_GET['subID']))
        {
            // Validating type of ID.
            validateData('ID(GET)', $_GET['subID'], 'NUMERIC');

            // Checking whether subject with this id exist.

            $subject->id = (int)$dbConn->real_escape_string($_GET['subID']);

            $chkSub = $subject->checkID();

            if(!$chkSub)
            {
                throwError($error = 'Invalid Subject ID');
            }

            $game->subID = $subject->id;
        }

        // Getting all the questions.
        $result = $game->getGames();
    
        if($result)
        {
            // Game(s) found.
            
            $numOfRows = $result->num_rows;
            $data = array();
            for ($i=0; $i < $numOfRows; $i++)
                array_push($data, $result->fetch_assoc());
    
            returnResponse($message = $numOfRows . ' Games Found!', $data = $data);
        }
        else
        {
            // No Game could be found.
            throwError($message = 'No Games/Records Found!');
        }
    }

?>