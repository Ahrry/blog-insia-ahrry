<?php
	include('../../init.php'); //Initialisation
	$mysql->bfp_connexion('../../config.xml');
?>
<?xml version='1.0' encoding='UTF-8'?>
<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Strict//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd'>
<html xmlns='http://www.w3.org/1999/xhtml'>
<head>
	<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
	<title>Creation Commentaire</title>	
	<link title="css1" type='text/css' rel='stylesheet' media='all' href='dragdrop.css' />
	<script type="text/javascript" src="jquery.js"></script>
	<script type="text/javascript">
		$(function(){
		  $("#createcomm").submit(function(){
		  commentaire = $(this).find("textarea[name=commentaire]").val();
		  $.post("CreationCommentaires_liste2.php", {commentaire: commentaire}, function(data){
			alert(data);
		  });
		  return false;
		  });
		
		});
	</script> 
</head>
<body>


	<form method="post" action="CreationCommentaires_liste2.php" id="createcomm">
	<table>
		<tbody>
		<tr>
			<td>Commentaire :<br /><textarea name="commentaire" id="commentaire"></textarea><br /></td>
		</tr>
		<tr>
			<td colspan='2'><button type="submit" colspan="2">Ajouter</button></td>
		</tr>
		</tbody>
            
	</table>
</body> 
</html>
