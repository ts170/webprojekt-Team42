<?php
session_start();
include_once '../outsourced-php-code/userdata.php';
global $count;
$count= 0;

try {
    $db = new PDO($dsn, $dbuser, $dbpass, $option);
    $stmt = $db->prepare("SELECT * FROM `notifications_posts_view` WHERE `notified_user` = :user");

    if ($stmt->execute(array(":user"=> $_SESSION["user-id"]))){
        while ($row = $stmt->fetch()){

            //Beitrag wird als Listenelemt für das Dropdown in der Navbar zurückgegeben

            echo '<a class="text-white" href="show-post.php?post-id='.$row["post_id"].'&follow-id='.$row["notification_id"].'">';
			echo '<li>';
            echo 'Neuer Beitrag von '.$row["user_name"];
            if ($row["topic_name"] !== null){
                echo ' in '.$row["topic_name"];
            }
            echo '</li>';
            echo '</a>';
            echo '<div class="dropdown-divider"></div>';


            //count wird verwendet um die Anzahl an Notifications neben der Glocke anzuzeigen
            $count++;

        }
    } else {
        echo 'Datenbank Fehler';
        echo 'bitte wende dich an den Administrator';
    }
} catch (PDOException $e) {
    echo "Error!: Bitten wenden Sie sich an den Administrator...<br/>";
    die();
}
$_SESSION["notification-count"] = $count;
