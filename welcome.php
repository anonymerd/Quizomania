<?php

    session_start();

    // print_r($_POST);
    // print_r($_SESSION);

    function logOut()
    {
        session_unset();
        header("Location:logIn.php");
    }

    function startQuiz()
    {
        $_SESSION["subID"] = $_POST["subID"];
        header("Location:quiz.php");
    }

    // analysing how to react when the page loads.

    if (isset($_SESSION["subID"]))
    {
        // The quiz has already started.
        header("Location:quiz.php");
    }
    if (array_key_exists("logOutBtn", $_POST))
    {
        // The user wants to log out.
        logOut();
    }
    elseif (array_key_exists("subID", $_POST))
    {
        // The user has selected a subject so starting a quiz session.
        startQuiz();
    }
    elseif (array_key_exists("email", $_SESSION)) 
    {
        // This block will be executed on first load.

        // database connection file...
        require "connectDb.php";

        // retrieving email from session variable
        $email = mysqli_real_escape_string($link, $_SESSION["email"]);

        // executing query and generating query results...
        $query = "SELECT Name FROM Users WHERE Email = '$email'";
        $result = mysqli_query($link, $query);

        // checking if user exists...
        if(!mysqli_num_rows($result))
            die("The user with this email does not exists");
        else
        {
            // The user exists.
            $row = mysqli_fetch_array($result);
            $name = $row["Name"];
            // echo "Hi ".$row["Name"];
        }
    }
    else
    {
        // On illegal access to the page, redirecting back to the login page.
        header("Location:logIn.php");
    }

?>


<?php

    $title = "QUIZONAMIA-WELCOME";
    include "header.php";

?>

<nav class="navbar">
    <a class="navbar-brand" href="index.php">
        <img src="logo/indexLogo.png" id="logoIcon">
    </a>
    <a href="index.php">
        <div class="form">
            <span class="userIcon">
                <i class="far fa-user"></i>
            </span>
            <?php echo $name;?> 
        </div>
    </a>
</nav>

<div class="subjectFormContainer">

    <form method="post" id="subjectForm">

        <input type="hidden" id="hiddenSub" name="subID">
        
        <div class="subjectPanel">
            <a type="submit" role="button" name="sub" onClick="loadSub(1);" >
                <div class="card text-white bg-primary mb-3" style="max-width: 18rem;">
                    <div class="card-header">
                        HISTORY
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">Primary card title</h5>
                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                    </div>
                </div>
            </a>

            <a type="submit" role="button" name="sub" onClick="loadSub(2);" >
                <div class="card text-white bg-secondary mb-3" style="max-width: 18rem;">
                    <div class="card-header">
                        GEOGRAPHY
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">Secondary card title</h5>
                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                    </div>
                </div>
            </a>

            <a type="submit" role="button" name="sub" onClick="loadSub(3);" >
                <div class="card text-white bg-success mb-3" style="max-width: 18rem;">
                    <div class="card-header">
                        POLITICAL SCIENCE
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">Success card title</h5>
                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                    </div>
                </div>
            </a>

            <a type="submit" role="button" name="sub" onClick="loadSub(4);" >
                <div class="card text-white bg-danger mb-3" style="max-width: 18rem;">
                    <div class="card-header">
                        ECONOMICS
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">Danger card title</h5>
                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                    </div>
                </div>
            </a>

            <a type="submit" role="button" name="sub" onClick="loadSub(5);" >
                <div class="card text-white bg-warning mb-3" style="max-width: 18rem;">
                    <div class="card-header">
                        PHYSICS
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">Warning card title</h5>
                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                    </div>
                </div>
            </a>

            <a type="submit" role="button" name="sub" onClick="loadSub(6);" >
                <div class="card text-white bg-info mb-3" style="max-width: 18rem;">
                    <div class="card-header">
                        CHEMISTRY
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">Info card title</h5>
                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                    </div>
                </div>
            </a>

            <a type="submit" role="button" name="sub" onClick="loadSub(7);" >
                <div class="card bg-light mb-3" style="max-width: 18rem;">
                    <div class="card-header">
                        MATHS
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">Light card title</h5>
                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                    </div>
                </div>
            </a>

            <a type="submit" role="button" name="sub" onClick="loadSub(8);" >
                <div class="card text-white bg-dark mb-3" style="max-width: 18rem;">
                    <div class="card-header">
                        BIOLOGY
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">Dark card title</h5>
                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="logOutBtn">
            <button class="btn btn-lg btn-outline-dark" type="submit" name="logOutBtn">
                LogOut
            </button>
        </div>
    </form>
</div>

<script>
    function loadSub(subID)
    {
        console.log(subID);
        $("#hiddenSub").attr("value", subID);
        $("#subjectForm").submit();
    }
</script>

<?php

    include "footer.php";

?>