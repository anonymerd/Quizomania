<?php

    session_start();

    require "connectDb.php";

    $title = "QUIZOMANIA - LEADERBOARD";
    include "header.php";

    if(!isset($_POST["submitBtn"]) && !isset($_POST["prev"]) && !isset($_POST["next"]))
    {
        // First Load
        $_SESSION["lbsubID"] = 1;
        $_SESSION["pageNo"] = 0;
    }

    if(isset($_POST["submitBtn"]))
    {
        // Submit Btn clicked.
        // New subject choosen.
        $_SESSION["lbsubID"] = $_POST["subID"];
    }

    if(isset($_POST["prev"]))
    {
        // Prev Btn clicked.
        $_SESSION["pageNo"]--;
    }
    
    if(isset($_POST["next"]))
    {
        // Next Btn clicked.
        $_SESSION["pageNo"]++;
    }

?>


<nav class="navbar">
    <a class="navbar-brand" href="index.php">
        <img src="logo/indexLogo.png" id="logoIcon">
    </a>

<?php

    if(isset($_SESSION["email"]))
    {
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

?>
    <a href="index.php">
        <div class="form">
            <span class="userIcon">
                <i class="far fa-user"></i>
            </span>
            <?php echo $name;?> 
        </div>
    </a>

<?php
        }
    }
    elseif(isset($_SESSION["admin"]))
    {
        // retrieving email from session variable
        $email = mysqli_real_escape_string($link, $_SESSION["admin"]);

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

?>
    <a href="index.php">
        <div class="form">
            <span class="userIcon">
                <i class="far fa-user"></i>
            </span>
            <?php echo $name."(admin)";?> 
        </div>
    </a>

<?php
        }

    }
    else
    {

?>
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

<?php

    }

?>
</nav>

<div class="leaderboardPanel">
    
    <form method="post">
        <div class="formElement">
            <label for="chooseSub">
                Select Subject
            </label>
            <select class="form-control" id="chooseSub" name="subID" required>
<?php
                    
    $query = "SELECT SubID, SubjectName FROM Subjects";
    $result = mysqli_query($link, $query);
    $noOfSub = mysqli_num_rows($result);
    $row = mysqli_fetch_all($result);

    foreach($row as $x)
    {
        // $x[0] ---> SubID
        // $x[1] ---> SubjectName

        if($x[0] == $_SESSION["lbsubID"])
        {

?>
                <option value="<?php echo $x[0];?>" selected> <?php echo $x[1]; ?> </option>
<?php
        }
        else
        {              
?>
                <option value="<?php echo $x[0];?>"> <?php echo $x[1]; ?> </option>
<?php
        }

    }
            
?>
            </select>

            <div class="btnContainer">
                <button type="submit" class="btn btn-primary" name="submitBtn" value="selectSub">Select</button>
            </div>

        </div>

<?php

    $subID = mysqli_real_escape_string($link, $_SESSION["lbsubID"]);
    $pageNo = mysqli_real_escape_string($link, $_SESSION["pageNo"]);
    $offset = $pageNo*10;

    // Checking if next result is available or not.
    $nextOffset = $pageNo*10 + 10;
    $nextBtn = false;
    $prevBtn = false;

    $query = "SELECT Users.Name, Game.Score FROM Users, Game WHERE Users.ID = Game.PlayerID AND Game.SubID = $subID ORDER BY Game.Score DESC LIMIT 10 OFFSET $nextOffset;";
    $result = mysqli_query($link, $query);

    // Checking whether to show next button or not.
    if(mysqli_num_rows($result))
        $nextBtn = true;

    // Checking whether to show prev button or not.
    if($_SESSION["pageNo"] > 0)
        $prevBtn = true;


    $query = "SELECT Users.Name, Game.Score FROM Users, Game WHERE Users.ID = Game.PlayerID AND Game.SubID = $subID ORDER BY Game.Score DESC LIMIT 10 OFFSET $offset;";
    $result = mysqli_query($link, $query);

    if(!$result)
    {
        echo "MySQL error:- ".mysqli_error($link);
    }
    elseif(!mysqli_num_rows($result))
    {
        echo "No results found";
    }
    else
    {

?>

        <div class="leaderboardTable">
            <table class="table table-striped table-dark">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Name</th>
                        <th scope="col">Score</th>
                    </tr>
                </thead>
                <tbody>

<?php

        $numOfRows = mysqli_num_rows($result);
        for($i=$offset; $i<$numOfRows + $offset; $i++)
        {
            $row = mysqli_fetch_array($result);

?>
        
                    <tr>
                        <th scope="row"><?php echo $i+1; ?></th>
                        <td><?php echo $row["Name"]; ?></td>
                        <td><?php echo $row["Score"]; ?></td>
                    </tr>
<?php

        }

?>

                </tbody>
            </table>

            <input type="hidden" name="adminControlBtn">

            <table class="btnTable">
                <tbody>
                    <tr>
                        
<?php

        if($prevBtn)
        {

?>
                        <td>
                            <div class="btnContainer">
                                <button type="submit" class="btn btn-primary" name="prev">Prev</button>
                            </div>
                        </td>

<?php
        }
        
        if($nextBtn)
        {
?>
                        <td>
                            <div class="btnContainer">
                                <button type="submit" class="btn btn-primary" name="next">Next</button>
                            </div>
                        </td>

<?php
        }

    }

?>
                    </tr>
                </tbody>
            </table>
        </div>
    </form>

</div>

<?php
    include "footer.php";

?>