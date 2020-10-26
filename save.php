<?php
/* Eunjoo Na, 000811369
 * Date: 2020.10.20
 * Description: save.php for wumpus game web application. 
 *              It shows the game records of the user and top 10 players
 */
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/save.css">
    <title>Game Records</title>
</head>

<body>
    <?php
    //  connect the database
    try {
        $dbh = new PDO(
            "mysql:host=localhost;dbname=000811369",
            "000811369",
            "19850622"
        );
    } catch (Exception $e) {
        //show the error massage if the connection is failed
        die("ERROR: Couldn't connect. {$e->getMessage()}");
    }

    // email information of the user
    $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
    // winning information of the user
    $win = filter_input(INPUT_POST, "win", FILTER_VALIDATE_INT);
    // losing information of the user
    $lose = filter_input(INPUT_POST, "lose", FILTER_VALIDATE_INT);
    // current date information
    date_default_timezone_set('America/Toronto');
    $date = date('Y-m-d', time());
    ?>
    <div id="container">
        <?php
        // check the email information is received or invalid
        if ($email === "" or $email === false) {
            echo "<div><p>Email not received or invalid!</p><br><br><a href='index.php'>Play again!</a></div> ";
        } else {
            // sql statment to select the email which has the same email address of the user  
            $command = "SELECT Email FROM players WHERE Email = ?";
            // prepare sql statement with parameters and excute it 
            $stmt = $dbh->prepare($command);
            $params = [$email];
            $success = $stmt->execute($params);

            if ($success) {
                //create new email and update or just update the game result after checking user's email is exist or not
                if ($stmt->rowCount() === 0) {
                    // sql statment to create the email, game datas of the user 
                    $command = "INSERT into players (Email, Wins, Loses, `Date`) VALUES (?, ?, ?, ?)";
                    // prepare sql statement with parameters and excute it
                    $stmt = $dbh->prepare($command);
                    $params = [$email, $win, $lose, $date];
                    $success = $stmt->execute($params);
                    if ($success) {
                        //display the user's game information 
                        echo "<div class='left'><h2>Game score have been recorded!<br><br>$email</h2><p>You've won $win times, and lost $lose times so far.<br><br>Last played $date</p><br><br><a href='index.php'>Play again!</a></div>";
                    } else {
                        echo "<div><p>Fail to connect!</p><br><br><a href='index.php'>Play again!</a></div>";
                    }
                } else {
                    // sql statment to update the game datas of the user 
                    $command = "UPDATE players SET Wins= Wins + ?,  Loses = Loses + ?, `Date` = ? WHERE Email = ?";
                    // prepare sql statement with parameters and excute it
                    $stmt = $dbh->prepare($command);
                    $params = [$win, $lose, $date, $email];
                    $success = $stmt->execute($params);
                    if ($success) {
                        // sql statment to get the game datas of the user
                        $command = "SELECT Email, Wins, Loses, `Date` FROM players WHERE Email = ?";
                        // prepare sql statement with parameters and excute it
                        $stmt = $dbh->prepare($command);
                        $params = [$email];
                        $success = $stmt->execute($params);
                        if ($success) {
                            //display the user's game information 
                            while ($row = $stmt->fetch()) {
                                echo "<div class='left'><h2>Game score have been recorded!<br><br>$row[Email]</h2><p>You've won $row[Wins] times, and lost $row[Loses] times so far.<br><br>Last played $row[Date]</p><br><br><a href='index.php'>Play again!</a></div>";
                            }
                        } else {
                            echo "<div><p>Fail to connect!</p><br><br><a href='index.php'>Play again!</a></div>";
                        }
                    } else {
                        echo "<div><p>Fail to connect!</p><br><br><a href='index.php'>Play again!</a></div>";
                    }
                }
            } else {
                echo "<div><p>Fail to connect!</p><br><br><a href='index.php'>Play again!</a></div>";
            }
        }
        ?>
        <div class="right">
            <!-- table for top 10 players -->
            <table class="top">
                <h2>Top 10 Players</h2>
                <tr>
                    <td>Email</td>
                    <td>Win</td>
                    <td>Lose</td>
                    <td>Last Played</td>
                </tr>
                <?php
                // sql statment to get the game datas of the top 10 players
                $command = "SELECT Email , Wins, Loses, `Date` FROM players ORDER BY Wins DESC LIMIT 10";
                // prepare sql statement with parameters and excute it
                $stmt = $dbh->prepare($command);
                $success = $stmt->execute();

                if ($success) {
                    //display the top 10 players' game information 
                    while ($row = $stmt->fetch()) {
                        echo "<tr class><td>$row[Email]</td><td>$row[Wins]</td><td>$row[Loses]</td><td>$row[Date]</td></tr>";
                    }
                } else {
                    echo "<div><p>Fail to connect!</p><br><br><a href='index.php'>Play again!</a></div>";
                }
                ?>
            </table>
        </div>

    </div>
</body>
<script>
    //modify the current history entry to prevent updating game record when the user clicks the refresh button 
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
</script>

</html>