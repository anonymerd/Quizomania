<?php

    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Origin: *');


    require_once __DIR__ . '/../../php-mailer/PHPMailerAutoload.php';
    require_once __DIR__ . '/../../auth/Validation/validate.php';
    require_once __DIR__ . '/../../models/User.php';
    require_once __DIR__ . '/../../config/Database.php';
    require_once __DIR__ . '/../../config/Globals.php';
    
    // Checking the request method type.
    if($_SERVER['REQUEST_METHOD'] !== 'POST')
    {
        // ! Invalid HTTP Request Method
        header($_SERVER["SERVER_PROTOCOL"]." 405 Method Not Allowed", true, 405);
        throwError($error = '405 Method not Allowed');
    }

    // Getting raw posted data and converting it into an array.
    $data = json_decode(file_get_contents("php://input"), true);

    // Checking the recieved data.

    // Validate Keys.
    validateKeys($data, ['Email']);

    // Validating the received data.
    validateData('Email', $data['Email'], STRING); // TODO:- Validate Email to be an email using regular expression and not as a string later.

    // Creating a new database connection...
    $database = new Database();
    $dbConn = $database->connect();

    // Creating a user object.
    $user = new User($dbConn);

    // Refining the data.
    $email = $dbConn->real_escape_string($data['Email']);
    $user->email = $email;

    // Checking whether it is a valid email id.
    if(!$user->checkEmail())
        throwError($error = "Email does not exist.", $message = 'This Email is no associated with any account.');

    $userDetails = $user->getSingleUser();

    $payload = array(
        'iat' => time(),
        'iss' => 'Anonymerd',
        'sub' => 'Reset Password',
        'exp' => time() + 30 * 60,
        'id' => $user->id
    );

    $token = generateToken($payload);
    $resetLink = $SERVER_ADDRESS . 'forgotPassword.php?token=' . $token;

    $message = "
    <style> 

        @import url('https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&display=swap');

        .message {
            width: 60%;
            margin: 0 auto;
            font-family: 'Open Sans', sans-serif;
            font-size: 16px !important;
        }

        .message-body {
            padding: 30px;
        }

        .message-body p,
        .message-body button {
            display: block;
            margin : 25px 0 !important;
        }

        .message-body .greetings {
            font-size: 150%;
            font-weight: 600;
        }

        .message-body #reset-password-button {
            margin: 0 auto;
            font-family: 'Open Sans', sans-serif;
            cursor: pointer;
            padding: 5px;
        }

        .message-body .small-text {
            font-size: 80% !important;
        }

    </style>
    <div class='message'>
        <div class='message-body'>
            <span class='greetings'> Hi Anonymerd! </span>
            <br>
            <p>
                You recently requested to reset your password for your Quizomania Account. Click the button below to reset it.
            </p>
            <a href=$resetLink type='button' id='reset-password-button'> Reset your password </a>
            <p>
                If you did not request a password reset, please ignore this email or reply to let us know. This password reset is only <b> valid for the next 30 minutes </b>.
            </p>
            <p>
            Thanks,
            <br>
            Anonymous Nerd
            </p>
            <p class='small-text'>
                <b>P.S.</b> If you're having trouble clicking the password button, copy and paste the URL below into your web browser.
                <br>
                <a href='$resetLink' id='reset-link'> $resetLink </a>
            </p>
        </div>
    </div>
    ";

    // Creating new PHP Mailer Objects.
    $mailToUser = new PHPMailer;
    
    // $mailToUser->SMTPDebug = 3;
    
    $mailToUser->IsSMTP();        //Sets Mailer to send message using SMTP
    $mailToUser->Host = 'smtp.gmail.com';  //Sets the SMTP hosts
    $mailToUser->Port = '587';        //Sets the default SMTP server port
    $mailToUser->SMTPAuth = true;       //Sets SMTP authentication. Utilizes the Username and Password variables
    $mailToUser->Username = '';/*! YOUR GMAIL ID */     //Sets SMTP username
    $mailToUser->Password = ''; /*! YOUR GMAIL PASSWORD */   //Sets SMTP password
    $mailToUser->SMTPSecure = '';/* ! YOUR GMAIL ID*/       //Sets connection prefix. Options are "", "ssl" or "tls"
    $mailToUser->From = '';     //Sets the From email address for the message
    $mailToUser->FromName = 'Quizomania Admin';    //Sets the From name of the message
    $mailToUser->AddAddress($user->email);//Adds a "To" address
    $mailToUser->WordWrap = 50;       //Sets word wrapping on the body of the message to a given number of characters
    $mailToUser->IsHTML(true);       //Sets message type to HTML    
    $mailToUser->Subject = 'Reset Password';    //Sets the Subject of the message
    $mailToUser->Body = $message;    //An HTML or plain text message body.
    
    
    if($mailToUser->Send())        //Send an Email. Return true on success or false on error
    {
        returnResponse($message = 'Reset Password Link was successfuly sent');
    }
    else
    {
        throwError($error = 'SMTP Error', $message = 'Email could not be sent.' . $mailToUser->ErrorInfo . '\n' . $mailToSender->ErrorInfo);
    }


?>