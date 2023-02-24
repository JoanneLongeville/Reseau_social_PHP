<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, -scale=1.0">
    <title>Feed</title>
    <link rel="stylesheet" href="style.css"/>
</head>

<body>
    <?php
    include 'header.php'; //appel du header
    include 'calldatabase.php'; //appel de la base de données
    session_start(); 
    ?>

    <div id="wrapper">
        <?php           
        if (!isset($_SESSION['connected_id'])){
            header('Location: login.php');
        };
        $userId =intval($_SESSION['connected_id']);
        ?>

        <aside>
            <?php
            $laQuestionEnSql = "SELECT * FROM `users` WHERE id= '$userId' ";
            $lesInformations = $mysqli->query($laQuestionEnSql);
            $user = $lesInformations->fetch_assoc();
            ?>
            <img src="img/user.jpg" alt="Portrait de l'utilisatrice"/>
            <section>
                <h3>Présentation</h3>
                <p>Sur cette page vous trouverez tous les message des utilisatrices auxquel est abonnée l'utilisatrice <?php echo $user['alias'] ?>.</p>
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
                FROM followers 
                JOIN users ON users.id=followers.followed_user_id
                JOIN posts ON posts.user_id=users.id
                LEFT JOIN posts_tags ON posts.id = posts_tags.post_id  
                LEFT JOIN tags       ON posts_tags.tag_id  = tags.id 
                LEFT JOIN likes      ON likes.post_id  = posts.id 
                WHERE followers.following_user_id='$userId' 
                GROUP BY posts.id
                ORDER BY posts.created DESC  
                ";
            $lesInformations = $mysqli->query($laQuestionEnSql);
            if ( ! $lesInformations)
            {
                echo("Échec de la requete : " . $mysqli->error);
            }
            while ($follower = $lesInformations->fetch_assoc())
            {
                var_dump($follower["like_number"]);
            ?>                
            <article>
                <h3>
                    <time datetime='2020-02-01 11:12:13' ><?php echo $follower['created'] ?></time>
                </h3>
                <a href="wall.php?user_id=<?php echo $follower['user_id'] ?>">
                    <address>par <?php echo $follower['author_name'] ?></address>
                </a>
                <div>
                    <p><?php echo $follower['content'] ?></p>
                </div>                                            
                <footer>
                    <small>
                        <form method="post">
                            <input type="hidden" value="<?php echo $follower['post_id'] ?>" name="post_id"></input>
                            <input type='submit' value="♥ <?php echo $follower['like_number'] ?>">
                        </form>
                    </small>
                    <?php 
                    $taglist = explode(",", $follower['taglist']);
                    foreach ($taglist as $label) {
                    ?>
                    <a href="tags.php?tag_id=<?php echo $follower['tagID'] ?>">#<?php echo $label ?></a>
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
