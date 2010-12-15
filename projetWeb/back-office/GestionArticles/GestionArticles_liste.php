<?php
	include('../../init.php');
	//---- Connexion à la base de données
	$mysql->bfp_connexion('../../config.xml');
	
	$_SESSION['id']=5;
	$_SESSION['type']=2;
	
	//Vérifie le droit d'accès
    if(!isset($_SESSION['id']) || $_SESSION['type']==2) {
       // header('Location: ..\index.php?erreur=4');
    }
	
?>
<?php "<?xml version='1.0' encoding='UTF-8'?>"; ?>
<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Strict//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd'>
<html xmlns='http://www.w3.org/1999/xhtml'>
<head>
	<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
	<title>Gestion des articles</title>
	
	<link title="css2" type="text/css" rel="stylesheet" media="screen" href="../../css/master.css">
	<link title="css1" type='text/css' rel='stylesheet' media='all' href='dragdrop.css' />	
</head> 
<body> 

  <div id="block">
    <div id="head">
      <img src="../../images/logo.png">

    </div>
    <div id="bar">

    </div>
    <div id="sheet">
      <div id="content">
        
        <!-- Choix pour l'affichage -->
        Afficher :<br />
        <input type="radio" name="choix" value="article" id="article" checked="checked" onclick='choixArticle("article")'/> <label for="article">Les articles visibles</label><br />
        <input type="radio" name="choix" value="masquer" id="masquer" onclick='choixArticle("masquer")'/> <label for="masquer">Les articles masqués</label><br />
        <input type="radio" name="choix" value="validation" id="validation" onclick='choixArticle("validation")'/> <label for="validation">Les articles soumis à validation</label><br />

        <br />
        	<div id="infoResult"></div> <!-- Affiche le message de confirmation/erreur -->

        	<div id="lesarticles">
        	<table id="tableau" border="2">
        		<!-- RECHERCHE -->
        		<tr><td colspan='5'>Rechercher : <input class="InputSearch" type="text" name="recherche" id="recherche" />
        		<button type='button' id='btnRech'>Rechercher</button></td>
				    <td colspan='3'><button type='button' id='AjouterArticle'>Ajouter un article</button></td></tr>
        		<!-- ENTETE (TH) -->
        		<tr>
        		<th>Catégorie</th>
        		<th>Id</th>
        		<th>Titre</th>
        		<th>Texte</th>
        		<th>DateHeure</th>
        		<th></th>
        		<th></th>
        		<th></th>
        		</tr>

        	<tbody class="corps">
        	<?php 
        	$sql = $bd_articles->get_articlesVisible(5);
        	while ($donnees = mysql_fetch_array($sql)) //Affiche tous les articles
        	{
        		$libCategorie = $bd_categories->get_libelle($donnees['article_idCategorie']);
        	?>		
        	<tr id="listItem_<?php echo $donnees['article_id'];?>" class="
        	<?php 
        	if(!$donnees['article_visible']){
        		echo " invisible"; //Class spéciale pour ligne invisible
        	} ?>
        	">
        	<!-- CELLULES données (TD) -->
        	<td><?php echo $libCategorie ?></td>
        	<td><?php echo $donnees['article_id']; ?></td>
        	<td><?php echo $donnees['article_titre']; ?></td>
        	<td><?php echo $donnees['article_texte']; ?></td>
        	<td><?php echo $donnees['article_date']; ?></td>

        	<!-- MODIFIER -->
        	<td><a><img src="../../images/modifier.png" alt="modifier" title="modifier" border=""/></a></td>
        	<!-- SUPPRIMER -->
        	<td><a href="#"  onclick='traitement("supArticle",<?php echo $donnees['article_id'] . ",\"".  $donnees['article_date']."\"". ",\"".  $donnees['article_titre']."\"";?>)'>
        	<img src="../../images/supprimer.png" alt="supprimer" title="supprimer" border=""/></a></td>
        	<!-- MASQUER -->
        	<td><a href="#" onclick='traitement("masquer",<?php echo $donnees['article_id'] . ",\"".  $donnees['article_date']."\"";?>)'>
        	<img src="../../images/masquer.png" alt="visible" title="visible" border=""/></a></td>
        	</tr>		

        	<?php } ?>
        		</tbody>
        	</table>

        	<input type='hidden' name='sauvegarde' id='sauvegarde' value='article' />
        	<input type="hidden" name="rech" id="rech" value="null" />

        	<?php 
        	//Affiche une option pour voir plus de ligne si nécessaire
        	$nbrLignes = $bd_articles->get_nombre_articlesVisible();
        	if($nbrLignes>5){ ?>
        		<a href="#" onclick='choixArticle("plus")'>+ voir plus</A>
        	<?php } ?>
        	</div>  
      </div>
      <div id="contentMenu">
        <div class="titre">MENU</div>

        <a href="../GestionUtilisateurs/GestionUtilisateurs_liste.php"><div class="button">Abonnés</div></a>
        <a href="../GestionCategories/GestionCategories_liste.php"><div class="button">Catégories</div></a>
        <a href="../GestionArticles/GestionArticles_liste.php"><div class="button">Articles</div></a>
      </div>

      <div class="clear"></div>

    </div>
  </div>


	
  <!-- Jquery Framwork -->
	<script type="text/javascript" src="../../javascript/jquery/jquery.js"></script>
	<script type="text/javascript" src="../../javascript/jquery/jquery-ui-1.8.6.js"></script>
	<!-- Script Javascript -->
	<script type="text/javascript" src="GestionArticles.js"></script>
</body> 
</html> 