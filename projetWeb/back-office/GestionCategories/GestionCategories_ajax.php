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

	if(isset($_POST['controle'])){
		if($_POST['controle']=="supprimer"){
			$nbArticle = $bd_articles->get_nombre_articles_categorie($_POST['id']);
			if($nbArticle>0){
				echo "Suppression impossible! la catégorie contient des articles";
			}else{
				echo "<script>traitement('supprimer',".$_POST['id'].",'".$_POST['valeur']."');</script>";
			}
		}elseif($_POST['controle']=="ajouter" || $_POST['controle']=="modifier"){
			if(!isset($_POST['valeur']) || empty($_POST['valeur'])){
				echo "La saisie du champs est obligatoire";
			}else{
				//---- Contrôle si le libelle exite 		
				$nbCategorie = $bd_categories->get_nombre_categorie_libelle($_POST['valeur'],$_POST['id']);
				if ($nbCategorie>0){
					if($_POST['controle']=="ajouter"){ echo "Ajout"; }else{ echo "Modification";}
					echo " impossible! le libellé saisie existe déjà.";
				}else{
					echo "<script>traitement('".$_POST['controle']."',".$_POST['id'].",'".$_POST['valeur']."');</script>";
				}
			}
		}
	}elseif(isset($_POST['listItem'])){  //modification de la position
		// foreach ($_POST['listItem'] as $position => $item) : 
		  // $sql[] = "UPDATE `table` SET `position` = $position WHERE `id` = $item"; 
		// endforeach; 
		// print_r ($sql);
		//on récupère la position des lignes
		foreach ($_POST['listItem'] as $cle => $id){ 
			$position=$cle+1;
			$bd_categories->modifier_position($position,$id);
		}
		echo "Modification effectuée";
	}
	else{
		//Action de traitement MODIFIER/SUPPRIMER
		if(isset($_POST['action'])){
			$id=$_POST['id'];
			if($_POST['action']=="valider"){ //---- Valider une catégorie	
				$bd_categories->valider($id);		
			}elseif($_POST['action']=="supprimer"){ //---- Supprimer une catégorie 
				$bd_categories->supprimer($id);
			}elseif($_POST['action']=="masquer"){	//---- Masque une catégorie
				$bd_categories->visible($id);
			}elseif($_POST['action']=="ajouter"){	//---- Ajouter une catégorie
				$valeur=mysql_real_escape_string(addslashes($_POST['valeur'])); 
				$bd_categories->ajouter($valeur,"true",$_SESSION['id']);
			}elseif($_POST['action']=="modifier"){	//---- Modifier une catégorie
				$valeur=mysql_real_escape_string(addslashes($_POST['valeur'])); 
				$bd_categories->modifier($id,$valeur);
			}
		}
		if(isset($_POST['action']) && $_POST['action']=="recherche"){
			$valRech= $_POST['valeur'];
			$tabRech = explode (" ", $_POST['valeur']); //récupère le ou les mots recherchés		
			$sql = $bd_categories->get_categories($tabRech);
		}else{
			$sql = $bd_categories->get_categories();	
		}
	
		?>		
		<table id="tableau" border="2">
			<tr><td colspan='3'>Rechercher : <input class="InputSearch" type="text" name="recherche" id="recherche" 
			<?php if(isset($valRech)){
				echo " value='".$valRech."'";
			} ?> />
			<button type='button' id='btnRech' onClick='rechercher()'>Rechercher</button></td>
			<td colspan='2'><button type='btnajouter' id='btnajouter' onClick='ajouter()'>Ajouter une catégorie</button></td>
			</tr>
			<th>Libelle</th>
			<th>Modifier</th>
			<th>Visibilité</th>
			<th>Supprimer</th>
			<th>Valider</th>
			</tr>

		<tbody class="corps">
		<?php 
		while ($donnees = mysql_fetch_array($sql))
		{
		?>		
		<tr id="listItem_<?php echo $donnees['categorie_id'];?>" class="
		<?php
		if(!$donnees['categorie_validation']){
			echo " validation";
		}elseif(!$donnees['categorie_visible']){
			echo " masquer";
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
		
		<script type="text/javascript" src="GestionCategories.js"></script>

	<?php } ?>