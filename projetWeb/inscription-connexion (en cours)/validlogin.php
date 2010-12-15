<?php

include ('init.php');
$mysql->bfp_connexion('../../config.xml');

$login = $_POST['login'];
$mdp = $_POST['mdp'];

$verifmdp =mysql_query("SELECT * FROM utilisateurs WHERE utilisateur_login='$login'");
$valmdp = mysql_fetch_array($verifmdp);




if ( $login !=NULL and $mdp!=NULL )
	{
	if ($valmdp["utilisateur_password"]!=NULL)
		{
		if (crypt($mdp,$valmdp["utilisateur_password"]) == $valmdp)
			echo "Bonjour $login, vous êtes connecté";
			$_SESSION["id"]=$valmdp["utilisateur_id"];
			$_SESSION["type"]=$valmdp["utilisateur_type"];
			$_SESSION["login"]=$login;
		
			header('Chemin d acces');
		}
		else 
			{
			echo "Erreur de login/mot de passe";	
			}
	}
else
	{
	echo "Champs incomplet";
	}



?>