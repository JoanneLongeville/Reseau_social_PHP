<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, -scale=1.0">
    <title>Followers</title>
    <link rel="stylesheet" href="style.css"/>
</head>

<body>
    <?php
    include 'header.php'; //appel du header
    include 'calldatabase.php'; //appel de la base de données
    session_start();
    if (!isset($_SESSION['connected_id'])){
        header('Location: login.php');
    };
    $userId =intval($_SESSION['connected_id']);
    ?>

    <div id="wrapper">          
        <aside>
            <img src = "img/user.jpg" alt = "Portrait de l'utilisatrice"/>
            <?php                                
            $laQuestionEnSql = "SELECT * FROM users WHERE id= '$userId' ";
            $lesInformations = $mysqli->query($laQuestionEnSql);
            $user = $lesInformations->fetch_assoc();               
            ?>
            <section>
                <h3>Présentation</h3>
                <p>Sur cette page vous trouverez la liste des personnes qui suivent les messages de <?php echo $user['alias'] ?></p>
            </section>
        </aside>

        <main class='contacts'>
            <?php
            $laQuestionEnSql = "
                SELECT users.*
                FROM followers
                LEFT JOIN users ON users.id=followers.following_user_id
                WHERE followers.followed_user_id='$userId'
                GROUP BY users.id
                ";
            $lesInformations = $mysqli->query($laQuestionEnSql);
            while ($follower = $lesInformations->fetch_assoc()){
            ?>
            <article>
                <img src="img/user.jpg" alt="blason"/>
                <a href="wall.php?user_id=<?php echo $follower['id'] ?>">
                    <h3><?php echo $follower['alias'] ?></h3>
                </a>
                <p><?php //echo $follower['id'] ?></p>
            </article>
            <?php } ?>
        </main>
    </div>
</body>
</html>
