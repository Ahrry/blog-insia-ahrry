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
	

	if(isset($_POST['listItem'])){
		$nbarticles = count($_POST['listItem']);
		$bd_articles->deplacer_position($nbarticles);
		//on récupère la position des lignes
		foreach ($_POST['listItem'] as $cle => $id){ 
			$position=$cle+1;
			$bd_articles->modifier_position($position,$id);
		}
	}
	else{
	//récupère le nombre de ligne du tableau (5 minimum)
	if(isset($_POST['nbLignes'])){if($_POST['nbLignes']<5){$limit = 5;}else{$limit = $_POST['nbLignes'];}}	
	
	//Action de traitement MODIFIER/SUPPRIMER
	if(isset($_POST['action'])){
		$id=$_POST['id'];
		$date=$_POST['date'];
		if($_POST['action']=="valider"){ //---- Valider un article
			$choix="validation";
			//recherche si il sagit d'une modification 
			$nb = $bd_articles->get_nombre_articles($id);
			if ($nb > 1){
				//si modification on désactive l'ancien article
				$bd_articles->desactiver($id,$date);
			}				
			//valide l'article
			$bd_articles->valider($id,$date);			
		}elseif($_POST['action']=="supProposition"){ //---- Supprimer une proposition d'article (nouveau/modification)
			$choix="validation";
			$bd_articles->supprimer($id,$date);
		}elseif($_POST['action']=="supArticle" || $_POST['action']=="supArticleMasque"){  //---- Supprimer un article (article+commentaires)
			if($_POST['action']=="supArticleMasque"){$choix="masquer";}else{$choix="article";}
			$bd_articles->supprimer($id);					
			//--> suppression en cascade des commentaires
		}elseif($_POST['action']=="masquer"){	//---- Masque un article
			$choix="article";	
			$bd_articles->visible($id,$date,'false');
		}elseif($_POST['action']=="visible"){	//---- Rend visible un article
			$choix="masquer";	
			$bd_articles->visible($id,$date,'true');
		}
	}
	
	if(isset($_POST['choix'])){ $choix=$_POST['choix'];}
	
	//Choix d'affichage
	if($choix=='article'){	//---- affiche les articles enregistrés (valider et visible)
		if(!isset($limit)){$limit=5;}
		$sql = $bd_articles->get_articlesVisible($limit);		
	}elseif($choix=='validation'){	//---- affiche les articles à valider
		if(!isset($limit)){$limit=5;}
		$sql = $bd_articles->get_articlesNonValide($limit);
	}elseif($choix=='masquer'){	//---- affiche les articles masqués
		if(!isset($limit)){$limit=5;}
		$sql = $bd_articles->get_articlesMasques($limit);
	}elseif($choix=='plus'){ //---- affiche plus de lignes
		$limit = $limit + 5;
		$choix=$_POST['svg'];
		if($_POST['svg']=='validation'){
			if($_POST['recherche']!='null'){
				$recherche=true;
				$sql = $bd_articles->get_articlesNonValide($limit,$_POST['recherche']);
			}else{
				$sql = $bd_articles->get_articlesNonValide($limit);
			}
		}elseif($_POST['svg']=='article'){
			if($_POST['recherche']!='null'){
				$recherche=true;
				$sql = $bd_articles->get_articlesVisible($limit,$_POST['recherche']);
			}else{
				$sql = $bd_articles->get_articlesVisible($limit);
			}
		}elseif($_POST['svg']=='masquer'){
			if($_POST['recherche']!='null'){
				$recherche=true;
				$sql = $bd_articles->get_articlesMasques($limit,$_POST['recherche']);
			}else{
				$sql = $bd_articles->get_articlesMasques($limit);
			}
		}
	}
	elseif($choix=='recherche'){ //---- Effectue une recherche selon le choix (sur le titre de l'artitre et de sa catégorie)
		$tabRech = explode(" ", $_POST['recherche']);
		$choix=$_POST['svg'];		
		if($choix=="article"){
			$sql = $bd_articles->get_articlesVisible($limit,$tabRech);
		}elseif($choix=="validation"){
			$sql = $bd_articles->get_articlesNonValide($limit,$tabRech);
		}
		elseif($choix=="masquer"){
			$sql = $bd_articles->get_articlesMasques($limit,$tabRech);
		}
		$recherche=true;
	}
	?>	
		
	<table id="tableau" border="2">
		<tr><td colspan='5'>Rechercher : <input class="InputSearch" type="text" name="recherche" id="recherche" 
		<?php //si on effectue une recherche on garde la valeur de la textbox
		if($recherche){ echo " value='{$_POST['recherche']}'";} ?>		
		/>
		<button type='button' id='btnRech'>Rechercher</button></td>
		<td colspan='3'><button type='button' id='AjouterArticle'>Ajouter un article</button></td></tr>
		<tr>
		<th>Catégorie</th>
		<th>Id</th>
		<th>Titre</th>
		<th>Texte</th>
		<th>DateHeure</th>
		<th></th>
		<th></th>
		<?php if($choix=="article"){echo "<th></th>";}?>
		</tr>

	<?php if($choix=="article"){echo "<tbody class='corps'>";}

	while ($donnees = mysql_fetch_array($sql))
	{
		$libCategorie = $bd_categories->get_libelle($donnees['article_idCategorie']);
	?>		
	<tr id="listItem_<?php echo $donnees['article_id'];?>" class="ligneArticle
	<?php
	if(!$donnees['article_visible']){
		echo " invisible";
	} ?>	
	">
	<td><?php echo $libCategorie ?></td>
	<td><?php echo $donnees['article_id']; ?></td>
	<td><?php echo $donnees['article_titre']; ?></td>
	<td><?php echo $donnees['article_texte']; ?></td>
	<td><?php echo $donnees['article_date']; ?></td>
	
	<?php if($choix=='article'){ ?>
		<!-- MODIFIER -->
		<td><img src="../../images/modifier.png" alt="modifier" title="modifier" border=""/></a></td>
		<!-- SUPPRIMER -->
		<td><a href="#"  onclick='traitement("supArticle",<?php echo $donnees['article_id'] . ",\"".  $donnees['article_date']."\"". ",\"".  $donnees['article_titre']."\"";?>)'>
		<img src="../../images/supprimer.png" alt="supprimer" title="supprimer" border=""/></a></td>
		<!-- MASQUER -->
		<td><a href="#" onclick='traitement("masquer",<?php echo $donnees['article_id'] . ",\"".  $donnees['article_date']."\"";?>)'>
		<img src="../../images/masquer.png" alt="visible" title="visible" border=""/></a></td>
		</tr>	
	<?php } elseif($choix=='validation'){ ?>
		<!-- VALIDER -->
		<td><a href="#" onclick='traitement("valider",<?php echo $donnees['article_id'] . ",\"".  $donnees['article_date']."\"";?>)'>
		<img src="../../images/valider.png" alt="valider" title="valider" /></a></td>
		<!-- SUPPRIMER -->
		<td><a href="#"  onclick='traitement("supProposition",<?php echo $donnees['article_id'] . ",\"".  $donnees['article_date']."\"". ",\"".  $donnees['article_titre']."\"";?>)'>
		<img src="../../images/supprimer.png" alt="supprimer" title="supprimer" border=""/></a></td>
	<?php }elseif($choix=='masquer'){ ?>
		<!-- VALIDER -->
		<td><a href="#" onclick='traitement("visible",<?php echo $donnees['article_id'] . ",\"".  $donnees['article_date']."\"";?>)'>
		<img src="../../images/valider.png" alt="valider" title="valider" /></a></td>
		<!-- SUPPRIMER -->
		<td><a href="#"  onclick='traitement("supArticleMasque",<?php echo $donnees['article_id'] . ",\"".  $donnees['article_date']."\"". ",\"".  $donnees['article_titre']."\"";?>)'>
		<img src="../../images/supprimer.png" alt="supprimer" title="supprimer" border=""/></a></td>	
	<?php } ?>
	</tr>		
	
	<?php } 
	if($choix=="article"){echo "</tbody>";}?>
	  
	</table>

	<!-- SAUVEGARDE du choix  -->
	<?php
	if($choix=='plus'){
		$valeurSvg=$_POST['svg'];
	}else{
		$valeurSvg=$choix;
	}
	?>
	<input type="hidden" name="sauvegarde" id="sauvegarde" value="<?php echo $valeurSvg; ?>" />
	
	<!-- SAUVEGARDE si recherche  -->
	<?php
	if($recherche){
		$rech=$_POST['recherche'];
	}else{
		$rech="null";
	}
	?>
	<input type="hidden" name="rech" id="rech" value="<?php echo $rech; ?>" />


	<?php
		//Récupère le nombre d'articles selon le choix(visible/A valider/masquer)
		if($valeurSvg=="article"){
			if($recherche){
				$nbrLignes = $bd_articles->get_nombre_articlesVisible($_POST['recherche']);
			}else{
				$nbrLignes = $bd_articles->get_nombre_articlesVisible();
			}
		}elseif($valeurSvg=="validation"){
			if($recherche){
				$nbrLignes = $bd_articles->get_nombre_articlesNonValide($_POST['recherche']);
			}else{
				$nbrLignes = $bd_articles->get_nombre_articlesNonValide();
			}
		}elseif($valeurSvg=="masquer"){
			if($recherche){
				$nbrLignes = $bd_articles->get_nombre_articlesMasques($_POST['recherche']);
			}else{
				$nbrLignes = $bd_articles->get_nombre_articlesMasques();
			}
		}		
		//Affiche une option pour voir plus de ligne si nécessaire
		if($nbrLignes > $limit){ 	?>		
			<a href="#" onclick='choixArticle("plus")'>+ voir plus</A>
		<?php }
	}?>
	<script type="text/javascript" src="GestionArticles.js"></script>