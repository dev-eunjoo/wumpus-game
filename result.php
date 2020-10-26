<?php
/* Eunjoo Na, 000811369
 * Date: 2020.10.20
 * Description: result.php for wumpus game web application. 
 *     It shows the game result to the user and gets the email address from the user through the form.
 */
?>

<?php
//start the session
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <script language="javascript" type="text/javascript">
        //move forward one page in the session history. It is created to prevent the back button working
        window.history.forward();
    </script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/wumpus.css">
    <title>Game Result</title>
</head>

<body>

    <div id="container">
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

        // row information of the user's choice  
        $row = filter_input(INPUT_GET, "row", FILTER_VALIDATE_INT);
        // column information of the user's choice 
        $col = filter_input(INPUT_GET, "col", FILTER_VALIDATE_INT);

        // sql statment to check if the user's choice and wumpuse's location are matched 
        $command = "SELECT `Id` FROM wumpuses WHERE `Row` = ? and `Column` = ?";
        // prepare sql statement with parameters and excute it 
        $stmt = $dbh->prepare($command);
        $params = [$row, $col];
        $success = $stmt->execute($params);
        if ($success) {
            //display the message and create the win, lose data according to the sql result
            if ($stmt->rowCount() === 1) {
                echo "<h1>You Win!</h1>";
                echo "<img src='./win1.jpg' alt='Smiley face' width='600'/>";
                $win = 1;
                $lose = 0;
            } else {
                echo "<h1>Sorry, you lose</h1>";
                echo "<img src='./lose.jpg' alt='Sadly face' width='600'/>";
                $win = 0;
                $lose = 1;
            }
        ?>
            <div>
                <!-- get email, win, lose datas through the from -->
                <form method="POST" action="save.php">
                    <label>Enter your email and record your score!</label>
                    <input type="email" name="email" placeholder='Enter your email' autocomplete="off" required>
                    <input type="hidden" name="win" value=<?= $win ?>>
                    <input type="hidden" name="lose" value=<?= $lose ?>>
                    <input type="submit">
                </form>
            </div>
        <?php
        } else {
            //show the error massage if the connection is failed
            echo "Fail to connect. Please check the connection.";
        }
        ?>
    </div>
</body>

</html>