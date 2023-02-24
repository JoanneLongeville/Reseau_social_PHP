<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, -scale=1.0">
    <title>Post</title>
    <link rel="stylesheet" href="style.css"/>
</head>

<body>
    <?php
    include 'header.php'; //appel du header
    include 'calldatabase.php'; //appel de la base de données
    session_start();
    ?>

    <div id="wrapper" >

        <aside>
            <h2>Présentation</h2>
            <p>Sur cette page on peut poster un message.</p>
        </aside>

        <main>
            <article>
                <h2>Poster un message</h2>                
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
                $enCoursDeTraitement = isset($_POST['auteur']);
                if ($enCoursDeTraitement){
                    $authorId = $_POST['auteur'];
                    $postContent = $_POST['message'];                    
                    $authorId = intval($mysqli->real_escape_string($authorId));
                    $postContent = $mysqli->real_escape_string($postContent);                      
                    $lInstructionSql = "INSERT INTO posts "
                            . "(id, user_id, content, created, parent_id) "
                            . "VALUES (NULL, "
                            . $authorId . ", "
                            . "'" . $postContent . "', "
                            . "NOW(), "
                            . "NULL);"
                            ;
                    $ok = $mysqli->query($lInstructionSql);
                    $id = mysqli_insert_id($mysqli);                        
                    if ( ! $ok){
                        echo "Impossible d'ajouter le message: " . $mysqli->error;
                    } else{
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
