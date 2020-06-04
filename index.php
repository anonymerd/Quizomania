<?php

    $title = "QUIZONAMIA-HOME";
    include "header.php";

?>


<nav class="navbar">
    <a class="navbar-brand" href="index.php">
        <img src="logo/indexLogo.png" id="logoIcon">
    </a>
    <div class="form">
        <form class="form-inline">
            <a class="btn btn-lg btn-outline-dark" type="submit" href="logIn.php" role="button">
                LogIn
            </a>
            <a class="btn btn-lg btn-outline-dark" type="submit" href="signUp.php" role="button">
                SignUp
            </a>
        </form>
    </div>
</nav>

<div class="mainPanel">
    <h1 class="display-3">
        WELCOME TO QUIZOMANIA!
    </h1>

    <p class="lead">
        A trivial quiz game that you can play to test your knowledge. It can help you to hone your quizzing skills in multiple domains.
    </p>

    <hr class="my-3">

    <p>
        Some more attractive text....
    </p>

    <a class="btn btn-lg btn-outline-dark" href="logIn" role="button">
        Take a Quiz
    </a>

</div>


<?php

    include "footer.php";

?>
