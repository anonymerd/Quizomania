<?php

    $error = ""; // var containing signup errors.

    if(array_key_exists("name", $_POST) && array_key_exists("email", $_POST) && array_key_exists("password", $_POST))
    {

        // database connection file...
        require "connectDb.php";

        // retrieving login credentials...
        $name = mysqli_real_escape_string($link, $_POST["name"]);
        $email = mysqli_real_escape_string($link, $_POST["email"]);
        $pass = mysqli_real_escape_string($link, $_POST["password"]);

        // checking if a user already exists...
        $query = "SELECT ID FROM Users WHERE Email = '$email'";
        $result = mysqli_query($link, $query);

        if(mysqli_num_rows($result))
        {
            // User with this Email already exists.
            $error .= "<p>This email is already taken!<br>Choose another one</p>";
        }
        else
        {
            // executing INSERT query and generating query results...
            $query = "INSERT INTO Users (Name, Email, Password) values ('$name' , '$email' , '$pass')";
            if(mysqli_query($link, $query))
            {
                // redirecting to logIn.php when user creation is successfull...
                header("Location:logIn.php");
            }
            else
            {
                // Error try again.
                $error = "DATABASE ERROR";
            }
        }

        // removing post variables...
        unset($_POST["name"]);
        unset($_POST["email"]);
        unset($_POST["password"]);
    }

 ?>


<?php

    $title = "QUIZONAMIA-SIGNUP";
    include "header.php";

?>

<nav class="navbar">
    <a class="navbar-brand" href="index.php">
        <img src="logo/indexLogo.png" id="logoIcon">
    </a>
    <div class="form">
        <form class="form-inline">
            <a class="btn btn-lg btn-outline-dark" type="submit" href="leaderboard.php" role="button">
                Leaderboard
            </a>
        </form>
    </div>
</nav>

<div class="container">
    <div class="jumbotron">
        <div role="alert" class="error"><?php echo $error; ?>
        </div>
        <form method="post">
            <div class="formElement">
                <label for="nameInput">
                    Name
                </label>
                <input type="text" id="nameInput" name="name" required>

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
            <div class="signupBtn">
                <button type="submit" class="btn btn-lg btn-outline-dark">
                    Create new Account!
                </button>
            </div>
            
        </form>
    </div>
</div>

<?php

    include "footer.php";

?>
