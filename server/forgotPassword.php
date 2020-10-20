<?php

    session_start();
?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>QUIZOMANIA - RESET PASSWORD</title>

        <style>

        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-color: #3742fa;
            font-family: 'Poppins', sans-serif;
            font-size: 130%;
        }

        .container {
            position: absolute;
            width: 30%;
            top: 50%;
            left: 50%;
            transform: translate(-50%,-50%);
            background-color: #dfe4ea;
            text-align: center;
            padding: 20px;
            border-radius: 10px; 
        }
        .form-container {
            padding:20px;
            margin: 20px auto;
        }

        label {
            display: block;
        }
        input[type=password] {
            display: block;
            padding: 5px;
            margin: 10px auto;
            border: none;
            border-bottom: 1px solid #000;
            background: none;
            width: 50%;
            font-family: 'Poppins', sans-serif;
        }
        .change-password {
            display: inline-block;
            background-color: #130f40;
            padding: 10px;
            border: none;
            border-radius: 25px;
            color: #fff;
            font-family: 'Poppins', sans-serif;
            margin-top: 20px;
            transition: transform 0.2s;
        }
        .change-password:focus {
            outline: none;
        }
        .change-password:hover {
            transform: scale(1.1);
        }
        </style>

    </head>
    <body>
        <div class="container">
        

<?php

    require_once './auth/Validation/validate.php';
    
    if(isset($_POST['change-password']) && isset($_SESSION['token']))
    {
        // Check token, Change the password and acknowledge.

        $response = validateForgetPasswordToken($_SESSION['token']);

        if($response['result'] === true)
        {
            // Valid Token

            require_once __DIR__ . '/models/User.php';
            require_once __DIR__ . '/config/Database.php';

            $payload = $response['data'];

            // Creating the database connection.
            $database = new Database();
            $dbConn = $database->connect();

            // Creating the user object.
            $user = new User($dbConn);
            $user->id = $payload->id;

            // Updating the password.
            
            $newPassword = $dbConn->real_escape_string($_POST['password']);

            if($user->updateUser('Password', $newPassword))
            {
                // Password Changed successfully.
                $message = 'Password Changed Successfuly!';
            }
            else
            {
                // Database error.
                $message = 'Password Could not be Changed. There was a database error. Please try after sometime.';
            }


        }
        else
        {
            // Invalid Token
            $message = 'There was an error. The password could not be changed. Please try again.';
        }


        session_destroy();

?>    

        <div class="message">
            <?php echo $message; ?>
        </div>


<?php

    }
    else if(isset($_GET['token']))
    {
        // Verify the token and show the change password form

        $response = validateForgetPasswordToken($_GET['token']);
        
        if($response['result'] === true)
        {
            // Valid Token
            
            $_SESSION['token'] = $_GET['token'];
?>

            <div class="heading">
                <h3>
                    RESET YOUR PASSWORD
                </h3>
            </div>

            <div class="form-container">
                <form action="forgotPassword.php" method="post">
                    <div class="field">
                        <label for="password">New Password</label>
                        <i class="fa fa-at"></i><input type="password" name="password"
                            id="password" placeholder="Type new password" required>
                    </div>

                    <div class="field">
                        <label for="confirm-password">Confirm Password</label>
                        <i class="fa fa-at"></i><input type="password" name="confirm-password"
                            id="confirm-password" placeholder="Confirm new password" required>
                    </div>

                    <div class="btn-container">
                        <input type="submit" name="change-password" value="Change Password" class="btn change-password">
                    </div>
                </form>
            </div>

<?php

        }
        else
        {
            // Invalid Token
?>            
            <div class="message">
                <?php echo $response['message']; ?>
            </div>
<?php
        }
    }
    else
    {
        // Invalid Access to this page. Show error message.
?>
        <div class="message">
            Invalid Access.
        </div>

<?php
        

    }
?>

    </div> <!-- End of Container div. -->