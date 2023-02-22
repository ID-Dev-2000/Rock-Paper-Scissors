<?php
    session_start();
    include('methods.php');
    include('md5salt.php');

    $methodObject = new methodObject($con);

    $loginCheck = 0;

    if(isset($_POST['loginSubmitButton'])) {
        $username = $_POST['username'];
        $password = md5($salt . ($_POST['password']));

        $loginAuth = $methodObject -> authenticateAccountLogin($username, $password);

        if($loginAuth == false) {
            $loginCheck = 1;
        }elseif($loginAuth == true) {
            // takes account data from method, sends to session superglobal
            $accountDataForSession = $methodObject -> loginToAccount($username, $password);
            $_SESSION['username'] = $accountDataForSession['username'];
            $_SESSION['scoredAtLogin'] = $accountDataForSession['highest_score'];
            header('location: ../index.php');
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
    <title>Login</title>
    <link rel="icon" type="image/x-icon" href="../image/favicon-32x32.png">
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
    <main>
        <h1>Login</h1>
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
            <button type="submit" name="loginSubmitButton">LOGIN</button>
            <?php if($loginCheck == 1) {
                echo "<br>";
                echo "Login failed!";
            } ?>
        </form>
    <br>
    <hr style="width: 100px;">
    <div class="accountMisc">
        <p style="margin-bottom: 5px;">Don't have an account?</p>
        <a href="register.php" style="margin-bottom: 5px;">REGISTER</a>
        <a href="../index.php">RETURN HOME</a>
    </div>
    </main>
</body>
</html>
