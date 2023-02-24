<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, -scale=1.0">
    <title>Wall</title>
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
            } else if(isset($_SESSION['connected_id']) && isset($_GET['user_id'])){
                $userId =intval($_GET['user_id']);
            } else if(isset($_SESSION['connected_id']) && !isset($_GET['user_id'])){
                $userId =intval($_SESSION['connected_id']);
            }            
        ?>
        <aside>
            <?php                   
                $laQuestionEnSql = "SELECT * FROM users WHERE id= '$userId' ";
                $lesInformations = $mysqli->query($laQuestionEnSql);
                $user = $lesInformations->fetch_assoc();
                $enCoursDeTraitement = isset($_POST['follow']);
                if ($enCoursDeTraitement) {
                    $follower = $_SESSION['connected_id'];
                    $followed = $user["id"];
                    $instructionSql = "INSERT INTO followers" . "(id, followed_user_id, following_user_id)" . "VALUES (NULL,"
                        . $followed . ", " . $follower . ");";
                    $ok = $mysqli->query($instructionSql);
                    if (!$ok) {
                        echo "impossible de s'abonner";
                    } else {
                        echo "vous etes abonné";
                    }
                }
            ?>                
            <img src="img/user.jpg" alt="Portrait de l'utilisatrice"/>
            <section>
                <h3>Présentation</h3>
                <p>Sur cette page vous trouverez tous les message de l'utilisatrice : <?php echo $user['alias'] ?></p>
            </section>
            <?php
                $follower = $_SESSION['connected_id'];
                $followed = $user["id"];
                $sql = "SELECT * FROM followers WHERE followed_user_id = '$followed' AND following_user_id = '$follower'";
                $result = $mysqli->query($sql);
                if ($follower == $followed) {
                    echo "Vous ne pouvez pas vous suivre vous meme!";
                } else if ($result->num_rows < 1) {
            ?>
            <form method='post'><button type="submit" name="follow">S'abonner à <?php echo $user["id"] ?></button></form>
            <?php } else {
                        echo "Vous etes déjà abonné";
                    }
            ?>
        </aside>

        <main>
            <?php
                include('addLike.php');
                $laQuestionEnSql = "
                    SELECT posts.content, 
                    posts.id as post_id,
                    posts.created, 
                    users.alias as author_name,
                    users.id, 
                    COUNT(likes.id) as like_number, 
                    GROUP_CONCAT(DISTINCT tags.label) AS taglist,
                    GROUP_CONCAT(DISTINCT tags.id) as tagID 
                    FROM posts
                    JOIN users ON  users.id=posts.user_id
                    LEFT JOIN posts_tags ON posts.id = posts_tags.post_id  
                    LEFT JOIN tags       ON posts_tags.tag_id  = tags.id 
                    LEFT JOIN likes      ON likes.post_id  = posts.id 
                    WHERE posts.user_id='$userId' 
                    GROUP BY posts.id
                    ORDER BY posts.created DESC  
                    ";
                $lesInformations = $mysqli->query($laQuestionEnSql);
                if ( ! $lesInformations){
                    echo("Échec de la requete : " . $mysqli->error);
                }
                while ($post = $lesInformations->fetch_assoc()){
            ?>                                   
            <article>
                <h3>
                    <time datetime='2020-02-01 11:12:13' ><?php echo $post['created'] ?></time>
                </h3>
                <address><?php echo $post['author_name'] ?></address>
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
                            <a href="tags.php?tag_id=<?php echo $post['tagID'] ?>" id="tagsList">#<?php echo $label ?></a>                               
                        <?php } ?>
                </footer>
            </article>
                <?php } ?>
        </main>
    </div>
</body>
</html>
