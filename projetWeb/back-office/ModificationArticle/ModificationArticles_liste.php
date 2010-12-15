<?php
	include('../../init.php'); //Initialisation
	$mysql->bfp_connexion('../../config.xml');
	$idarticle=21;
?>
<?xml version='1.0' encoding='UTF-8'?>
<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Strict//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd'>
<html xmlns='http://www.w3.org/1999/xhtml'>
<head>
	<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
	<title>Creation Article</title>	
	<link title="css1" type='text/css' rel='stylesheet' media='all' href='dragdrop.css' />
	<script type="text/javascript" src="jquery.js"></script>
	<!--<script type="text/javascript">
		$(function(){
		  $("#createarticle").submit(function(){
		  categorie = $(this).find("select[name=categorie]").val(); 
		  titre = $(this).find("textarea[name=titre]").val();
		  contenu = $(this).find("textarea[name=contenu]").val();
		  image = $(this).find("input[name=image]").val();
		  $.post("CreationArticles_liste2.php", {categorie: categorie, titre: titre, contenu: contenu}, function(data){
			alert(data);
		  });
		  return false;
		  });
		
		});
	</script> -->
</head>

<body>
<?php $champ_titre = mysql_query("SELECT article_titre FROM categories WHERE article_id = '$idarticle' " );?>
<?php $champ_texte = mysql_query("SELECT article_texte FROM categories WHERE article_id = '$idarticle' "); ?>
	<form method="post" action="CreationArticles_liste2.php" id="createarticle">
	<table>
		<tbody>
		<tr>
			<!--<td>Categorie :</td>
			<td>
			<select name="categorie" id="categorie">
				<option><?php mysql_query("SELECT article_idCategorie FROM categories" )?></option>	
			<?php
				$res = mysql_query("SELECT * FROM categories" );
				while ($val = mysql_fetch_array($res)){
				echo"<option>". $val["categorie_libelle"]."</option>";	
			}
			?>
			</select>
			</td>-->
		</tr>
		<tr>
			<td>Titre :</td> <td><textarea name="titre" id="titre" >
						<?php 
						echo "$champ_titre";
						?>
					     </textarea></td><br />
		</tr>
		<tr>
			<td>Contenu :</td> <td><textarea name="contenu" id="contenu">
							<?php 
							echo "$champ_texte";
							?>
					       </textarea></td><br />
		</tr>
		<tr>
			<td colspan='2'><button type="submit" colspan="2">Modifier</button></td>
		</tr>
		</tbody>
	</table>
	</form>
</body> 
</html>
