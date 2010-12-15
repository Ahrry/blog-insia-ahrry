<?php
include('../../init.php');
$mysql->bfp_connexion('../../config.xml');
$dossier = 'ImageArticle/';
$fichier = $_FILES['image']['name'];
$taille_maxi = 100000;
$taille = filesize($_FILES['image']['tmp_name']);
$extensions = array('.png', '.gif', '.jpg', '.jpeg');
$extension = strrchr($_FILES['image']['name'], '.'); 
if ($fichier != "" )
{
if(!in_array($extension, $extensions)) 
{
     $erreur = 'Vous devez uploader un fichier de type png, gif, jpg, jpeg, txt ou doc...';
}
if($taille>$taille_maxi)
{
     $erreur = 'Le fichier est trop gros...';
}
if($_FILES['image']['error'] == 0) {

     move_uploaded_file($_FILES['image']['tmp_name'], $dossier.$fichier);
	echo 'Upload effectué avec succès !';
}
else
{
     echo $erreur;
     echo 'Echec de l\'upload !';
}
}
	$categorie = $_POST['categorie'];
	$titre = $_POST['titre'];
	$contenu = $_POST['contenu']; 
	if ($categorie != "" AND $titre != "" AND $contenu != "")
	{
		$cat = $bd_articles->recup_categorie($categorie);
		$bd_articles->creer_article($titre, $contenu, $cat);
		echo "Article ajouté !";
	}
	else{
		echo "Rentrer tout les champs !";
	}

?>
