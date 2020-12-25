<?php

    class Database
    {

        // Database Parameters
        private $hostName = ""; /* Your MySQL Host Name Eg: localhost */
        private $userName = "root"; /* Your MySQL User Name Eg: root */
        private $password = ""; /* Your MySQL Password */
        private $database = "Quizomania"; /* Your MySQL Database name Eg: Quizomania */
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

