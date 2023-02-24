<header>
    <a href='admin.php'><img src="img/resoc.jpg" alt="Logo de notre réseau social"/></a>
    <nav id="menu">
        <a href="news.php">Actualités</a>
        <a href="wall.php">Mur</a>
        <a href="feed.php">Flux</a>
        <a href="tags.php">Mots-clés</a>
        <a href="post.php">Publier</a>
    </nav>
    <nav id="user">
        <a href="#">Profil</a>
            <ul>
                <li><a href="login.php">Connexion / Inscription</a></li>
                <li><a href="settings.php">Paramètres</a></li>
                <li><a href="followers.php">Mes suiveurs</a></li>
                <li><a href="subscriptions.php">Mes abonnements</a></li>
                <?php 
                if (isset($_SESSION['connected_id'])) { //si il y a une session ouverte...
                ?> 
                <form method="post">
                    <button type="sumbit" name="logout">Logout</button>
                </form>
                <?php 
                } 
                ?>
            </ul>
    </nav>
</header>



<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])){ 
    session_destroy(); 
    header('Refresh:0');
}
?>