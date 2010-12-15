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
	<title>Gestion des catégories</title>	
	<link title="css2" type="text/css" rel="stylesheet" media="screen" href="../../css/master.css">
  <link title="css1" type='text/css' rel='stylesheet' media='all' href='dragdrop.css' /> 
</head> 
<body onLoad="load()"> 
  
  <div id="block">
    <div id="head">
      <img src="../../images/logo.png">

    </div>
    <div id="bar">

    </div>
    <div id="sheet">
      <div id="content">
        <!-- Formulaire de saisie pour ajout ou modification d'une catégorie -->
        <div id="divSaisie"> 
        <form method="post" action="#">
          <label for="categorie">Catégorie : </label><input class="InputText" type="text" name="textCategorie" id="textCategorie" />
        	<button type="button" id="btnvalider" onClick="valider()">Valider</button>
          <button type="button" id="btnannuler">Annuler</button>
        	<input type="hidden" name="action" id="action" value="null" />
        	<input type="hidden" name="svgId" id="svgId" value="null" />
        </form>
        </div>

        <br />
        	<div id="infoResult"></div> <!-- Affiche le message de confirmation -->
          
          <div class="clear"></div>
          
        	<div id="lescategories">
        	<table id="tableau" border="2">
        		<!-- RECHERCHE -->
        		<tr><td colspan='3'>Rechercher : <input class="InputSearch" type="text" name="recherche" id="recherche" />
        		<button type='button' id='btnRech' onClick='rechercher()'>Rechercher</button></td>
        		<!-- Ajouter une catégorie -->
        		<td colspan='2'><button type='btnajouter' id='btnajouter' onClick='ajouter()'>Ajouter une catégorie</button></td>
        		</tr>
        		<tr>
        		<!-- Entete -->
        		<th>Libelle</th>
        		<th>Modifier</th>
        		<th>Visibilité</th>
        		<th>Supprimer</th>
        		<th>Valider</th>
        		</tr>

        	<tbody class="corps">
        	<?php 
        	$sql = $bd_categories->get_categories();
        	while ($donnees = mysql_fetch_array($sql))
        	{
        	?>		
        	<tr id="listItem_<?php echo $donnees['categorie_id'];?>" class="
        	<?php
        	if(!$donnees['categorie_validation']){
        		echo " validation"; //Class spéciale si SOUMIS A VALIDATION
        	}elseif(!$donnees['categorie_visible']){
        		echo " masquer";	//Class spéciale si MASQUER
        	}?> ">
        	<td><?php echo $donnees['categorie_libelle']; ?></td>

        	<!-- MODIFIER -->
        	<?php if($donnees['categorie_validation']){ ?>
        		<td><a href="#" onclick='modifier(<?php echo $donnees['categorie_id'] . ",\"".  $donnees['categorie_libelle']."\"";?>)'><img src="../../images/modifier.png" alt="modifier" title="modifier" border=""/></a></td>
        	<?php }else{ echo "<td></td>"; } ?>
        	<!-- MASQUER -->		
        	<?php if($donnees['categorie_validation']){ ?>
        	<td><a href="#" onclick='traitement("masquer",<?php echo $donnees['categorie_id'];?>)'><img src="../../images/masquer.png" alt="masquer" title="masquer" border=""/></a></td>
        	<?php }else{ echo "<td></td>"; } ?>
        	<!-- SUPPRIMER -->
        	<td><a href="#"  onclick='controle("supprimer",<?php echo $donnees['categorie_id'] . ",\"".  $donnees['categorie_libelle']."\"";?>)'>
        	<img src="../../images/supprimer.png" alt="supprimer" title="supprimer" border=""/></a></td>
        	<!-- VALIDER -->
        	<?php if(!$donnees['categorie_validation']){ ?>
        	<td><a href="#" onclick='traitement("valider",<?php echo $donnees['categorie_id'];?>)'><img src="../../images/valider.png" alt="valider" title="valider" border=""/></a></td>
        	<?php }else{ echo "<td></td>";} ?>
        	</tr>			

        	<?php } ?>
        		</tbody>
        	</table>

        	<input type="hidden" name="rech" id="rech" value="null" />
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
	
	<!-- Script Javascript -->
	<script type="text/javascript" src="../../javascript/jquery/jquery.js"></script>
	<script type="text/javascript" src="../../javascript/jquery/jquery-ui-1.8.6.js"></script>
	<script type="text/javascript" src="GestionCategories.js"></script>
</body> 
</html> 