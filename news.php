<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, -scale=1.0">
    <title>News</title>
    <link rel="stylesheet" href="style.css"/>
</head>

<body>
    <?php
    include 'header.php'; //appel du header
    include 'calldatabase.php'; //appel de la base de données
    ?>

    <div id="wrapper">

        <aside>
            <img src="./img/bigai_Plan de travail 1.png" alt="Portrait de l'utilisatrice" />
            <section>
                <h3 class="nameuser">THE BIG WALL</h3>
                <p>On this page you'll see all the messages from our community</p>
            </section>
        </aside>

        <main>
            <?php
            include('addLike.php');
            $laQuestionEnSql = "
                SELECT posts.content,
                posts.id as post_id,
                posts.created,
                users.alias as author_name,
                users.id as user_id,  
                count(likes.id) as like_number,  
                GROUP_CONCAT(DISTINCT tags.label) AS taglist,
                GROUP_CONCAT(DISTINCT tags.id) AS tagID 
                FROM posts
                JOIN users ON  users.id=posts.user_id
                LEFT JOIN posts_tags ON posts.id = posts_tags.post_id  
                LEFT JOIN tags       ON posts_tags.tag_id  = tags.id 
                LEFT JOIN likes      ON likes.post_id  = posts.id 
                GROUP BY posts.id
                ORDER BY posts.created DESC  
                LIMIT 20
                ";
            $lesInformations = $mysqli->query($laQuestionEnSql);
            // Vérification
            if ( ! $lesInformations){
                echo "<article>";
                echo("Échec de la requete : " . $mysqli->error);
                echo("<p>Indice: Vérifiez la requete  SQL suivante dans phpmyadmin<code>$laQuestionEnSql</code></p>");
                exit();
            }
            while ($post = $lesInformations->fetch_assoc()){
            ?>
            <article>
                <h3>
                    <time><?php echo $post['created'] ?></time>
                </h3>
                <a href="wall.php?user_id=<?php echo $post['user_id'] ?>">
                    <address>
                        from 
                        <?php echo $post['author_name'] ?>
                    </address>
                </a>
                <div>
                    <p><?php echo $post['content'] ?></p>
                </div>
                <footer>
                    <small>
                        <?php include 'addLikeColor.php' ?>
                    </small>
                    <?php 
                    $taglist = $post['taglist'];
                    $tags = explode(",", $post['taglist']);
                    foreach ($tags as $value) {
                    ?>
                    <a href="tags.php?tag_id=<?php echo $post['tagID'] ?>"><?php echo "#" . $value ?></a>
                    <?php } ?>                    
                </footer>
            </article>
            <?php
            }
            ?>              
        </main>
    </div>
</body>
</html>
