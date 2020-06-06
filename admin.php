<?php
    session_start();

    function logOut()
    {
        session_unset();
        header("Location:logIn.php");
    }

    if (array_key_exists("logOutBtn", $_POST))
    {
        logOut();
    }

    if(isset($_SESSION["admin"]))
    {
        require "connectDb.php";

        // echo $_SESSION["admin"];

        // retrieving email from session variable
        $email = mysqli_real_escape_string($link, $_SESSION["admin"]);

        // executing query and generating query results...
        $query = "SELECT Name, isAdmin FROM Users WHERE Email = '$email'";
        $result = mysqli_query($link, $query);

        $row = mysqli_fetch_array($result);

        // echo $row["Name"]."     ". $row["isAdmin"];

        // checking if user exists...
        if(!mysqli_num_rows($result) || !$row["isAdmin"])
        {
            // Either the user is not an admin or the user does not exists at all.

            // die("The user with this email does not exists");
            echo "not exists / not admin";
        }
        else
        {

            $name = $row["Name"]." (Admin)";
            $success = "";

            $title = "ADMIN (CONTROL PANEL)";
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

<div class="adminControls">
    <form method="post">
        <button type="submit" class="btn btn-primary" name="adminControlBtn" value="addQues">Add Question</button>
        <!-- <button type="submit" class="btn btn-primary" name="adminControlBtn" value="delQues">Delete Question</button> -->
        <button type="submit" class="btn btn-primary" name="adminControlBtn" value="addSub">Add Subject</button>
        <button type="submit" class="btn btn-primary" name="adminControlBtn" value="showRes">Show Results</button>
        <button type="submit" class="btn btn-primary" name="adminControlBtn" value="showQues">Show Questions</button>
        <button type="submit" class="btn btn-lg btn-outline-dark" name="logOutBtn">LogOut</button>
    </form>
</div>


<?php

            if(isset($_POST["adminControlBtn"]))
            {
                if($_POST["adminControlBtn"] == "addQues")
                {
        
?>

<div class="addQuesPanel">
    <form method="post">
        <div class="quesContainer">
            <label for="quesTA">
                Enter Question
            </label>
            <textarea class="form-control" id="quesTA" rows="3" name="ques" required></textarea>
        </div>
        
        <div class="subjectContainer">
            <label for="chooseSub">
                Choose Subject
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
            
?>
                <option value="<?php echo $x[0];?>"> <?php echo $x[1]; ?> </option>
<?php

                        }
        
?>
            </select>
        </div>
        
        <div class="optionsContainer">
            
            <table class="optionsTable">
                <tr>
                    <td>
                        <div class="formElement">
                            <label for="optA">
                                Option A
                            </label>
                            <input type="text" class="form-control" name="optA" id="optA" required>
                        </div>
                    </td>
                    <td>
                        <div class="formElement">
                            <label for="optB">
                                Option B
                            </label>
                            <input type="text" class="form-control" name="optB" id="optB" required>
                        </div>
                    </td>
                    
                </tr>

                <tr>
                    <td>
                        <div class="formElement">
                            <label for="optC">
                                Option C
                            </label>
                            <input type="text" class="form-control" name="optC" id="optC" required>
                        </div>
                    </td>
                    <td>
                        <div class="formElement">
                            <label for="optD">
                                Option D
                            </label>
                            <input type="text" class="form-control" name="optD" id="optD" required>
                        </div>
                    </td>
                </tr>
            </table>

        </div>
        

        <div class="ansContainer">
            <label for="selectAns">
                Select Answer
            </label>

            <select class="form-control" id="selectAns" name="ans">
                <option value="A">Option A</option>
                <option value="B">Option B</option>
                <option value="C">Option C</option>
                <option value="D">Option D</option>
            </select>
            
        </div>
        
        <div class="btnContainer">
            <button type="submit" class="btn btn-primary" name="submitBtn" value="addQues">Add Ques</button>
        </div>
        
    </form>
</div>
        
<?php

                }
                elseif($_POST["adminControlBtn"] == "delQues")
                {
        
?>

<div class="deleteQuesPanel">

    <form method="post">

        <div class="subjectContainer">
            <label for="chooseSub">
                Choose Subject
            </label>
            <select class="form-control" id="chooseSub" name="subID" required>
<?php
                
                $query = "SELECT SubID, SubjectName FROM Subjects";
                $result = mysqli_query($link, $query);
                $noOfSub = mysqli_num_rows($result);
                $row = mysqli_fetch_all($result);

                        foreach($row as $x)
                        {
                            // $x[0] --> SubID
                            // $x[1] --> SubjectName
            
?>
                <option value="<?php echo $x[0]; ?>"><?php echo $x[1]; ?></option>

<?php

                        }
            
?>

            </select>
        </div>

        <div class="btnContainer">
            <button type="submit" class="btn btn-primary" name="submitBtn" value="showQues">Show Questions</button>
        </div>

    </form>

</div>

<?php

                }
                elseif($_POST["adminControlBtn"] == "addSub")
                {
        
?>

<div class="addSubPanel">

    <form method="post">

        <div class="formElement">
            <label for="newSub">
                Enter the name of new subject :-
            </label>
            <input type="text" class="form-control" name="newSub" id="newSub" required>
        </div>

        <div class="btnContainer">
            <button type="submit" class="btn btn-primary" name="submitBtn" value="addSub">Add Subject</button>
        </div>
        
    </form>
</div>

<?php

                }
                elseif($_POST["adminControlBtn"] == "showRes" || isset($_POST["prev"]) || isset($_POST["next"]))
                {
                    if(!isset($_POST["prev"]) && !isset($_POST["next"]))
                    {
                        // First Load
                        $_SESSION["pageNo"] = 0;
                    }

                    if(isset($_POST["prev"]))
                        $_SESSION["pageNo"]--;
                    
                    if(isset($_POST["next"]))
                        $_SESSION["pageNo"]++;

                    $pageNo = mysqli_real_escape_string($link, $_SESSION["pageNo"]);
                    $offset = $pageNo*10;

                    // Checking if next result is available or not.
                    $nextOffset = $pageNo*10 + 10;
                    $nextBtn = false;
                    $prevBtn = false;

                    $query = "SELECT Users.Name, Subjects.SubjectName, Game.TotalQ, Game.AttemptedQ, Game.CorrectAns, Game.Score FROM Users, Game, Subjects WHERE Users.ID = Game.PlayerID AND Game.SubID = Subjects.SubID LIMIT 10 OFFSET $nextOffset;";
                    $result = mysqli_query($link, $query);

                    // Checking whether to show next button or not.
                    if(mysqli_num_rows($result))
                        $nextBtn = true;

                    // Checking whether to show prev button or not.
                    if($_SESSION["pageNo"] > 0)
                        $prevBtn = true;


                    $query = "SELECT Users.Name, Subjects.SubjectName, Game.TotalQ, Game.AttemptedQ, Game.CorrectAns, Game.Score FROM Users, Game, Subjects WHERE Users.ID = Game.PlayerID AND Game.SubID = Subjects.SubID LIMIT 10 OFFSET $offset;";
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

<div class="showResultsPanel">
    <form method="post">
        <table class="table table-striped table-dark">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Subject</th>
                    <th scope="col">Total Questions</th>
                    <th scope="col">Attempted Questions</th>
                    <th scope="col">Correct Ans</th>
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
                    <td><?php echo $row["SubjectName"]; ?></td>
                    <td><?php echo $row["TotalQ"]; ?></td>
                    <td><?php echo $row["AttemptedQ"]; ?></td>
                    <td><?php echo $row["CorrectAns"]; ?></td>
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

?>
                </tr>
            </tbody>
        </table>
    </form>
</div>

<?php

                    }

                }
                elseif($_POST["adminControlBtn"] == "showQues")
                {

?>

<div class="showQuesPanel">

    <form method="post">

        <div class="subjectContainer">
            <label for="chooseSub">
                Choose Subject
            </label>
            <select class="form-control" id="chooseSub" name="subID" required>
<?php
                
                $query = "SELECT SubID, SubjectName FROM Subjects";
                $result = mysqli_query($link, $query);
                $noOfSub = mysqli_num_rows($result);
                $row = mysqli_fetch_all($result);

                        foreach($row as $x)
                        {
                            // $x[0] --> SubID
                            // $x[1] --> SubjectName
            
?>
                <option value="<?php echo $x[0]; ?>"><?php echo $x[1]; ?></option>

<?php

                        }
            
?>

            </select>
        </div>

        <div class="btnContainer">
            <button type="submit" class="btn btn-primary" name="submitBtn" value="showQues">Show Questions</button>
        </div>

    </form>

</div>

<?php

                }
            }
            elseif(isset($_POST["submitBtn"]))
            {
                if($_POST["submitBtn"] == "addQues")
                {
                    $subID = mysqli_real_escape_string($link, $_POST["subID"]);
                    $ques = mysqli_real_escape_string($link, $_POST["ques"]);
                    $optA = mysqli_real_escape_string($link, $_POST["optA"]);
                    $optB = mysqli_real_escape_string($link, $_POST["optB"]);
                    $optC = mysqli_real_escape_string($link, $_POST["optC"]);
                    $optD = mysqli_real_escape_string($link, $_POST["optD"]);
                    $ans = mysqli_real_escape_string($link, $_POST["ans"]);


                    $query = "INSERT INTO Questions (Question, OptionA, OptionB, OptionC, OptionD, Answer, SubID) VALUES ('$ques', '$optA', '$optB', '$optC', '$optD', '$ans', '$subID');";
                    $result1 = mysqli_query($link, $query);

                    if ($result1)
                    {
                        echo "Question Added!";

                        $query = "UPDATE Subjects SET TotalQ = TotalQ + 1 WHERE SubID = '$subID';";
                        $result2 = mysqli_query($link, $query);

                        if ($result2)
                            echo "Subject Table Updated!";
                        else
                        {
                            // Error when query 2 fails.
                            echo "MySQL error:- ".mysqli_error($link);
                        }
                    }
                    else
                    {
                        // Error when Query 1 fails.
                        echo "MySQL error:- ".mysqli_error($link);
                    }

                    
                }
                elseif($_POST["submitBtn"] == "showQues")
                {
                    $subID = mysqli_real_escape_string($link, $_POST["subID"]);

                    $query = "SELECT Question, OptionA, OptionB, OptionC, OptionD, Answer FROM Questions WHERE SubID = '$subID';";
                    $result = mysqli_query($link, $query);

                    if(!$result)
                        echo "MySQL error:- ".mysqli_error($link);
                    else
                    {

?>

<div class="showQuesPanel">
    <table class="table table-striped table-dark">
        <thead>
            <tr>
                <th scope="col">Q#</th>
                <th scope="col">Question</th>
                <th scope="col">Option A</th>
                <th scope="col">Option B</th>
                <th scope="col">Option C</th>
                <th scope="col">Option D</th>
                <th scope="col">Answer</th>
            </tr>
        </thead>
        <tbody>
<?php

                    $numOfRows = mysqli_num_rows($result);
                    for($i=0; $i<$numOfRows; $i++)
                    {
                        $row = mysqli_fetch_array($result);

?>
                <tr>
                <th scope="row"><?php echo $i+1; ?></th>
                <td><?php echo $row["Question"]; ?></td>
                <td><?php echo $row["OptionA"]; ?></td>
                <td><?php echo $row["OptionB"]; ?></td>
                <td><?php echo $row["OptionC"]; ?></td>
                <td><?php echo $row["OptionD"]; ?></td>
                <td><?php echo $row["Answer"]; ?></td>
                </tr>
<?php

                    }

?>

        </tbody>
    </table>
</div>

<?php

                    }
                }
                elseif($_POST["submitBtn"] == "addSub")
                {
                    $newSub = mysqli_real_escape_string($link, $_POST["newSub"]);

                    $query = "INSERT INTO Subjects (SubjectName, TotalQ) VALUES ('$newSub', 0);";
                    $result = mysqli_query($link, $query);

                    if(!$result)
                    {
                        echo "MySQL error:- ".mysqli_error($link);
                    }
                    else
                    {
                        echo "Subject Added Successfuly!";
                    }
                }
                
            }
            else
            {

?>

<div class="firstLoadPanel">
    <h1 class="display-2">
        ADMIN CONTROL PANEL
    </h1>

    <h1 class="display-3">
        YOU ARE THE MASTER!
    </h1>
</div>

<?php

            }
?>

<?php

            include "footer.php";

        }
    }
    else
        header("Location:logIn.php");

?>