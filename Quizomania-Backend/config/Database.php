<?php

    class Database
    {

        // Database Parameters
        private $hostName = "localhost";
        private $userName = "root";
        private $password = "";
        private $database = "Quizomania";
        private $link;

        // Database Connection
        public function connect()
        {

            // This function creates a mysql connection and returns it.

            $this->link = NULL;

            $this->link = new mysqli($host = $this->hostName, $username = $this->userName, $passwd = $this->password, $dbname = $this->database);

            if($this->link->connect_error)
            {
                echo "Database Connection Error! ------ " . $this->link->connect_error;

                exit();
            }
            // else
            //     echo "Successful";
        
            return $this->link;
        }
    }

    // $db = new Database();
    // $db->connect();

?>

