<!DOCTYPE html>
<html lang="fr">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
                <h2>Mots-clés</h2>
                <?php
                $laQuestionEnSql = "SELECT * FROM `tags` LIMIT 50";
                $lesInformations = $mysqli->query($laQuestionEnSql);
                // Vérification
                if ( ! $lesInformations)
                {
                    echo("Échec de la requete : " . $mysqli->error);
                    exit();
                }
                while ($tag = $lesInformations->fetch_assoc())
                {
                ?>
                <a href="tags.php?tag_id=<?php echo $tag['id'] ?>">
                    <article>
                        <h3>#<?php echo $tag['label'] ?></h3>
                        <p><?php echo $tag['id'] ?></p>
                        <nav>Messages</nav>
                    </article>
                </a>
                <?php 
                } 
                ?>
            </aside>

            <main>
                <h2>Utilisatrices</h2>
                <?php
                $laQuestionEnSql = "SELECT * FROM `users` LIMIT 50";
                $lesInformations = $mysqli->query($laQuestionEnSql);
                // Vérification
                if ( ! $lesInformations)
                {
                    echo("Échec de la requete : " . $mysqli->error);
                    exit();
                }
                while ($tag = $lesInformations->fetch_assoc())
                {
                ?>
                <article>
                    <a href="wall.php?user_id=<?php echo $tag['id'] ?>">
                        <h3><?php echo $tag['alias'] ?></h3>
                    </a>
                    <p><?php echo $tag['id'] ?></p>
                    <nav>
                        <a href="wall.php?user_id=<?php echo $tag['id'] ?>">Mur</a>
                        <a href="feed.php?user_id=<?php echo $tag['id'] ?>">Flux</a>
                        <a href="settings.php?user_id=<?php echo $tag['id'] ?>">Paramètres</a>
                        <a href="followers.php?user_id=<?php echo $tag['id'] ?>">Suiveurs</a>
                        <a href="subscriptions.php?user_id=<?php echo $tag['id'] ?>">Abonnements</a>
                    </nav>
                </article>
                <?php 
                } 
                ?>
            </main>

        </div>

    </body>
    
</html>
