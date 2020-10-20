<?php

    class Question
    {
        // Database Stuff
        private $dbConn;
        private $tableName = "questions";

        // Question Properties
        public $id = NULL;
        public $question = NULL;
        public $optionA = NULL;
        public $optionB = NULL;
        public $optionC = NULL;
        public $optionD = NULL;
        public $answer = NULL;
        public $subID = NULL;

        // Constructor that creates DB Connection
        public function __construct($link)
        {
            $this->dbConn = $link;
        }

        // Get All Questions
        public function getQuestions($showAns = false)
        {
            /* This function retrieves all Questions from Database and return a mysqli_result object containing all the results. 
            If a subject ID is provided then all the questions of that subject are returned. */


            if($showAns)
                $query = "SELECT * FROM $this->tableName";
            else
                $query = "SELECT QID, Question, OptionA, OptionB, OptionC, OptionD, SubID FROM $this->tableName";


            if($this->subID)
                $query .= " WHERE SubID = $this->subID;";

            $result = $this->dbConn->query($query);

            chkQuery($this->dbConn, $result);

            // When query is successful.

            if($result->num_rows > 0)
            {
                // Question(s) Found.
                return $result;
            }
            else
            {
                // Question(s) could not be found.
                return false;
            }

        }

        // Get a single Question
        public function getSingleQuestion($showAns = false)
        {
            /* This function retrieves a single Question from the Database corresponding to the id recieved as parameter and returns a mysqli_result object containing the Question. */


            if($showAns)
                $query = "SELECT * FROM $this->tableName WHERE QID = $this->id LIMIT 0, 1;";
            else
                $query = "SELECT QID, Question, OptionA, OptionB, OptionC, OptionD, SubID FROM $this->tableName WHERE QID = $this->id LIMIT 0, 1;";

            $result = $this->dbConn->query($query);

            chkQuery($this->dbConn, $result);

            // When query is successful.

            if($result->num_rows > 0)
            {
                // Question Found.
                $data = $result->fetch_assoc();

                // Assigning the Question data.
                $this->id = $data["QID"];
                $this->question = $data['Question'];
                $this->optionA = $data['OptionA'];
                $this->optionA = $data['OptionA'];
                $this->optionA = $data['OptionA'];
                $this->optionA = $data['OptionA'];
                $this->answer = $showAns ? $data['Answer'] : NULL;
                $this->subID = $data['SubID'];

                return $data;

            }
            else
            {
                // Question could not be found.
                return false;
            }
        }

        // Checking whether the Question with particular ID already exists.
        public function checkID()
        {
            /* This function checks whether a Question already exists and returns true/false based on whether it exists.*/

            $query = "SELECT QID FROM $this->tableName WHERE QID = $this->id LIMIT 0, 1;";

            $result = $this->dbConn->query($query);

            chkQuery($this->dbConn, $result);

            // When query is successful.

            if($result->num_rows)
            {
                // Question exists.
                // print_r ($result->fetch_assoc());
                return true;
            }
            else
            {
                // Question does not exists.
                return false;
            }

        }


        // // Checking whether the Question with particular Email already exists.
        // public function checkName()
        // {
        //     /* This function checks whether a Question already exists and returns true/false based on whether it exists.*/

        //     $query = "SELECT QuestionName FROM $this->tableName WHERE QuestionName = '$this->name' LIMIT 0, 1;";

        //     $result = $this->dbConn->query($query);

        //     chkQuery($this->dbConn, $result);

        //     // When query is successful.

        //     if($result->num_rows)
        //     {
        //         // Question already exists.
        //         return true;
        //     }
        //     else
        //     {
        //         // Question does not exists.
        //         return false;
        //     }
        // }

        // Creating a new Question
        public function addQuestion()
        {
            /* This function adds a new Question to the Database and returns true/false on whether the query was successfull. */

            $query = "INSERT INTO $this->tableName (Question, OptionA, OptionB, OptionC, OptionD, Answer, SubID) VALUES ('$this->question', '$this->optionA', '$this->optionB', '$this->optionC', '$this->optionD', '$this->answer', $this->subID);";

            $result = $this->dbConn->query($query);

            if($result)
            {
                // Question Added Successfully.
                return true;
            }
            else
            {
                // Question could not be added.
                return false;
            }
        }

        // Updating Question's data
        public function updateQuestion($property, $newValue)
        {
            /* This function updates the specified property (passed as parameter) of the Question corresponding to the id, to the newName (also passed as parameter). It returns true/false based on whether the updation was successful. */

            $query = "UPDATE $this->tableName SET $property = '$newValue' WHERE QID = $this->id;";

            $result = $this->dbConn->query($query);

            if($result)
            {
                // Updation was successful
                return true;
            }
            else
            {
                // Updation failed.
                return false;
            }
        }

        // Deleting a Question from the database.
        public function deleteQuestion()
        {
            /* This function deletes the Question corresponding to the id from the database table. It returns true/false based on whether deletion was successful.*/
            
            $query = "DELETE FROM $this->tableName WHERE QID = $this->id;";

            if($this->dbConn->query($query))
            {
                //Deletion was successful.
                return true;
            }
            else
            {
                //Deletion failed.
                return false;
            }
        }

    }


?>