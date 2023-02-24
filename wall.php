<?php session_start(); ?>

<!doctype html>

        <?php
        include 'header.php'
        ?>

        <div id="wrapper">
            <?php
            /**
             * Etape 1: Le mur concerne un utilisateur en particulier
             * La première étape est donc de trouver quel est l'id de l'utilisateur
             * Celui ci est indiqué en parametre GET de la page sous la forme user_id=...
             * Documentation : https://www.php.net/manual/fr/reserved.variables.get.php
             * ... mais en résumé c'est une manière de passer des informations à la page en ajoutant des choses dans l'url
             */
        

             if (!isset($_SESSION['connected_id'])){
                header('Location: login.php');
            } else if(isset($_SESSION['connected_id']) && isset($_GET['user_id'])){
                $userId =intval($_GET['user_id']);
            } else if(isset($_SESSION['connected_id']) && !isset($_GET['user_id'])){
                $userId =intval($_SESSION['connected_id']);
            }
            
            ?>
            <?php
            /**
             * Etape 2: se connecter à la base de donnée
             */
            include 'calldatabase.php';
            ?>

            <aside>
                <?php
                /**
                 * Etape 3: récupérer le nom de l'utilisateur
                 */                
                $laQuestionEnSql = "SELECT * FROM users WHERE id= '$userId' ";
                $lesInformations = $mysqli->query($laQuestionEnSql);
                $user = $lesInformations->fetch_assoc();
                //@todo: afficher le résultat de la ligne ci dessous, remplacer XXX par l'alias et effacer la ligne ci-dessous
                // echo "<pre>" . print_r($user, 1) . "</pre>";
                echo "<pre>" . print_r($_SESSION['connected_id']) . "</pre>";

                $enCoursDeTraitement = isset($_POST['follow']);
        if ($enCoursDeTraitement) {
            $follower = $_SESSION['connected_id'];
            $followed = $user["id"];
            $instructionSql = "INSERT INTO followers" . "(id, followed_user_id, following_user_id)" . "VALUES (NULL,"
                . $followed . ", " . $follower . ");";
            $ok = $mysqli->query($instructionSql);
            var_dump($ok);
            if (!$ok) {
                echo "impossible de s'abonner";
            } else {
                echo "vous etes abonné";
            }
        }
                ?>
                
                <img src="user.jpg" alt="Portrait de l'utilisatrice"/>
                <section>
                    <h3>Présentation</h3>
                    <p>Sur cette page vous trouverez tous les message de l'utilisatrice : <?php echo $user['alias'] ?>
                    </p>
                </section><?php
                    $follower = $_SESSION['connected_id'];
                    $followed = $user["id"];
                    $sql = "SELECT * FROM followers WHERE followed_user_id = '$followed' AND following_user_id = '$follower'";
                    $result = $mysqli->query($sql);
                    //var_dump($result->num_rows);
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
                /**
                 * Etape 3: récupérer tous les messages de l'utilisatrice
                 */
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
                var_dump($lesInformations);
                if ( ! $lesInformations)
                {
                    echo("Échec de la requete : " . $mysqli->error);
                }

                /**
                 * Etape 4: @todo Parcourir les messsages et remplir correctement le HTML avec les bonnes valeurs php
                 */
                while ($post = $lesInformations->fetch_assoc())
                {
                    var_dump($post["like_number"]);

                    // echo "<pre>" . print_r($post, 1) . "</pre>";
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
                
                            // var_dump($taglist);
                            foreach ($taglist as $label) { ?>

                            <a href="tags.php?tag_id=<?php echo $post['tagID'] ?>" id="tagsList">#<?php echo $label ?></a>
                               
                                <?php
                  }
                ?>
                        </footer>
                    </article>
                <?php } ?>


            </main>
        </div>
    </body>
</html>
