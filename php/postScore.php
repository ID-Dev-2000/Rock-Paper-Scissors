<?php
    error_reporting(0);
    
    include('methods.php');

    $methods = new methodObject($con);

    if(isset($_POST)) {
        $result = (file_get_contents('php://input'));
        $decoded = json_decode($result, true);
        $methods -> uploadScore($decoded['score'], $decoded['username']);  
    }

?>
