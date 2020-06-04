<?php
    session_start();

    $error = ""; // Variable that contains login errors.
    
    if(isset($_SESSION["subID"]))
    {
        // Some user is logged in and the quiz has already started. Redirecting to quiz.php to continue the quiz.
        header("Location:quiz.php");
    }
    elseif(isset($_SESSION["email"]))
    {
        // Someone(user) is already logged in. Redirecting to the welcome page.
        header("Location:welcome.php");
    }
    elseif(isset($_SESSION["admin"]))
    {
        // Someone(admin) is already logged in. Redirecting to the admin page.
        header("Location:admin.php");
    }
    elseif(array_key_exists("email", $_POST) && array_key_exists("password", $_POST))
    {
        // database connection file...
        require "connectDb.php";

        // retrieving login credentials...
        $email = mysqli_real_escape_string($link, $_POST["email"]);
        $pass = mysqli_real_escape_string($link, $_POST["password"]);

        // Checking if user exists.

        // executing query and generating query results...
        $query = "SELECT * FROM Users WHERE Email = '$email'";
        $result = mysqli_query($link, $query);

        if(!mysqli_num_rows($result))
        {
            // query returned an empty table, so user with this Email does not exists.
            $error .= "<p>The user with this email does not exists!</p><p>Check your credentials and try again<p>";
        }
        else
        {
            $row = mysqli_fetch_array($result);

            // checking password...
            if($row["Password"] != $pass)
                $error .= "<p>You entered incorrect password!</p><p>Check your credentials and try again<p>";
            else
            {
                // If there exists a user with same credentials....

                if($row["isAdmin"])
                {
                    // the user is ADMIN
                    // redirecting to admin.php with login credentials.
                    $_SESSION["admin"] = $email;
                    header("Location:admin.php");

                }
                else
                {
                    // regular user
                    // redirecting to welcome.php with login credentials...
                    $_SESSION["email"] = $email;
                    header("Location:welcome.php");
                }
            }
        }

        // removing post variables...
        unset($_POST["name"]);
        unset($_POST["email"]);
        unset($_POST["password"]);
    }
?>


<?php

    $title = "QUIZONAMIA-LOGIN";
    include"header.php";

?>

<div class="container">
    <div class="jumbotron">
        <div role="alert" class="error"><?php echo $error; ?>
        </div>
        <form method="post" class="loginForm">
            <div class="formElement">
                <label for="emailInput">
                    Email address
                </label>
                <input type="email" id="emailInput" name="email" required>
                <small id="emailHelp" class="form-text text-muted">
                    We'll never share your email with anyone else.
                </small>
            </div>
            <div class="formElement">
                <label for="passwordInput">
                    Password
                </label>
                <input type="password" id="passwordInput" name="password" required>
            </div>
            <div class="form-group form-check">
                <input type="checkbox" class="form-check-input" id="rememberMe">
                <label class="form-check-label" for="rememberMe">
                    Remember Me (functionality not added yet xD)
                </label>
            </div>
            <div class="loginBtn">
                <button class="btn btn-lg btn-outline-dark" type="submit">
                    LogIn
                </button>
            </div>
        </form>
    </div>
</div>

<?php

    include "footer.php";

?>
