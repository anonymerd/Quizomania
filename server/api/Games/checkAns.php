<?php

    // This module checks the answers, calculate the score and generates the results.

    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');

    require_once '../../config/Database.php';
    require_once '../../models/Question.php';
    require_once '../../models/Subject.php';
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
     * The returned value are the details about the verified user as associative array.
     */
    $tokenUser = validateAccessToken();

    $database = new Database();
    $dbConn = $database->connect();

    $question = new Question($dbConn);
    $subject = new Subject($dbConn);
    $game = new Game($dbConn);

    // Getting raw posted data
    $data = json_decode(file_get_contents("php://input"), true);

    // Checking the received data.

    // Validating the keys.
    validateKeys($data, ['Count', 'Questions', 'SubID', 'Answers']);

    // Validating the received data.
    validateData('SubID', $data['SubID'], 'NUMERIC');
    validateData('Count', $data['Count'], 'NUMERIC');
    validateData('Questions', $data['Questions'], 'ARRAY');
    validateData('Answers', $data['Answers'], 'ARRAY');

    // Initialising useful variables
    $totalQues = $data['Count'];
    $attemptedQues = 0;
    $correctAns = 0;
    $score = 0;
    $correctResponse = array();
    $scoresArray = array();

    for ($i=0; $i < $totalQues; $i++)
    {
        $question->id = $data['Questions'][$i];
        $ques = $question->getSingleQuestion(true);

        if($ques['Answer'] === $data['Answers'][$i])
        {
            $correctAns++;
            array_push($scoresArray, 10);
        }
        else
            array_push($scoresArray, 0);

        if(in_array($data['Answers'][$i], array('A', 'B', 'C', 'D')))
            $attemptedQues++;

        array_push($correctResponse, $ques['Answer']);
    }

    $score = $correctAns*10;


    // Creating new Game entry.

    $game->playerID = $tokenUser->id;
    $game->subID = $data['SubID'];
    $game->totalQues = count($data['Questions']);
    $game->attemptedQues = $attemptedQues;
    $game->correctAns = $correctAns;
    $game->score = $score;
    $game->createdAt = 'CURRENT_TIMESTAMP';


    // Generating new token
    $payload = array(
        'userID' => $tokenUser->id,
        'Admin' => $tokenUser->isAdmin,
        'subID' => $game->subID, // Subject ID
        'randomQues' => $data['Questions'], // random questions ID array
        'answers' => $data['Answers'],
        'gameOver' => true, // Is the game over?
        "name" => $tokenUser->name
    );

    $token = generateToken($payload);

    // print_r($token);

    $result = $game->addGame();

    $returndata = array(
        'name' => $tokenUser->name,
        'totalQues' => $game->totalQues,
        'attemptedQues' => $game->attemptedQues,
        'correctAns' => $game->correctAns,
        'score' => $game->score,
        'userResponse' => $data['Answers'],
        'correctResponse' => $correctResponse,
        'scoresArray' => $scoresArray
    );


    if($result)
        returnResponse($message = 'Game Recorded Successfuly', $data = $returndata, $token = $token);
    else
        throwError($error = 'Database error', $message = 'Game Not Recorded.');


?>