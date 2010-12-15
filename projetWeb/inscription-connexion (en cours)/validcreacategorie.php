
<?php
extract($_POST);
include ('init.php');
$mysql->bfp_connexion('../../config.xml');
if ($categorie=="")
{
echo "Nom de categorie manquant, Merci de remplir le champ Categorie";
exit();
}
$date = date('Y-m-d H:i:s');

mysql_query("INSERT INTO categories VALUES ('', '$date', '$categorie', 1, 2, 1, 1)");
echo "La categorie $categorie a bien ete ajoute";
?> 


