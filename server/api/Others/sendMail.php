<?php

    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Origin: *');


    require_once __DIR__ . '/../../php-mailer/PHPMailerAutoload.php';
    require_once __DIR__ . '/../../auth/Validation/validate.php';
    
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
    validateKeys($data, ['Name', 'Email', 'Message']);

    // Validating the received data.
    validateData('Name', $data['Name'], STRING);
    validateData('Email', $data['Email'], STRING); // TODO:- Validate Email to be an email using regular expression and not as a string later.
    validateData('Message', $data['Message'], STRING);

    // Creating a new database connection...
    $database = new Database();
    $dbConn = $database->connect();

    // Refining the data.
    $name = $dbConn->real_escape_string($data['Name']);
    $email = $dbConn->real_escape_string($data['Email']);
    $message = $dbConn->real_escape_string($data['Message']);

    // Mail to the Quizomania Admin.

    // Creating new PHP Mailer Objects.
    $mailToAdmin = new PHPMailer;
    
    // $mailToAdmin->SMTPDebug = 3;
    
    $mailToAdmin->IsSMTP();        //Sets Mailer to send message using SMTP
    $mailToAdmin->Host = 'smtp.gmail.com';  //Sets the SMTP hosts
    $mailToAdmin->Port = '587';        //Sets the default SMTP server port
    $mailToAdmin->SMTPAuth = true;       //Sets SMTP authentication. Utilizes the Username and Password variables
    $mailToAdmin->Username = '';/*! YOUR GMAIL ID */     //Sets SMTP username
    $mailToAdmin->Password = ''; /*! YOUR GMAIL PASSWORD */   //Sets SMTP password
    $mailToAdmin->SMTPSecure = '';       //Sets connection prefix. Options are "", "ssl" or "tls"
    $mailToAdmin->From = $email;     //Sets the From email address for the message
    $mailToAdmin->FromName = $name;    //Sets the From name of the message
    $mailToAdmin->AddAddress('');//Adds a "To" address
    $mailToAdmin->WordWrap = 50;       //Sets word wrapping on the body of the message to a given number of characters
    $mailToAdmin->IsHTML(true);       //Sets message type to HTML    
    $mailToAdmin->Subject = 'Message from Quizomania!';    //Sets the Subject of the message
    $mailToAdmin->Body = $message;    //An HTML or plain text message body

    // Acknowledgement mail to the sender.
    
    // Creating new PHP Mailer Object.
    $mailToSender = new PHPMailer;
    
    // $mailToSender->SMTPDebug = 3;


    $mailToSender->IsSMTP();        //Sets Mailer to send message using SMTP
    $mailToSender->Host = 'smtp.gmail.com';  //Sets the SMTP hosts
    $mailToSender->Port = '587';        //Sets the default SMTP server port
    $mailToSender->SMTPAuth = true;       //Sets SMTP authentication. Utilizes the Username and Password variables
    $mailToSender->Username = '';/*! YOUR GMAIL ID */     //Sets SMTP username
    $mailToSender->Password = ''; /*! YOUR GMAIL PASSWORD */   //Sets SMTP password
    $mailToSender->SMTPSecure = '';       //Sets connection prefix. Options are "", "ssl" or "tls"
    $mailToSender->From = '';     //Sets the From email address for the message
    $mailToSender->FromName = $name;    //Sets the From name of the message
    $mailToSender->AddAddress($email);//Adds a "To" address
    $mailToSender->WordWrap = 50;       //Sets word wrapping on the body of the message to a given number of characters
    $mailToSender->IsHTML(true);       //Sets message type to HTML    
    $mailToSender->Subject = 'THANK YOU FOR YOUR MESSAGE';    //Sets the Subject of the message
    $mailToSender->Body = "<p> Quizomania Team thanks you for your valuable message.</p><p> Your Message: $message </p>";    //An HTML or plain text message body
    
    
    if($mailToAdmin->Send() && $mailToSender->Send())        //Send an Email. Return true on success or false on error
    {
        returnResponse($message = 'Email was successfuly sent');
    }
    else
    {
        throwError($error = 'SMTP Error', $message = 'Email could not be sent.' . $mailToAdmin->ErrorInfo . '\n' . $mailToSender->ErrorInfo);
    }


    ?>