<?php include ('init.php'); ?>
<html>
<head>
<title> Formulaire</title>

<script type="text/javascript" src="jquerry.js"></script>
<script type="text/javascript">
$(function(){
$("#createcategorie").submit(function(){
categorie = $(this).find("input[id=categorie]").val();
$.post("verif.php", {categorie: categorie}, function(data){
alert(data);
});
return false;
});
});
</script>



</head>

<body>
<form method="post" action="validcreacategorie.php" id="createcategorie">

Catégorie : <input type="text" name="categorie" id="categorie" size="12">
<input type="submit" value="OK">
</form>


</body>
</html>