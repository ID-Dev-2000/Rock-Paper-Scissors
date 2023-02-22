<?php
    session_start();

    include('php/methods.php');

    $methods = new methodObject($con);

    if(isset($_SESSION['username'])) {
        $username = $_SESSION['username'];
        $loginScore = $_SESSION['scoredAtLogin'];
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rock Paper Scissors</title>
    <link rel="icon" type="image/x-icon" href="image/favicon-32x32.png">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <main class="mainContent">
        <h1 style="margin-bottom: 0px;">ROCK PAPER SCISSORS</h1>
        <div class="gameMain">
            <div class="centered">
                <p class="charName"><b>YOU<span></span></b></p>
            </div>
            <div class="playerSide">
                <div class="RPSSelectPlayer" id="playerRock">&#x1F44A</div>
                <div class="RPSSelectPlayer" id="playerPaper">&#x1F590</div>
                <div class="RPSSelectPlayer" id="playerScissors">&#x270C</div>
            </div>

            <div class="centered">
                <p class="charName"><b>ENEMY<span></span></b></p>
            </div>
            <div class="robotSide">
                <div class="RPSSelectEnemy" id="enemyRock">&#x1F44A</div>
                <div class="RPSSelectEnemy" id="enemyPaper">&#x1F590</div>
                <div class="RPSSelectEnemy" id="enemyScissors">&#x270C</div>
            </div>
        </div>
        
        <div class="resultStatusParent">
            <div class="gameResult" id="gameResult"></div>
        </div>

        <?php if(!isset($_SESSION['username'])) { ?>
            <!-- no login -->
            <p class="cleanP">WIN STREAK: <span id="currentWinStreak">0</span></p>
            <p class="cleanP">RECORD: <span id="highestWinStreak">0</span></p>
        <?php } else { ?>
            <p class="cleanP">WIN STREAK: <span id="currentWinStreak">0</span></p>
            <p class="cleanP">RECORD: <span id="highestWinStreak"> <?php echo $loginScore ?> </span></p> <!-- update the record from win value in DB if username is set -->
        <?php } ?>

        <div class="centered">
            <p class="viewLeaderboard" id="viewLeaderboard">VIEW LEADERBOARD</p>
        </div>

        <hr style="width: 100px;">

        <?php if(!isset($_SESSION['username'])) { ?>
            <div class="accountDiv">
                <p class="accountDivP">ACCOUNT: NOT LOGGED IN</p>
                <p class="accountDivP">
                    <a href="php/login.php">LOGIN</a>
                </p>
            </div>
        <?php } else { ?>
            <div class="accountDiv">
                <p class="accountDivP">ACCOUNT: <span style='color: limegreen'><?php echo $_SESSION['username'] ?></span></p>
                <p class="accountDivP">
                    <a href="php/logout.php">LOGOUT</a>
                </p>
            </div>
            <?php } ?>
    </main>

    <?php if(isset($_SESSION['username'])) {?>
        <script>
            let loginAuth = true
            let accountLoginWins = <?php echo $loginScore // do not need to json_encode() as it is an integer ?>; 
            var accountUsername = <?php echo json_encode($username) ?>;
        </script>
    <?php } else { ?>
        <script>
            let loginAuth = false
            let accountLoginWins = 0; 
            var accountUsername = 'N/A';
        </script>
    <?php } ?>

        <!-- modal -->
        <div class="modalBackground" id="modalBackground" style="display: none;">
            <div class="modalDisplay" id="modalDisplay">
                <div class="modalContent" id="modalContent">
                <div class="modalExitButton" id="modalExitButton">X</div>
                    <h1 style="margin-top: 0px;">Leaderboard</h1>
                    <?php $trackToFive = 1; 
                    foreach($methods -> retrieveTopFiveAccounts() as $accountData) { ?>
                    <p class="leaderBoardItem" id="leaderBoard<?php echo $trackToFive; ?>" style="font-size: large;">
                    <b>#<?php echo $trackToFive; ?></b>: <?php echo $accountData['username']; ?> - <?php echo $accountData['highest_score']; ?> WINS</p>
                    <?php $trackToFive++; } ?>
                    <hr style="width: 100px;">
                    <p id="loggedScore" style="margin-bottom: 0px;"></p>
                </div>
                <div class="refreshButtonParent">
                    <div class="refreshLeaderboardButton" id="refreshLeaderboard">
                        <p>CLICK TO REFRESH</p>
                    </div>
                </div>
            </div>
        </div>

    <script src="script.js"></script>
</body>
</html>
