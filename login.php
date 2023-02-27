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
        <?php
            if (isset($_SESSION['connected_id'])){
                header('Location: feed.php');
            }
        ?>
        
        <aside>
            <h2>Welcome !</h2>
            <img src="img/bigai_Plan de travail 1.png" alt="AI">
        </aside>

        <main>
            <article>
                <h2>Please log in :</h2>
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
                    $lInstructionSql = "SELECT * "
                    . "FROM users "
                    . "WHERE "
                    . "email LIKE '" . $emailAVerifier . "'";
                    //vérification de l'utilisateur
                    $res = $mysqli->query($lInstructionSql);
                    $user = $res->fetch_assoc();
                    if ( !$user OR $user["password"] != $passwdAVerifier)
                    {
                        echo "Connection failed.";
                        
                    } else {
                        echo "Hello " . $user['alias'] . " !";
                        $_SESSION['connected_id'] = $user['id'];
                        header('Location: wall.php');
                        exit;
                    }
                }
                ?>                     
                <form action="login.php" method="post">
                    <input type='hidden'name='???' value='achanger'>
                    <dl>
                        <dt><label for='email'>E-Mail</label></dt>
                        <dd><input type='email'name='email'></dd>
                        <dt><label for='motpasse'>Password</label></dt>
                        <dd><input type='password'name='motpasse'></dd>
                    </dl>
                    <input type='submit'>
                </form>
                <p>Not registered yet ? <a href='registration.php'>Register</a></p>
            </article>
        </main>
    </div>
</body>
</html>
