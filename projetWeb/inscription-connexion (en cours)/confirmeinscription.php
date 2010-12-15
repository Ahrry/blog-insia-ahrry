<?php

include ('init.php');
$mysql->bfp_connexion('../../config.xml');

$val=$_GET["pseudo"]
mysql_query("UPDATE utilisateurs SET utilisateur_valid='1'");
echo " Votre compte est désormais activé ";



header '/../../index.php';
?>