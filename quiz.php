<?php

    session_start();
    // $name = "Anonymerd";
    

    // initialising required variables
    $ques = "No Ques Available";
    $optA = "This is opt A";
    $optB = "This is opt B";
    $optC = "This is opt C";
    $optD = "This is opt D";
    $totalQues = 5;
    $error = "";

    function generateRandQues($link, $totalQues)
    {
        global $error;

        //Generating Questions randomly.

        $subID = mysqli_real_escape_string($link, $_SESSION["subID"]);
    
        $query = "SELECT Answer from Questions WHERE SubID = '$subID'";
        $result = mysqli_query($link, $query);

        if(mysqli_num_rows($result))
        {
            $_SESSION["randomQNo"] = range(1, mysqli_num_rows($result));
            shuffle($_SESSION["randomQNo"]);
            $_SESSION["randomQNo"] = array_slice($_SESSION["randomQNo"], 0, $totalQues);
            
            $_SESSION["answers"] = array_fill(0, $totalQues, 'e'); // map for storing correct answers for the generated questions.

            $row = mysqli_fetch_all($result);
            for($i=0; $i<$totalQues; $i++)
            {
                // $row[$x][0] --> Answer to the $x th question.
                $currQues = $row[$_SESSION["randomQNo"][$i]-1];
                $_SESSION["answers"][$i] = $currQues[0];
            }
        }
        else
        {
            $error = "Subject Not Available!";
        } 
    }

    function getQues($link)
    {
        global $error;

        // This function generates new question xD

        // variables declared global so that there updated values can be used throughout the code.
        global $ques, $optA, $optB, $optC, $optD;

        $subID = mysqli_real_escape_string($link, $_SESSION["subID"]);

        $query = "SELECT Question, OptionA, OptionB, OptionC, OptionD from Questions WHERE SubID = '$subID'";
        $result = mysqli_query($link, $query);

        if(mysqli_num_rows($result))
        {
            $row = mysqli_fetch_all($result);
            $currQues = $row[$_SESSION["randomQNo"][$_SESSION["currQNo"]-1]-1];

            // currQues[0] --> Question
            // currQues[1] --> OptionA
            // currQues[2] --> OptionB
            // currQues[3] --> OptionC
            // currQues[4] --> OptionD

            $ques = "Q".$_SESSION["currQNo"].":- ".$currQues[0];
            $optA = $currQues[1];
            $optB = $currQues[2];
            $optC = $currQues[3];
            $optD = $currQues[4];
        }
        else
        {
            $ques = "No Ques Available";
            $error = "No Ques Available";
        }
    }

    function chkAns()
    {
        global $error;

        if(array_key_exists("answer", $_POST))
        {
            $_SESSION["response"][$_SESSION["currQNo"]-1] = $_POST["answer"];
            if($_SESSION["answers"][$_SESSION["currQNo"]-1] == $_SESSION["response"][$_SESSION["currQNo"]-1])
                $_SESSION["isCorrect"][$_SESSION["currQNo"]-1] = 1;
            else
                $_SESSION["isCorrect"][$_SESSION["currQNo"]-1] = 0;
        }
        else
        {
            $error = "answer not available in POST";
        }
    }


    

    if (!array_key_exists("email", $_SESSION)) 
    {
        // This means that no user is logged in yet.
        header("Location:logIn.php");
    }
    else if(!array_key_exists("subID", $_SESSION))
    {
        // This means that user hasn't chosen a subject yet.
        header("Location:welcome.php");
    }
    else
    {
        // All legal login...

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
            // Fetching user's name.
            $row = mysqli_fetch_array($result);
            $name = $row["Name"];

            if(array_key_exists("nxtBtn", $_POST))
            {
                if($_SESSION["currQNo"] >= 1  && $_SESSION["currQNo"] < $totalQues)
                {
                    chkAns();
                    $_SESSION["currQNo"]++;
                }
                getQues($link);
            }
            else if(array_key_exists("prevBtn", $_POST))
            {
                if($_SESSION["currQNo"] > 1 && $_SESSION["currQNo"] <= $totalQues)
                {
                    chkAns();
                    $_SESSION["currQNo"]--;
                }
                getQues($link);
            }
            else if(array_key_exists("submit", $_POST))
            {
                chkAns();
                header("Location:result.php");
            }
            else // on first page load.
            {
                $_SESSION["currQNo"] = 1;

                $_SESSION["isCorrect"] = array_fill(0, $totalQues, 0); // map for storing whether the ques was correctly answered or not (1/0).
                // $_SESSION["attemptedQues"] = 0;
                
                $_SESSION["response"] = array_fill(0, $totalQues, 'Not Attempted'); // map for storing the answers(options) entered by the user.
                
                generateRandQues($link, $totalQues);
                
                getQues($link);
            }

?>


<?php

            $title = "WELCOME TO THE GAME!";
            include "header.php";

            // print_r($_SESSION["randomQNo"]);
            // print_r($_SESSION["answers"]);

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

<div class="quesPanel">
    <form method="post">
        <div class="quesElement">
            <textarea class="form-control" rows="4" readonly><?php
                echo $ques;
            ?></textarea>
        </div>
        <div class="optionsPanel">
            <table class="optionsTable">
                <tr>
                    <td scope="col">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="answer" id="optA" value="A">
                            <label class="form-check-label" for="a"><?php
                                    echo "A: $optA";
                                ?></label>
                        </div>
                    </td>
                    <td scope="col">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="answer" id="optB" value="B">
                            <label class="form-check-label" for="optB"><?php
                                    echo "B: $optB";
                            ?></label>
                        </div>
                    </td>
                    
                </tr>

                <tr>
                    <td scope="col">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="answer" id="optC" value="C">
                            <label class="form-check-label" for="optC"><?php
                                    echo "C: $optC";
                            ?></label>
                        </div>
                    </td>
                    <td scope="col">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="answer" id="optD" value="D">
                            <label class="form-check-label" for="optD"><?php
                                    echo "D: $optD";
                            ?></label>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
        <div class="btnPanel">
            <table class="btnTable">
                <tr>
                    <td> <button type="submit" class="btn btn-primary btn-lg btn-block" name="prevBtn" id="prevBtn"> Prev Ques </button> </td>
                    <td> <button type="submit" class="btn btn-primary btn-lg btn-block" name="submit" id="submit"> Get Result </button> </td>
                    <td> <button type="submit" class="btn btn-primary btn-lg btn-block" name="nxtBtn" id="nxtBtn"> Next Ques </button> </td>
                </tr>
            </table>
        </div>
    </form>  
</div>

<script>
    var chk = <?php echo json_encode($_SESSION["response"][$_SESSION["currQNo"]-1]); ?>;
    var currQNo = <?php echo json_encode($_SESSION["currQNo"]); ?>;
    var totalQues = <?php echo json_encode($totalQues); ?>;

    if (currQNo > 1)
        document.getElementById("prevBtn").style.visibility = "visible";
        // $("#prevBtn").css("visibility", "visible");

    if (currQNo < totalQues)
        document.getElementById("nxtBtn").style.visibility = "visible";
        // $("#nxtBtn").css("visibility", "visible");


    console.log(currQNo);
    document.getElementById("opt" + chk.toUpperCase()).setAttribute("checked", "checked");
    // $("#opt" + chk.toUpperCase()).attr("checked", "checked");

    
</script>

<?php

            include "footer.php";

        }
    }

?>