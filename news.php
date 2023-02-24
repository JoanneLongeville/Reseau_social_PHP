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
    session_start();
    ?>

    <div id="wrapper">

        <aside>
            <img src="img/user.jpg" alt="Portrait de l'utilisatrice"/>
            <section>
                <h3>Présentation</h3>
                <p>Sur cette page vous trouverez les derniers messages de tous les utilisateurs du site.</p>
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
                LIMIT 5
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
                <h3><time><?php echo $post['created'] ?></time></h3>
                <a href="wall.php?user_id=<?php echo $post['user_id'] ?>">
                    <address>par <?php echo $post['author_name'] ?></address>
                </a>
                <div>
                    <p><?php echo $post['content'] ?></p>
                </div>
                <footer>
                    <small>
                        <form method="post">
                            <input type="hidden" value="<?php echo $post['post_id'] ?>" name="post_id"></input>
                            <input type='submit' value="♥ <?php echo $post['like_number'] ?>">
                        </form>
                    </small>
                    <?php 
                    $taglist = explode(",", $post['taglist']);
                    foreach ($taglist as $label) { ?>
                        <a href="tags.php?tag_id=<?php echo $post['tagID'] ?>">#<?php echo $label ?></a>                              
                    <?php
                    }
                    ?>                           
                </footer>
            </article>
            <?php
            }
            ?>              
        </main>
    </div>
</body>
</html>
