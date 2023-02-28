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
    include 'notConnected.php'; //redirection si pas connecté
    ?>

    <div id="wrapper">
        <aside>
        <?php
        //récupérer le nom de l'utilisateur
        $laQuestionEnSql = "SELECT * FROM users WHERE id= '$userId' ";
        $lesInformations = $mysqli->query($laQuestionEnSql);
        $user = $lesInformations->fetch_assoc();
        $enCoursDeTraitement = isset($_POST['follow']);
        if ($enCoursDeTraitement) {
            $follower = $_SESSION['connected_id'];
            $followed = $user["id"];
            $instructionSql = "SELECT * FROM followers WHERE followed_user_id = $followed AND following_user_id = $follower";
            $result = $mysqli->query($instructionSql);
            if ($result->num_rows > 0) {
                // Si l'utilisateur est déjà abonné, supprimez l'abonnement
                $instructionSql = "DELETE FROM followers WHERE followed_user_id = $followed AND following_user_id = $follower";
                $ok = $mysqli->query($instructionSql);
                if (!$ok) {
                    //echo "Impossible de se désabonner.";
                } else {
                    //echo "Vous vous êtes désabonné.";
                }
            } else {
                // Si l'utilisateur n'est pas abonné, ajoutez l'abonnement
                $instructionSql = "INSERT INTO followers (id, followed_user_id, following_user_id) VALUES (NULL, $followed, $follower)";
                $ok = $mysqli->query($instructionSql);
                if (!$ok) {
                    //echo "Impossible de s'abonner.";
                } else {
                    //echo "Vous êtes abonné.";
                }
            }
        }

        ?>
        <img src="img/bighead-09.png" alt="Portrait de l'utilisatrice" />
        <section>
            <h3 class="nameuser"><?php echo $user["alias"] ?></h3>
        </section>
        <?php
        $follower = $_SESSION['connected_id'];
        $followed = $user["id"];
        $sql = "SELECT * FROM followers WHERE followed_user_id = '$followed' AND following_user_id = '$follower'";
        $result = $mysqli->query($sql);
        if ($follower == $followed) {
        } else if ($result->num_rows < 1) {
        ?>
            <form method='post'>
                <button class="follow" type="submit" name="follow">Follow</button>
            </form>
        <?php } else { ?>
            <form method='post'>
                <button class="unfollow" type="submit" name="follow">Unfollow</button>
            </form>
        <?php } ?>
        </aside>

        <main>
            <?php
            include 'addLike.php';
            $laQuestionEnSql = "
                SELECT posts.content, 
                posts.id as post_id,
                posts.created, 
                users.alias as author_name,
                users.id, 
                COUNT(likes.id) as like_number, 
                GROUP_CONCAT(DISTINCT tags.label) AS taglist,
                GROUP_CONCAT(DISTINCT tags.id) AS tagId 
                FROM posts
                JOIN users ON  users.id = posts.user_id
                LEFT JOIN posts_tags ON posts.id = posts_tags.post_id  
                LEFT JOIN tags       ON posts_tags.tag_id  = tags.id 
                LEFT JOIN likes      ON likes.post_id  = posts.id 
                WHERE posts.user_id = '$userId' 
                GROUP BY posts.id
                ORDER BY posts.created DESC  
                ";
            $lesInformations = $mysqli->query($laQuestionEnSql);
            if ( !$lesInformations){
                echo("Échec de la requete : " . $mysqli->error);
            }
            while ($post = $lesInformations->fetch_assoc()){
            ?>                                   
            <article>
                <h3>
                    <time><?php include 'formatDate.php' ?></time>
                </h3>
                <address>from <?php echo $post['author_name'] ?></address>
                <div>
                    <p><?php echo $post['content'] ?></p>
            </div>                                            
                <footer>
                    <small>
                        <?php include"addLikeColor.php" ?>
                    </small>
                    <?php 
                    $taglist = $post['taglist'];
                    $tags = explode(",", $post['taglist']);
                    foreach ($tags as $value) {
                    ?>
                    <a href="tags.php?tag_id=<?php echo $post['tagId'] ?>"><?php echo "#" . $value ?></a>                               
                    <?php } ?>
                </footer>
            </article>
                <?php } ?>
        </main>
    </div>
</body>
</html>
