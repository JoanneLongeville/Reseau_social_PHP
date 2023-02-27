<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, -scale=1.0">
    <title>Administration</title>
    <link rel="stylesheet" href="style.css"/>
</head>

<body>
    <?php
    include 'header.php'; //appel du header
    include 'calldatabase.php'; //appel de la base de données
    ?>
        
    <div id="wrapper" class='admin'>

        <aside>
            <h2>Hashtags</h2>
            <?php
            $laQuestionEnSql = "SELECT * FROM `tags` LIMIT 50";
            $lesInformations = $mysqli->query($laQuestionEnSql);
            // Vérification
            if ( ! $lesInformations){
                echo("Échec de la requete : " . $mysqli->error);
                exit();
            }
            while ($tag = $lesInformations->fetch_assoc()){
            ?>
            <a href="tags.php?tag_id=<?php echo $tag['id'] ?>">
                <article>
                    <h3>#<?php echo $tag['label'] ?></h3>
                </article>
            </a>
            <?php 
            } 
            ?>
        </aside>

        <main>
            <h2>Users</h2>
            <?php
            $laQuestionEnSql = "SELECT * FROM `users` LIMIT 50";
            $lesInformations = $mysqli->query($laQuestionEnSql);
            // Vérification
            if ( ! $lesInformations){
                echo("Échec de la requete : " . $mysqli->error);
                exit();
            }
            while ($tag = $lesInformations->fetch_assoc()){
            ?>
            <article>
                <a href="wall.php?user_id=<?php echo $tag['id'] ?>">
                    <h3><?php echo $tag['alias'] ?></h3>
                </a>               
            </article>
            <?php 
            } 
            ?>
        </main>

    </div>

</body>
</html>
