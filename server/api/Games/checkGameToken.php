<?php


    /*
    * This api checks the content of the token and accordingly returns the data.
    * If the token payload contains info about subject and question then it means the quiz has started.
    * If the token payload contains info about result the it means that the quiz has ended.
    */

    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: GET');

    require_once __DIR__ . '/../../config/Database.php';
    require_once __DIR__ . '/../../models/Question.php';
    require_once __DIR__ . '/../../models/Subject.php';
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

    // Creating a new database connection...
    $database = new Database();
    $dbConn = $database->connect();

    // Creating necessary objects
    $user = new User($dbConn);
    $subject = new Subject($dbConn);
    $question = new Question($dbConn);

    $payload = getTokenPayload();

    if( $payload->gameOver !== false)
    {
        // Game has already ended.
        $payload->name = $tokenUser->name;
        returnResponse($message = 'Game has already ended. Showing the results.', $data = $payload);
    }
    else if($payload->subID !== false && $payload->randomQues !== false && $payload->QNo !== false)
    {
        // Game is still alive continuing from, the same position(question)
        $data = array(
            'name' => $tokenUser->name,
            'subID' => $payload->subID,
            'randomQues' => $payload->randomQues,
            'answers' => $payload->answers,
            'QNo' => $payload->QNo
        );
        returnResponse($message = 'Game is still alive', $data = $data);
    }
    else
    {
        // New game.
        $data = array(
            'id' => $tokenUser->id,
            'name' => $tokenUser->name,
            'email' => $tokenUser->email,
        );
        returnResponse($message = 'New Game', $data = $data);
    }

?>