<?php

    require_once __DIR__ . '/../auth/Validation/validate.php';

    class User
    {
        // Database Stuff
        private $dbConn;
        private $tableName = "users";

        // User Properties
        public $id = NULL;
        public $name = NULL;
        public $email = NULL;
        public $pass = NULL;
        public $isAdmin = NULL;
        public $createdAt = NULL;

        // Constructor that creates DB Connection
        public function __construct($link)
        {
            $this->dbConn = $link;
        }

        // Get All Users
        public function getUsers()
        {
            /* This function retrieves all users from Database and return a mysqli_result object containing all the results. */

            $query = "SELECT * FROM $this->tableName;";

            $result = $this->dbConn->query($query);

            chkQuery($this->dbConn, $result);

            // When query is successful.

            if($result->num_rows > 0)
            {
                // User(s) Found.
                return $result;
            }
            else
            {
                // User(s) could not be found.
                return false;
            }

        }

        // Get a single User
        public function getSingleUser()
        {
            /* This function retrieves a single User from the Database corresponding to the id and/or email and returns an associative array containing all the results.*/

            if($this->id !== NULL)
                $query = "SELECT * FROM $this->tableName WHERE ID = $this->id LIMIT 0, 1;";
            else if($this->email !== NULL)
                $query = "SELECT * FROM $this->tableName WHERE Email = '$this->email' LIMIT 0, 1;";
            else
            {
                // * Demo query that will throw database error.
                $query = "SELECT";
            }

            $result = $this->dbConn->query($query);

            chkQuery($this->dbConn, $result);

            // When query is successful.

            if($result->num_rows > 0)
            {
                // User Found.
                $data = $result->fetch_assoc();

                // Assigning the user data.
                $this->id = $data["ID"];
                $this->name = $data['Name'];
                $this->email = $data['Email'];
                $this->pass = $data['Password'];
                $this->isAdmin = $data['isAdmin'] == 1 ? true : false;

                return $data;

            }
            else
            {
                // User could not be found.
                return false;
            }
        }

        // Checking whether the user with particular ID already exists.
        public function checkID()
        {
            /* This function checks whether a user already exists and returns true/false based on whether it exists..*/

            $query = "SELECT ID FROM $this->tableName WHERE ID = $this->id LIMIT 0, 1;";

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


        // Checking whether the user with particular Email already exists ans return true/false on whether the query was successful.
        public function checkEmail()
        {
            /* This function checks whether a user already exists.*/

            $query = "SELECT ID FROM $this->tableName WHERE Email = '$this->email' LIMIT 0, 1;";

            $result = $this->dbConn->query($query);

            chkQuery($this->dbConn, $result);

            // When query is successful.

            if($result->num_rows > 0)
            {
                // Email was found
                return true;
            }
            else
            {
                // Email was not found.
                return false;
            }

        }

        // Adding a new user to the database.
        public function addUser()
        {
            /* This function adds a new user to the Database and returns true/false on whether the query was successfull. */

            $query = "INSERT INTO $this->tableName (Name, Email, Password, isAdmin, CreatedAt) VALUES ('$this->name', '$this->email', '$this->pass', '$this->isAdmin', CURRENT_TIMESTAMP);";

            $result = $this->dbConn->query($query);

            if($result)
            {
                // User Added Successfully.
                return true;
            }
            else
            {
                // User could not be added.
                return false;
            }

        }

        // Updating user's data
        public function updateUser($property, $newValue)
        {
            /* This function updates the specified property (passed as parameter) of the user corresponding to the id to the newName (also passed as parameter). It returns true/false based on whether the updation was successful. */

            $query = "UPDATE $this->tableName SET $property = '$newValue' WHERE ID = $this->id;";

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

        // Deleting a User from the database.
        public function deleteUser()
        {
            /* This function deletes the user corresponding to the id from the database table. It returns true/false based on whether deletion was successful.*/
            
            $query = "DELETE FROM $this->tableName WHERE ID = $this->id;";

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