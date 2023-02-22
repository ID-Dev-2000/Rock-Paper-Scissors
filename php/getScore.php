<?php
    error_reporting(0);

    include('methods.php');

    $methods = new methodObject($con);

    $retrievedAccounts = $methods -> retrieveTopFiveAccounts($con);

    if(isset($_GET)) {
        $accountArray = [];
        foreach($retrievedAccounts as $account) {
            array_push($accountArray, $account);
        }
    
        echo json_encode($accountArray);
    }

?>
