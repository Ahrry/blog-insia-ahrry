<?php
	include('../init.php');
	//---- Connexion à la base de données
	$mysql->bfp_connexion('../config.xml');
	$_SESSION['type']=2;
	$_SESSION['id']=6;
	$_SESSION['login']="toto";
?>

<?php "<?xml version='1.0' encoding='UTF-8'?>"; ?>
<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Strict//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd'>
<html xmlns='http://www.w3.org/1999/xhtml'>
<head>
	<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
	<title>Gestion des catégories</title>	
	<link title="css2" type="text/css" rel="stylesheet" media="screen" href="../css/master.css">
  <link title="css1" type='text/css' rel='stylesheet' media='all' href='dragdrop.css' /> 
</head> 

<body onLoad="load()">
  <div id="block">
    <div id="head">
      <img src="../images/logo.png">
    </div>
    <div id="bar">

    </div>
    <div id="sheet">
      <div id="menuCategories" class="contentMenuFront">
        <div class="titre">Catégories</div>
			<?php 
			$sql = $bd_categories->get_categories_visible();
			while ($donnees = mysql_fetch_array($sql)) //Affiche tous les articles
			{		
				echo "<a href='#' onClick='afficher(\"listeArticles\",{$donnees['categorie_id']})'><div class='button'>{$donnees['categorie_libelle']}</div></a>";
			} ?>
      </div>
	  
      <div id="menuArticles" class="contentMenuFront">
      
      </div>
	  
      <div id="contentFront">
     
      </div>

      <div class="clear"></div>


    </div>
  </div>
  	<!-- Script Javascript -->
	<script type="text/javascript" src="../javascript/jquery/jquery.js"></script>
	<script type="text/javascript" src="frontOffice.js"></script>
</body>
</html>
