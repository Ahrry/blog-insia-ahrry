<?php
	session_start();
	//---- Instancie les classes base de données(requêtes)
	require_once 'lil-php/bd/bfp_bd_articles.inc.php';
	$bd_articles = new BD_Articles;

	require_once 'lil-php/bd/bfp_bd_commentaires.inc.php';
	$bd_commentaires = new BD_Commentaires;
	
	require_once 'lil-php/bd/bfp_bd_categories.inc.php';
	$bd_categories = new BD_Categories;

	require_once 'lil-php/bd/bfp_bd_utilisateurs.inc.php';
	$bd_utilisateurs = new BD_Utilisateurs;

	require_once 'lil-php/bfp_email.inc.php';
	$email = new Email;
	
	require_once 'lil-php/bfp_mysql.inc.php';
	$mysql = new Mysql;
?>	