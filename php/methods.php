<?php
    include('db.php');

    class methodObject {
        private $dbConnection;

        public function __construct($db) {
            $this -> db = $db;
        }
        
        // begin account methods

        public function registerAccount($accountName, $accountPassword) {
            $initialWins = 0;
            $sql = "INSERT into users(username, password, highest_score) VALUES (?, ?, ?)";
            $preparedStatement = mysqli_prepare($this -> db, $sql);
            mysqli_stmt_bind_param($preparedStatement,'ssi', $accountName, $accountPassword, $initialWins);
            mysqli_execute($preparedStatement);
        }

        // prevent multiple accounts being created with the same username
        public function verifyIfAccountExists($accountName) {
            $sql = "SELECT * FROM users WHERE username=?";
            $preparedStatement = mysqli_prepare($this -> db, $sql);
            mysqli_stmt_bind_param($preparedStatement,'s', $accountName);
            mysqli_execute($preparedStatement);
            $result = mysqli_stmt_get_result($preparedStatement);
            // returns true if account exists
            if(mysqli_num_rows($result) == 0) {
                return true;
            } else {
                return false;
            }
        }

        // authenticate account login details, returns true if information is correct
        public function authenticateAccountLogin($accountName, $accountPassword) {
            $sql = "SELECT * FROM users WHERE username=? AND password=?";
            $preparedStatement = mysqli_prepare($this -> db, $sql);
            mysqli_stmt_bind_param($preparedStatement,'ss', $accountName, $accountPassword);
            mysqli_execute($preparedStatement);
            $result = mysqli_stmt_get_result($preparedStatement);
            // returns true if account information matches in DB
            if(mysqli_num_rows($result) > 0) {
                return true;
            } else {
                return false;
            }
        }

        // takes login details, returns account data as array
        public function loginToAccount($accountName, $accountPassword) {
            $sql = "SELECT * FROM users WHERE username=? AND password=?";
            $preparedStatement = mysqli_prepare($this -> db, $sql);
            mysqli_stmt_bind_param($preparedStatement,'ss', $accountName, $accountPassword);
            mysqli_execute($preparedStatement);
            $result = mysqli_stmt_get_result($preparedStatement);
            $row = mysqli_fetch_array($result);
            return $row;
        }

        // end account methods

        // retrieve top 5 accounts by score
        public function retrieveTopFiveAccounts() {
            $sql = "SELECT username, highest_score FROM users ORDER BY highest_score DESC LIMIT 5";
            $result = mysqli_query($this -> db, $sql);
            return $result;
        }

        // update account score data in DB
        public function uploadScore($score, $username) {
            $sql = "UPDATE users SET highest_score=? WHERE username=?";
            $preparedStatement = mysqli_prepare($this -> db, $sql);
            mysqli_stmt_bind_param($preparedStatement, 'ss', $score, $username);
            mysqli_stmt_execute($preparedStatement);
        }
    }

?>
