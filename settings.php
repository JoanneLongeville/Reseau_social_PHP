<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, -scale=1.0">
    <title>Settings</title>
    <link rel="stylesheet" href="style.css"/>
</head>

<body>
    <?php
    include 'header.php'; //appel du header
    include 'calldatabase.php'; //appel de la base de données
    session_start()
    ?>

    <div id="wrapper" class='profile'>

        <aside>
            <img src="img/user.jpg" alt="Portrait de l'utilisatrice"/>
            <section>
                <h3>Présentation</h3>
                <p>Sur cette page vous trouverez les informations de l'utilisatrice n° <?php echo intval($_SESSION['connected_id']) ?></p>
            </section>
        </aside>

        <main>
            <?php
            if (!isset($_SESSION['connected_id'])){
                header('Location: login.php');
            };        
            $userId =intval($_SESSION['connected_id']);
            $laQuestionEnSql = "
                SELECT users.*, 
                count(DISTINCT posts.id) as totalpost, 
                count(DISTINCT given.post_id) as totalgiven, 
                count(DISTINCT recieved.user_id) as totalrecieved 
                FROM users 
                LEFT JOIN posts ON posts.user_id=users.id 
                LEFT JOIN likes as given ON given.user_id=users.id 
                LEFT JOIN likes as recieved ON recieved.post_id=posts.id 
                WHERE users.id = '$userId' 
                GROUP BY users.id
                ";
            $lesInformations = $mysqli->query($laQuestionEnSql);
            if ( ! $lesInformations){
                echo("Échec de la requete : " . $mysqli->error);
            }
            $user = $lesInformations->fetch_assoc();
            ?>                
            <article class='parameters'>
                <h3>Mes paramètres</h3>
                <dl>
                    <dt>Pseudo</dt>
                    <dd><?php echo $user['alias'] ?></dd>
                    <dt>Email</dt>
                    <dd><?php echo $user['email'] ?></dd>
                    <dt>Nombre de message</dt>
                    <dd><?php echo $user['totalpost'] ?></dd>
                    <dt>Nombre de "J'aime" donnés </dt>
                    <dd><?php echo $user['totalgiven'] ?></dd>
                    <dt>Nombre de "J'aime" reçus</dt>
                    <dd><?php echo $user['totalrecieved'] ?></dd>
                </dl>
            </article>
        </main>
    </div>       
</body>
</html>
