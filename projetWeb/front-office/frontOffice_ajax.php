<?php
	include('../init.php');
	//---- Connexion à la base de données
	$mysql->bfp_connexion('../config.xml');	


	if(isset($_POST['action']) && !empty($_POST['action'])){
		if($_POST['action']=="supprimer"){
			//récupère l'id de la catégorie
			$sql = $bd_articles->get_article($_POST['id']);
			$donnees = mysql_fetch_array($sql);
			$idCat = $donnees['article_idCategorie'];
			
			$bd_articles->supprimer($_POST['id']);					
			//--> suppression en cascade des commentaires
			?>
			<div class="titre">Articles</div>
			<?php 			
			$sql = $bd_articles->get_articlesVisible_categorie($idCat);
			while ($donnees = mysql_fetch_array($sql)) //Affiche tous les articles
			{	
				if(strlen($donnees['article_titre']) > 17){
					$titre = substr($donnees['article_titre'],0,17) ."..";
				}else{
					$titre = $donnees['article_titre'];
				}
				echo "<a href='#' onClick='afficher(\"article\",{$donnees['article_id']})'><div class='button'>$titre</div></a>";
			} 
		}elseif($_POST['action']=="listeArticles"){ 
			?>
			<div class="titre">Articles</div>
			<?php 
			$sql = $bd_articles->get_articlesVisible_categorie($_POST['id']);
			while ($donnees = mysql_fetch_array($sql)) //Affiche tous les articles
			{	
				if(strlen($donnees['article_titre']) > 17){
					$titre = substr($donnees['article_titre'],0,17) ."..";
				}else{
					$titre = $donnees['article_titre'];
				}
				echo "<a href='#' onClick='afficher(\"article\",{$donnees['article_id']})'><div class='button'>$titre</div></a>";
			} 
		}elseif($_POST['action']=="article"){
			$sql = $bd_articles->get_article($_POST['id']);
			$donnees = mysql_fetch_array($sql);
		
			
			echo "<div id='titreArticle'>";
				echo $donnees['article_titre'];
			echo "</div>";
	  
	        echo "<div id='texteArticle'>";
				echo $donnees['article_texte'];
			echo "</div>";
			
			
			if($_SESSION['type']==2){ //Si administrateur
				?>
				<!-- Modifier -->
				<a href="#"  onclick='traitement()'>
				<img src="../images/modifier.png" alt="supprimer" title="supprimer" border=""/></a>
				<!-- Supprimer -->
				<a href="#"  onclick='afficher("supprimer",<?php echo $_POST['id'];?>)'>
				<img src="../images/supprimer.png" alt="supprimer" title="supprimer" border=""/></a>
				<?php 
			}elseif($_SESSION['type']==1 && $donnees['article_idUtilisateur']==$_SESSION['id']){ //Si abonné qui à créer l'article
				?>
				<!-- Modifier -->
				<a href="#"  onclick='traitement()'>
				<img src="../images/modifier.png" alt="supprimer" title="supprimer" border=""/></a>
				<?php
			}
		}
	}
		function afficherArticles(){

	}