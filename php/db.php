<?php
    $host = 'localhost';
    $user = 'root';
    $pass = '';
    $dbName = 'rockpaperscissors';

    $con = mysqli_connect($host, $user, $pass, $dbName);

    if (!$con) {
        error_log("Error: " . mysqli_connect_errno());
        exit();
    }
    
?>
