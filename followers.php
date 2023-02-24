<?php session_start(); ?>
<!doctype html>

        <?php
        include 'header.php';
        include 'calldatabase.php';

        if (!isset($_SESSION['connected_id'])){
            header('Location: login.php');
        };

        $userId =intval($_SESSION['connected_id']);
        ?>

        <div id="wrapper">          
            <aside>
                <img src = "user.jpg" alt = "Portrait de l'utilisatrice"/>
                <?php
                                
                $laQuestionEnSql = "SELECT * FROM users WHERE id= '$userId' ";
                $lesInformations = $mysqli->query($laQuestionEnSql);
                $user = $lesInformations->fetch_assoc();
               
                ?>
                <section>
                    <h3>Présentation</h3>
                    <p>Sur cette page vous trouverez la liste des personnes qui
                        suivent les messages de l'utilisatrice
                        n° <?php echo intval($_SESSION['connected_id']) ?>
                        <?php echo $user['alias'] ?></p>

                </section>
            </aside>
            <main class='contacts'>
                <?php
                // Etape 1: récupérer l'id de l'utilisateur
                // $userId = intval($_GET['user_id']);
                // Etape 2: se connecter à la base de donnée
                // include 'calldatabase.php';
                // Etape 3: récupérer le nom de l'utilisateur
                $laQuestionEnSql = "
                    SELECT users.*
                    FROM followers
                    LEFT JOIN users ON users.id=followers.following_user_id
                    WHERE followers.followed_user_id='$userId'
                    GROUP BY users.id
                    ";
                $lesInformations = $mysqli->query($laQuestionEnSql);
                // Etape 4: à vous de jouer
                //@todo: faire la boucle while de parcours des abonnés et mettre les bonnes valeurs ci dessous 
                while ($follower = $lesInformations->fetch_assoc())
                {
                ?>
                <article>
                    <img src="user.jpg" alt="blason"/>
                    <a href="wall.php?user_id=<?php echo $follower['id'] ?>">
                    <h3><?php echo $follower['alias'] ?></h3>
                    </a>
                    <p><?php echo $follower['id'] ?></p>
                </article>
                <?php } ?>
            </main>
        </div>
    </body>
</html>
