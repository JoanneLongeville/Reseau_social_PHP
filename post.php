<?php
session_start();
?>
<!doctype html>
        <?php
        include 'header.php'
        ?>

        <div id="wrapper" >

            <aside>
                <h2>Présentation</h2>
                <p>Sur cette page on peut poster un message en se faisant 
                    passer pour quelqu'un d'autre</p>
            </aside>
            <main>
                <article>
                    <h2>Poster un message</h2>
                    <?php
                    /**
                     * BD
                     */

                    include 'calldatabase.php';
                    /**
                     * Récupération de la liste des auteurs
                     */
                    ?>
                    <?php

                    if (!isset($_SESSION['connected_id'])){
                     header('Location: login.php');
                    };


                    $userId =intval($_SESSION['connected_id']);
                    $listAuteurs = [];
                    $laQuestionEnSql = "SELECT * FROM users WHERE users.id = $userId";
                    $lesInformations = $mysqli->query($laQuestionEnSql);
                    $user = $lesInformations->fetch_assoc();
                    
                    $listAuteurs[$user['id']] = $user['alias'];
                    
                    // var_dump($listAuteurs);

                    /**
                     * TRAITEMENT DU FORMULAIRE
                     */
                    // Etape 1 : vérifier si on est en train d'afficher ou de traiter le formulaire
                    // si on recoit un champs email rempli il y a une chance que ce soit un traitement
                    $enCoursDeTraitement = isset($_POST['auteur']);
                    if ($enCoursDeTraitement)
                    {
                        // on ne fait ce qui suit que si un formulaire a été soumis.
                        // Etape 2: récupérer ce qu'il y a dans le formulaire @todo: c'est là que votre travaille se situe
                        // observez le résultat de cette ligne de débug (vous l'effacerez ensuite)
                        // echo "<pre>" . print_r($_POST, 1) . "</pre>";
                        // et complétez le code ci dessous en remplaçant les ???
                        $authorId = $_POST['auteur'];
                        $postContent = $_POST['message'];
                        

                        //Etape 3 : Petite sécurité
                        // pour éviter les injection sql : https://www.w3schools.com/sql/sql_injection.asp
                        $authorId = intval($mysqli->real_escape_string($authorId));
                        $postContent = $mysqli->real_escape_string($postContent);
                        //Etape 4 : construction de la requete
                        $lInstructionSql = "INSERT INTO posts "
                                . "(id, user_id, content, created, parent_id) "
                                . "VALUES (NULL, "
                                . $authorId . ", "
                                . "'" . $postContent . "', "
                                . "NOW(), "
                                . "NULL);"
                                ;
                        // echo $lInstructionSql;
                        // Etape 5 : execution
                        $ok = $mysqli->query($lInstructionSql);
                        $id = mysqli_insert_id($mysqli);
                        var_dump($id);
                        
                        if ( ! $ok)
                        {
                            echo "Impossible d'ajouter le message: " . $mysqli->error;
                        } else
                        {
                            echo "Message posté en tant que : " . $listAuteurs[$authorId];
                        }

                        $texte = $postContent;
                        $expression = "/#(\w+)/u";
                        preg_match_all($expression, $texte, $matches);
                        $hashtags = $matches[1];
                        foreach ($hashtags as $hashtag) {
                            $verif_hashtag = "SELECT * FROM tags WHERE tags.label = '$hashtag'";
                            $res = $mysqli->query($verif_hashtag);
                            $tags = $res->fetch_assoc();
                            
                            if ($res && $res->num_rows == 0) {
                                $add_hashtag = "INSERT INTO tags (label) VALUES ('$hashtag')";
                                $ok = $mysqli->query($add_hashtag);
                                $tag = mysqli_insert_id($mysqli);
                                var_dump($tag);
                               
                            }else{
                                $tag = $tags["id"];
                            }

                            $add_link = "INSERT INTO posts_tags (id, post_id, tag_id) VALUES (NULL, '$id', '$tag')";
                            $ok2 = $mysqli->query($add_link);

                        }
                    }
                    ?>                     
                    <form action="post.php" method="post">
                        <input type='hidden' name='???' value='achanger'>
                        <dl>
                            <dt><label for='auteur'>Auteur</label></dt>
                            <dd><select name='auteur'>
                                    <?php
                                    foreach ($listAuteurs as $id => $alias)
                                        echo "<option value='$id'>$alias</option>";
                                    ?>
                                </select></dd>
                            <dt><label for='message'>Message</label></dt>
                            <dd><textarea name='message'></textarea></dd>
                        </dl>
                        <input type='submit'>
                    </form>               
                </article>
            </main>
        </div>
    </body>
</html>
