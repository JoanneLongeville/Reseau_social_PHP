<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, -scale=1.0">
    <title>Registration</title>
    <link rel="stylesheet" href="style.css"/>
</head>

<body>
    <?php
    include 'header.php'; //appel du header
    include 'calldatabase.php'; //appel de la base de données
    ?>
    
    <div id="wrapper">

        <aside>
            <h2>Welcome !</h2>
            <img src="img/bigai_Plan de travail 1.png" alt="AI">
        </aside>

        <main>
            <article>
                <h2>Please sign in :</h2>
                <?php
                $enCoursDeTraitement = isset($_POST['email']);
                if ($enCoursDeTraitement){
                    $new_email = $_POST['email'];
                    $new_alias = $_POST['pseudo'];
                    $new_passwd = $_POST['motpasse'];
                    //vérifier si l'email ou le pseudo existe déjà
                    $sql = "SELECT * FROM users WHERE email='$new_email' OR alias='$new_alias'";
                    $result = $mysqli->query($sql);
                    if ($result->num_rows > 0) {
                        // L'adresse e-mail ou le pseudo existe déjà
                        echo "E-mail or name already exist, please choose another one.";
                    } else {
                            // L'adresse e-mail et le pseudo sont disponibles, on peut ajouter un nouvel utilisateur
                            // ... code pour ajouter un nouvel utilisateur ...
                    }
                    $new_email = $mysqli->real_escape_string($new_email);
                    $new_alias = $mysqli->real_escape_string($new_alias);
                    $new_passwd = $mysqli->real_escape_string($new_passwd);
                    // on crypte le mot de passe
                    $new_passwd = md5($new_passwd);
                    $lInstructionSql = "INSERT INTO users (id, email, password, alias) "
                        . "VALUES (NULL, "
                        . "'" . $new_email . "', "
                        . "'" . $new_passwd . "', "
                        . "'" . $new_alias . "'"
                        . ");";
                    $ok = $mysqli->query($lInstructionSql);
                    if ( ! $ok){
                        //echo "Sign in failed : " . $mysqli->error;
                    } else{
                        echo "Hello " . $new_alias . " ! Go ";
                        echo " <a href='login.php'>sign in !</a>";
                    }
                }
                ?>                     
                <form action="registration.php" method="post">
                    <input type='hidden'name='???' value='achanger'>
                    <dl>
                        <dt><label for='pseudo'>Name</label></dt>
                        <dd><input type='text'name='pseudo'></dd>
                        <dt><label for='email'>E-mail</label></dt>
                        <dd><input type='email'name='email'></dd>
                        <dt><label for='motpasse'>Password</label></dt>
                        <dd><input type='password'name='motpasse'></dd>
                    </dl>
                        <input type='submit' value="Submit">
                </form>
            </article>
        </main>
    </div>
</body>
</html>
