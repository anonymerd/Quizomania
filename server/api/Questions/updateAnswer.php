<?php

    // This module updates answer in the token.

    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: PUT');

    require_once __DIR__ . '/../../auth/Validation/validate.php';

    // Checking the request method type.
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

    $payload = getTokenPayload();

    if(!$payload->answers)
        $payload->answers = array();

    // Getting the raw posted data.
    $data = json_decode(file_get_contents('php://input'), true);

    // Checking the received data.

    // Validating the keys.
    validateKeys($data, ['Answer']);

    // Validating the received data.
    validateData('Answer', $data['Answer'], 'STRING');

    // * The answer should contain one the four options--- [A, B, C, D, Z]. Z for skipped option.
    if(!in_array($data['Answer'], array('A', 'B', 'C', 'D', 'Z')))
        throwError($error = 'Invalid value in the answer field.', $message = 'The answer should contain one the four options --- [A, B, C, D, Z]');

    // All set
    array_push($payload->answers, $data['Answer']);

    // Generating new token
    $newPayload = array(
        'userID' => $payload->userID,
        'Admin' => $payload->Admin,
        'subID' => $payload->subID, // Subject ID
        'randomQues' => $payload->randomQues, // random questions ID array
        'QNo' => ++$payload->QNo,
        'answers' =>  $payload->answers,
        'gameOver' => false
    );
    $token = generateToken($newPayload);

    $data = array(
        'token' => $token,
        'name' => $tokenUser->name,
        'subID' => $payload->subID,
        'randomQues' => $payload->randomQues,
        'answers' => $payload->answers,
        'QNo' => $payload->QNo
    );

    returnResponse($message = 'Answer Successfully Updated in the token', $data = $data);

?>