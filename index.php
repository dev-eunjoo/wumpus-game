<?php
/* Eunjoo Na, 000811369
 * Date: 2020.10.20
 * Description: index.php is the main page for wumpus game web application.       
 */
?>

<!DOCTYPE html>
<html>

<head>
    <title>hunt_the_wumpus</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/wumpus.css">
</head>

<body>
    <div id="container">
        <h1 id="title">Hunt the Wumpus!</h1>
        <div id="instruction">
            <img src="wumpus.JPG">
            <p>There are 5 Wumpuses in the blocks. Guess the location of Wumpus and click the block! If you find the Wumpus, then you win!</p>
        </div>
        <table>
            <?php
            for ($r = 0; $r < 5; $r++) {
                echo "<tr>";
                for ($c = 0; $c < 5; $c++) {
                    echo "<td><a href='result.php?row=$r&col=$c'></a></td>";
                }
                echo "</tr>";
            }
            ?>
        </table>
    </div>
    <footer>
        Copyright © Eunjoo Na 2020
    </footer>
</body>

</html>