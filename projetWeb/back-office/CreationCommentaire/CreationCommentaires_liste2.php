<?php
include('../../init.php');
$mysql->bfp_connexion('../../config.xml');
extract($_POST);
$article = 4;
	if ($commentaire != "")
	{
		$bd_commentaires->creer_commentaire($commentaire,$article);
		echo "Commentaire ajouté !";
	}
	else{
		echo "Rentrer tout les champs !";
	}

?>
