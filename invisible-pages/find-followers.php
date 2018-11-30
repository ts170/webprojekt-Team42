<?php
session_start();

include_once '../outsourced-php-code/userdata.php';
$result = [];

try {
    $db = new PDO($dsn, $dbuser, $dbpass, $option);
    $stmt = $db->prepare("SELECT `post_id`, `topic_id` FROM `posts` ORDER BY `post_id` DESC LIMIT 0,1");
    $stmt->execute();
    $post_information=$stmt->fetch();
    var_dump($post_information);
    echo '<br>';
    $user_id = $_SESSION["user_id"];
    var_dump($_SESSION);

}catch (PDOException $e) {
    echo "Error!: Bitten wenden Sie sich an den Administrator...";
    die();
}

try {
    $db = new PDO($dsn, $dbuser, $dbpass, $option);
    $stmt = $db->prepare("SELECT `following_user_id_user` FROM `user_follow_user` WHERE `followed_user_id`= :user
                                    UNION ALL 
                                    SELECT `following_user_id_topic` FROM `user_follow_topic` WHERE `followed_topic_id`= :topic");
    if ($stmt->execute(array(":user"=>$_SESSION["user-id"], ":topic"=>$post_information["topic_id"]))){
        echo 'test';
        while ($spalte = $stmt->fetch()){
            var_dump($spalte);
            echo '<br>';
            if (in_array($spalte[0], $result)){

            }
            else {
                echo 'TEST';
                $result[] = $spalte[0];
            }

        }
        echo 'TEST';
    }
    else {
        echo 'Datenbank Fehler 1234';
    }
    $db = 0;

    var_dump($result);
    echo '<br>';
} catch (PDOException $e){
    echo "Error!: Bitten wenden Sie sich an den Administrator...";
    die();
}
try {
    foreach ($result as $a){
        $db = new PDO($dsn, $dbuser, $dbpass, $option);
        $stmt = $db->prepare("INSERT INTO `notifications` (`notified_user`,`post_id`) VALUES (:user, :post)");

        $stmt->execute(array(":user"=>$a, ":post"=>$post_information["post_id"]));
    }

}catch (PDOException $e){
    echo "Error2!: Bitten wenden Sie sich an den Administrator...";
    die();
}
header('Location: ../profile.php');