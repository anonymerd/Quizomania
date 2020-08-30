<?php

    // This module validates and verifies the data sent/received to/from the user.

    require_once __DIR__ . '/../../config/Database.php';
    require_once __DIR__ . '/../../models/User.php';

    require __DIR__ . '/../../auth/vendor/autoload.php';
    
    use \Firebase\JWT\JWT;

    // Defining Data types

    define("STRING", 0);
    define("INT", 1);
    define("FLOAT", 2);
    define("BOOLEAN", 3);
    define("NUMERIC", 4);

    // Function to validate the keys of the data received from the client side.
    function validateKeys($data, $chkKeys)
    {
        $keys = array_keys($data);

        foreach ($chkKeys as $key)
        {
            if(!in_array($key, $keys))
                throwError($error = "$key not available", $message = "Add $key key in the raw body");
        }
    }


    // Function to validate the data/parameters recieved from the client side.
    function validateData($filedName, $data, $type, $required = true)
    {
        // If required is set true then the value of the field is required.
        if($required && (!isset($data) || $data === ''))
            throwError($error = "$filedName not found.", $message =  "This parameter is required.");

        // Determining whether the type of received data is valid.
        switch($type)
        {
            case "STRING" :
                if(!is_string($data))
                    throwError($error = "Invalid data type for $filedName.", $message = "It only accepts string.");
                    break;
            
            case "INT" :
                if(!is_int($data))
                    throwError($error = "Invalid data type for $filedName.", $message = "It only accepts int.");
                    break;

            case "FLOAT" :
                if(!is_float($data))
                    throwError($error = "Invalid data type for $filedName.", $message = "It only accepts float.");
                    break;

            case "BOOLEAN" :
                if(!is_bool($data))
                    throwError($error = "Invalid data type for $filedName.", $message = "It only accepts bool.");
                    break;
                    
            case "NUMERIC" :
                if(!is_numeric($data))
                    throwError($error = "Invalid data type for $filedName.", $message = "It only accepts numeric.");
                    break;

            case "ARRAY" :
                if(!is_array($data))
                    throwError($error = "Invalid data type for $filedName.", $message = "It only accepts array.");
                    break;

            default :
                throwError($error = "Invalid data type for $filedName.");
                break;
        }

    }

    // Function to check whether the query was successful.
    function chkQuery($conn, $result)
    {
        // * This function will only return when the query is successful.

        if(!$result)
        {
            // The query was unsuccessfull.
            throwError($error = "Database error ---- $conn->error", $message = 'There was some problem with the database.');
        }
    }

    // Function that returns the response on generally on successful attempt.
    function returnResponse($message, $data = NULL, $token = NULL, $result = true)
    {
        header("CONTENT-TYPE: application/json");

        $output = array(
            'result' => $result,
            'message' => $message,
        );

        if($data !== NULL)
            $output['data'] = $data;
        
        if($token !== NULL)
            $output['token'] = $token;

        echo json_encode($output);
        exit();
    }

    // Function to throw error and exit the script.
    function throwError($error = NULL, $message = NULL, $result = false)
    {
        header("CONTENT-TYPE: application/json");
        $errorMsg = array('result' => $result);

        if($error !== NULL)
            $errorMsg['error'] = $error;

        if($message !== NULL)
            $errorMsg['message'] = $message;

        echo json_encode($errorMsg);
        exit();
    }

    // Function for generating token.
    function generateToken($payload)
    {
        $payload['iat'] = time();
        $payload['iss'] = 'Anonymerd';
        $payload['sub'] = 'Login Authentication';

        $key = 'anonymerd';

        return JWT::encode($payload, $key);
    }

    // Get header authorization.
    function getAuthorizationHeader()
    {
            $headers = null;
            
            if (isset($_SERVER['Authorization'])) 
            {
	            $headers = trim($_SERVER["Authorization"]);
	        }
            else if (isset($_SERVER['HTTP_AUTHORIZATION'])) 
            { 
                //Nginx or fast CGI
	            $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
            }
            else if (function_exists('apache_request_headers')) 
            {
                $requestHeaders = apache_request_headers();
                
	            // Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization)
                $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
                
                if (isset($requestHeaders['Authorization'])) 
                {
	                $headers = trim($requestHeaders['Authorization']);
	            }
	        }
	        return $headers;
        }
        

        // Getting access token from header.
        function getBearerToken() 
        {
            $headers = getAuthorizationHeader();
            
	        // HEADER: Get the access token from the header
	        if (!empty($headers)) {
	            if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
	                return $matches[1];
	            }
	        }
	        throwError($error = 'Access Token Not found', $message = 'Access token is required for authorization.');
        }

        // Extracting token info
        function getTokenPayload($token = NULL)
        {
            try
            {
                if($token === NULL)
                    $token = getBearerToken();

                /**
                 * JWT::decode() throws the following exceptions.
                * @throws UnexpectedValueException     Provided JWT was invalid
                * @throws SignatureInvalidException    Provided JWT was invalid because the signature verification failed
                * @throws BeforeValidException         Provided JWT is trying to be used before it's eligible as defined by 'nbf'
                * @throws BeforeValidException         Provided JWT is trying to be used before it's been created as defined by 'iat'
                * @throws ExpiredException             Provided JWT has since expired, as defined by the 'exp' claim
                */
                
                $payload = JWT::decode($token, "anonymerd", ['HS256']);
                return $payload;
                
                // print_r($payload);
            }
            catch(Exception $e)
            {
                throwError($e->getMessage(), $message = 'Token error');
            } 

        }
        
        // Validating the bearer token.
        function validateAccessToken($token = NULL)
        {
            $payload = getTokenPayload($token);
            
            // When token is decoded successfully.

            // Creating a new database connection.
            $database = new Database();
            $dbConn = $database->connect();

            // Creating a new user object.
            $user = new User($dbConn);

            $user->id = $payload->userID;

            // Getting the details of the user to which the token is issued.
            $result = $user->getSingleUser();

            // Checking the query results.
            if($result)
            {
                // User found.
                    return $user;
            }
            else
            {
                // The user could not be found.
                    throwError($error = 'Unauthorised Access', $message = 'Access Denied');
            }

        }

        // Function to check the forget password token.
        function validateForgetPasswordToken($token)
        {
            try
            {

                /**
                 * JWT::decode() throws the following exceptions.
                * @throws UnexpectedValueException     Provided JWT was invalid
                * @throws SignatureInvalidException    Provided JWT was invalid because the signature verification failed
                * @throws BeforeValidException         Provided JWT is trying to be used before it's eligible as defined by 'nbf'
                * @throws BeforeValidException         Provided JWT is trying to be used before it's been created as defined by 'iat'
                * @throws ExpiredException             Provided JWT has since expired, as defined by the 'exp' claim
                */
                
                $payload = JWT::decode($token, "anonymerd", ['HS256']);
                return array(
                    'result' => true,
                    'data' => $payload
                );
                
                // print_r($payload);

            }
            catch(Exception $e)
            {
                return array(
                    'result' => false,
                    'message' => $e->getMessage()
                );
            } 

        }
        
?>