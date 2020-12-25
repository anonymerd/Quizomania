<?php

    class Subject
    {
        // Database Stuff
        private $dbConn;
        private $tableName = "subjects";

        // Subject Properties
        public $id = NULL;
        public $name = NULL;
        public $totalQues = NULL;
        public $createdAt = NULL;
        public $createdBy = NULL;

        // Constructor that creates DB Connection
        public function __construct($link)
        {
            $this->dbConn = $link;
        }

        // Get All Subjects
        public function getSubjects()
        {
            /* This function retrieves all subjects from Database and return a mysqli_result object containing all the results. */

            $query = "SELECT * FROM $this->tableName;";

            $result = $this->dbConn->query($query);

            chkQuery($this->dbConn, $result);

            // When query is successful.

            if($result->num_rows > 0)
            {
                // Subject(s) Found.
                return $result;
            }
            else
            {
                // Subject(s) could not be found.
                return false;
            }

        }

        // Get a single Subject
        public function getSingleSubject()
        {
            /* This function retrieves a single Subject from the Database corresponding to the id recieved as parameter and returns a mysqli_result object containing the Subject. */

            $query = "SELECT * FROM $this->tableName WHERE SubID = $this->id LIMIT 0, 1;";

            $result = $this->dbConn->query($query);

            $result = $this->dbConn->query($query);

            chkQuery($this->dbConn, $result);

            // When query is successful.

            if($result->num_rows > 0)
            {
                // Subject Found.
                $data = $result->fetch_assoc();

                // Assigning the Subject data.
                $this->id = $data["SubID"];
                $this->name = $data['SubjectName'];
                $this->totalQues = $data['TotalQ'];
                $this->createdAt = $data['CreatedAt'];
                $this->createdBy = $data['CreatedBy'];

                return $data;

            }
            else
            {
                // Subject could not be found.
                return false;
            }
        }

        // Checking whether the Subject with particular ID already exists.
        public function checkID()
        {
            /* This function checks whether a Subject already exists and returns true/false based on whether it exists.*/

            $query = "SELECT SubID FROM $this->tableName WHERE SubID = $this->id LIMIT 0, 1;";

            $result = $this->dbConn->query($query);

            chkQuery($this->dbConn, $result);

            // When query is successful.

            if($result->num_rows > 0)
            {
                // ID was found
                return true;
            }
            else
            {
                // ID was not found.
                return false;
            }

        }


        // Checking whether the Subject with particular Email already exists.
        public function checkName()
        {
            /* This function checks whether a Subject already exists and returns true/false based on whether it exists.*/

            $query = "SELECT SubjectName FROM $this->tableName WHERE SubjectName = '$this->name' LIMIT 0, 1;";

            $result = $this->dbConn->query($query);

            chkQuery($this->dbConn, $result);

            // When query is successful.

            if($result->num_rows)
            {
                // Subject already exists.
                return true;
            }
            else
            {
                // Subject does not exists.
                return false;
            }
        }

        // Creating a new Subject
        public function addSubject()
        {
            /* This function adds a new Subject to the Database and returns true/false on whether the query was successfull. */

            $query = "INSERT INTO $this->tableName (SubjectName, TotalQ, createdAt, createdBy) VALUES ('$this->name', $this->totalQues, CURRENT_TIMESTAMP, $this->createdBy);";

            if($this->dbConn->query($query))
            {
                // Subject added successfully.
                return true;
            }
            else
            {
                // Subject could not be added.
                return false;
            }
        }

        // Updating Subject's data
        public function updateSubject($property, $newValue)
        {
            /* This function updates the specified property (passed as parameter) of the Subject corresponding to the id to the newName (also passed as parameter). It returns true/false based on whether the updation was successful. */

            $query = "UPDATE $this->tableName SET $property = '$newValue' WHERE SubID = $this->id;";

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

        // Deleting a Subject from the database.
        public function deleteSubject()
        {
            /* This function deletes the Subject corresponding to the id from the database table. It returns true/false based on whether deletion was successful.*/
            
            $query = "DELETE FROM $this->tableName WHERE SubID = $this->id;";

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