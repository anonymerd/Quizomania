<?php

    // ! INCOMPLETE
    
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');

    require_once '../../config/Database.php';
    require_once '../../models/Game.php';
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
     * The returned value are the details about the verified game as associative array.
     */
    $tokenGame = validateAccessToken();

    $database = new Database();
    $dbConn = $database->connect();

    $game = new Game($dbConn);

    // Getting raw posted data
    $data = json_decode(file_get_contents("php://input"), true);

    // Checking the received data.

    // Validating the keys.
    validateKeys($data, ['SubID', 'TotalQ', 'AttemptedQ', 'Score', 'CorrectAns']);

    // Validating the received data.
    validateData('SubID', $data['SubID'], 'NUMERIC');
    validateData('TotalQ', $data['TotalQ'], 'NUMERIC');
    validateData('AttemptedQ', $data['AttemptedQ'], 'NUMERIC');
    validateData('Score', $data['Score'], 'NUMERIC');
    validateData('CorrectAns', $data['CorrectAns'], 'NUMERIC');

    // The answer should contain one the four options--- [A, B, C, D].
    if(!in_array($data['CorrectAns'], array('A', 'B', 'C', 'D')))
        throwError($error = 'Invalid value in the CorrectAns field.', $message = 'The answer should contain one the four options --- [A, B, C, D]');

    // Checking the recieved data.
    if(!isset($data['name']))
        $output['error'] = 'Name not Available!';
    else if(!isset($data['email']))
        $output['error'] = 'Email not Available!';
    else if(!isset($data['pass']))
        $output['error'] = 'Password not Available!';
    else
    {
        // Assigning all the data recieved after filtering.
        $game->name = $dbConn->real_escape_string($data['name']);
        $game->email = $dbConn->real_escape_string($data['email']);
        $game->pass = $dbConn->real_escape_string($data['pass']);
        $game->isAdmin = false;

        // Checking whether game already exist.
        $chkGame = $game->checkEmail();

        if(!$chkGame)
        {
            // Creating/Adding new game.
            $result = $game->addGame();
    
            $output['message'] = $result ? 'Added Successfuly' : 'Game Not Added';
            $output['result'] = $result;

            if(!$result)
                $output['error'] = $dbConn->error;

        }
        else
        {
            // Game with this email already exists.
            $output['message'] = "Game Not Added";
            $output['error'] = "Game with this email already exists.";
        }


    }
    
    echo json_encode($output);

?>