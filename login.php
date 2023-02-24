<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, -scale=1.0">
    <title>Login</title>
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
            <h2>Présentation</h2>
            <p>Bienvenu sur notre réseau social.</p>
        </aside>

        <main>
            <article>
                <h2>Connexion</h2>
                <?php
                $enCoursDeTraitement = isset($_POST['email']);
                if ($enCoursDeTraitement)
                {
                    $emailAVerifier = $_POST['email'];
                    $passwdAVerifier = $_POST['motpasse'];
                    $emailAVerifier = $mysqli->real_escape_string($emailAVerifier);
                    $passwdAVerifier = $mysqli->real_escape_string($passwdAVerifier);
                    // crypter le mot de passe
                    $passwdAVerifier = md5($passwdAVerifier);
                    $lInstructionSql = "SELECT * FROM users WHERE email LIKE $emailAVerifier";
                    //vérification de l'utilisateur
                    $res = $mysqli->query($lInstructionSql);
                    $user = $res->fetch_assoc();
                    if ( ! $user OR $user["password"] != $passwdAVerifier)
                    {
                        echo "La connexion a échouée. ";
                        
                    } else
                    {
                        echo "Votre connexion est un succès : " . $user['alias'] . ".";
                        $_SESSION['connected_id']=$user['id'];
                        // header('Location: news.php');
                    }
                }
                ?>                     
                <form action="login.php" method="post">
                    <input type='hidden'name='???' value='achanger'>
                    <dl>
                        <dt><label for='email'>E-Mail</label></dt>
                        <dd><input type='email'name='email'></dd>
                        <dt><label for='motpasse'>Mot de passe</label></dt>
                        <dd><input type='password'name='motpasse'></dd>
                    </dl>
                    <input type='submit'>
                </form>
                <p>Pas de compte?<a href='registration.php'>Inscrivez-vous.</a></p>
            </article>
        </main>
    </div>
</body>
</html>
