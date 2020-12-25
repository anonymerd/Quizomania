<?php

    class Game
    {
        // Database Stuff
        private $dbConn;
        private $tableName = "game";

        // Game Properties
        public $id = NULL;
        public $playerID = NULL;
        public $subID = NULL;
        public $attemptedQues = NULL;
        public $totalQues = NULL;
        public $correctAns = NULL;
        public $score = NULL;
        public $createdAt = NULL;

        // Constructor that creates DB Connection
        public function __construct($link)
        {
            $this->dbConn = $link;
        }

        // Get All Games
        public function getGames()
        {
            /* This function retrieves all Games from Database and return a mysqli_result object containing all the results. */

            if($this->playerID !== NULL && $this->subID !== NULL)
                $query = "SELECT * FROM $this->tableName WHERE PlayerID = $this->playerID AND SubID = $this->subID;";
            else if($this->playerID !== NULL)
                $query = "SELECT * FROM $this->tableName WHERE PlayerID = $this->playerID;";
            else if($this->subID !== NULL)
                $query = "SELECT * FROM $this->tableName WHERE SubID = $this->subID;";
            else
                $query = "SELECT * FROM $this->tableName;";

            $result = $this->dbConn->query($query);

            chkQuery($this->dbConn, $result);

            // When query is successful.
            if($result->num_rows > 0)
            {
                // Game(s) Found.
                return $result;
            }
            else
            {
                // Game(s) could not be found.
                return false;
            }

        }

        // Get a single Game
        public function getSingleGame()
        {
            /* This function retrieves a single Game from the Database corresponding to the id recieved as parameter and returns a mysqli_result object containing the Game. */

            $query = "SELECT * FROM $this->tableName WHERE GameID = $this->id LIMIT 0, 1;";

            $result = $this->dbConn->query($query);

            chkQuery($this->dbConn, $result);

            // When query is successful.Game

            if($result->num_rows > 0)
            {
                // Game Found.
                $data = $result->fetch_assoc();

                // Assigning the Game data.
                $this->id = $data["GameID"];
                $this->playerID = $data['PlayerID'];
                $this->attemptedQues = $data['AttemptedQ'];
                $this->subID = $data['SubID'];
                $this->totalQues = $data['TotalQ'];
                $this->score = $data['Score'];
                $this->correctAns = $data['CorrectAns'];
                $this->createdAt = $data['CreatedAt'];

                return $data;

            }
            else
            {
                // Game could not be found.
                return false;
            }
        }

        // Checking whether the Game with particular ID already exists.
        public function checkID()
        {
            /* This function checks whether a Game already exists and returns true/false based on whether it exists.*/

            $query = "SELECT GameID FROM $this->tableName WHERE GameID = $this->id LIMIT 0, 1;";

            $result = $this->dbConn->query($query);

            chkQuery($this->dbConn, $result);

            // When query is successful.

            if($result->num_rows)
            {
                // Game exists.
                // print_r ($result->fetch_assoc());
                return true;
            }
            else
            {
                // Game does not exists.
                return false;
            }

        }

        // Fetching the leaderboard.
        public function getLeaderboard()
        {
            /* This fuction fetches the leaderboard from the database. */

            $query = "SELECT users.Name, subjects.SubID, subjects.SubjectName, game.Score FROM users, subjects, game WHERE game.PlayerID = users.ID AND game.SubID = subjects.SubID";

            $result = $this->dbConn->query($query);

            if(result)
            {
                // Leaderboard fetched successfuly.
                return $result;
            }
            else
            {
                // Leaderoard Not fetched.
                return false;
            }

        }

        // Creating a new Game
        public function addGame()
        {
            /* This function adds a new Game to the Database and returns true/false on whether the query was successfull. */

            $query = "INSERT INTO $this->tableName (PlayerID, SubID, TotalQ, AttemptedQ, CorrectAns, Score, CreatedAt) VALUES ($this->playerID, $this->subID, $this->totalQues, $this->attemptedQues, $this->correctAns, $this->score, $this->createdAt);";

            $result = $this->dbConn->query($query);

            if($result)
            {
                // Game Added Successfully.
                return true;
            }
            else
            {
                // Game could not be added.
                return false;
            }
        }

        // Updating Game's data
        public function updateGame($property, $newValue)
        {
            /* This function updates the specified property (passed as parameter) of the Game corresponding to the id to the newName (also passed as parameter). It returns true/false based on whether the updation was successful. */

            $query = "UPDATE $this->tableName SET $property = '$newValue' WHERE GameID = $this->id;";

            if($this->dbConn->query($query))
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

        // Deleting a Game from the database.
        public function deleteGame()
        {
            /* This function deletes the Game corresponding to the id from the database table. It returns true/false based on whether deletion was successful.*/
            
            $query = "DELETE FROM $this->tableName WHERE GameID = $this->id;";

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