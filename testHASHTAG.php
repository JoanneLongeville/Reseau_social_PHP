<?php
$texte = "Voici un #exemple de #texte avec des #hashtags";
$expression = "/#(\w+)/u";
preg_match_all($expression, $texte, $matches);
// La fonction preg_match_all renvoie un tableau multidimensionnel contenant les correspondances trouvées.
// Les mots précédés d'un "#" se trouvent dans le deuxième élément du tableau ($matches[1]).
$hashtags = $matches[1];
// Insérer les hashtags dans la base de données
foreach ($hashtags as $hashtag) {
    // Code pour insérer le hashtag dans la base de données
}

var_dump($hashtags)
?>

