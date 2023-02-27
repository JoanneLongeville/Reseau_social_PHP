<header>
    <a href='admin.php'><img src="img/logo-07.png" alt="Logo de notre réseau social"/></a>
    <nav id="menu">
        <a href="news.php">News</a>
        <a href="wall.php">Wall</a>
        <a href="feed.php">Feed</a>
        <a href="tags.php">Hashtags</a>
        <a href="post.php">Post</a>
    </nav>
    <nav id="user">
        <a href="#">Profil</a>
            <ul>
                <li><a href="login.php">Login / Subscribe</a></li>
                <li><a href="settings.php">Settings</a></li>
                <li><a href="followers.php">Followers</a></li>
                <li><a href="subscriptions.php">Subscriptions</a></li>
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
    //header('Refresh:0');
    header('Location: login.php'); 
}
?>