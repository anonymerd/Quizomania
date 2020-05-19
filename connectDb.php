<?php

    // database credentials...
    $host = "localhost";
    $dbUser = "root";
    $dbPass = "";
    $dbName = "QUIZOMANIA";

    $link = mysqli_connect($host, $dbUser, $dbPass, $dbName);

    // checking database connectivity
    if(mysqli_connect_error())
        die("The Database connction has some issues");

?>