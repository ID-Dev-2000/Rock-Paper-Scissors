<?php
    session_start();
    include('methods.php');
    include('md5salt.php');

    $methodObject = new methodObject($con);

    $registerCheck = 0;

    if(isset($_POST['registerSubmitButton'])) {
        $username = $_POST['username'];
        $password = md5($salt . ($_POST['password']));
        
        $accountAuth = $methodObject -> verifyIfAccountExists($username);

        if($accountAuth == false) {
            $registerCheck = 1;
        }elseif($accountAuth == true) {
            $methodObject -> registerAccount($username, $password);
            header('location: login.php');
            exit;
        }

    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="icon" type="image/x-icon" href="../image/favicon-32x32.png">
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
    <main>
        <h1>Register</h1>
        <form class="postForm" method="POST">
            <div class="inputForm">
                <label for="username">Username</label>
                <input type="text" name="username" maxlength="20" required>
                <br>
            </div>
            <div class="inputForm">
                <label for="password">Password</label>
                <input type="password" name="password" maxlength="20" required>
                <br>
            </div>
            <button type="submit" name="registerSubmitButton">REGISTER</button>
            <?php
                if($registerCheck == 1){
                    echo '<br>';
                    echo 'Account already exists with username: ' . $username;
                }
            ?>
        </form>
        <br>
        <hr style="width: 100px;">
        <div class="accountMisc">
            <a href="../index.php">RETURN HOME</a>
        </div>
    </main>
</body>
</html>
