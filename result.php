<?php
    session_start();

    function logOut()
    {
        session_unset();
        header("Location:logIn.php");
    }

    // print_r($_SESSION);

    if (array_key_exists("logOutBtn", $_POST))
    {
        // The user wants to log out.
        logOut();
    }
    elseif(isset($_POST["playAgainBtn"]))
    {
        header("Location:welcome.php");
    }
    elseif(!isset($_SESSION["email"]))
    {
        // No user is logged in.
        header("Location:logIn.php");
    }
    elseif(!isset($_SESSION["subID"]))
    {
        // Subject not selected yet.
        header("Location:welcome.php");
    }
    elseif(isset($_SESSION["isCorrect"]) && isset($_SESSION["answers"]) && isset($_SESSION["response"]) && isset($_SESSION["randomQNo"]))
    {

        // database connection file...
        require "connectDb.php";

        // retrieving email from session variable
        $email = mysqli_real_escape_string($link, $_SESSION["email"]);

        // executing query and generating query results...
        $query = "SELECT Name FROM Users WHERE Email = '$email'";
        $result = mysqli_query($link, $query);

        // checking if user exists...
        if(mysqli_num_rows($result))
            die("The user with this email does not exists");
        else
        {
            $row = mysqli_fetch_array($result);
            $name = $row["Name"];

            // Calculating numbers of total questions.
            $totalQues = count($_SESSION["answers"]);


            // Calculating number of attempted questions.
            $attemptedQues = 0;
            foreach($_SESSION["response"] as $x)
                if($x != "Not Attempted")
                    $attemptedQues++;

            // Calculating number of correct answers.
            $noOfCorrectAns = 0;
            foreach($_SESSION["isCorrect"] as $x)
                if($x == 1)
                    $noOfCorrectAns++;

?>


<?php

            $title = "QUIZOMANIA-RESULT";
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

<div class="resultPanel">
    <table class="table table-dark resultTable">
        <thead>
            <tr>
                <th scope="col">Name</th>
                <th scope="col">Total Ques</th>
                <th scope="col">Attempted Ques</th>
                <th scope="col">Correct Ans</th>
                <th scope="col">Score</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?php echo $name; ?></td>
                <td><?php echo $totalQues; ?></td>
                <td><?php echo $attemptedQues ?></td>
                <td><?php echo $noOfCorrectAns; ?></td>
                <td><?php echo $noOfCorrectAns * 10; ?></td>
            </tr>
        </tbody>
        </table>
        <!-- php temporary variable checking -->
<?php
            // print_r($_SESSION["isCorrect"]);
            // print_r($_SESSION["answers"]);
            // print_r($_SESSION["response"]); 
?>

        <table class="table table-striped table-dark">
        <thead>
            <tr>
                <th scope="col">Q#</th>
                <th scope="col">Correct Ans</th>
                <th scope="col">Your Response</th>
                <th scope="col">Result</th>
            </tr>
        </thead>
        <tbody>
<?php 
            for($i=0; $i<$totalQues; $i++)
            {
?>
                <tr>
                <th scope="row"><?php echo $i+1; ?></th>
                <td><?php echo $_SESSION["answers"][$i]; ?></td>
                <td><?php echo $_SESSION["response"][$i]; ?></td>
                <td><?php echo $_SESSION["isCorrect"][$i] * 10; ?></td>
                </tr>
<?php
            }
?>
        </tbody>
    </table>
</div>

<div class="btnPanel">
    <form method="post">

        <table class="btnTable">
            <tr>
                <td><button class="btn btn-lg btn-outline-dark" type="submit" name="playAgainBtn">Play Again</button></td>
                <td><button class="btn btn-lg btn-outline-dark" type="submit" name="logOutBtn">LogOut</button></td>
            </tr>
        </table>
    </form>
</div>

<?php

            unset($_SESSION);
            $_SESSION["email"] = $email;

            include "footer.php";

        }
    }
    else
        die("There was a problem in submitting the quiz. Please try again.")

?>