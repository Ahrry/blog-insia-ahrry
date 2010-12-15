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

<?php echo "<?xml version='1.0' encoding='UTF-8'?>"; ?>
<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Strict//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd'>
<html xmlns='http://www.w3.org/1999/xhtml'>
<head>
	<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
	<title>Gestion des utilisateurs</title>	
	<link title="css2" type="text/css" rel="stylesheet" media="screen" href="../../css/master.css">
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
        <!-- Formulaire de Modification -->
        <div id="divSaisie">
          <form method="post" action="#" id="modification">
            <label for="login">Login : </label><input class="InputText" type="text" name="login" id="login" /><br />
            <label for="email">Email : </label><input class="InputText" type="text" name="email" id="email" />
            <button type="submit" id="btnvaliderModif">Valider</button>
            <button type="submit" id="btnannulerModif">Annuler</button>
            <input type="hidden" name="svgId" id="svgId" value="null" />
          </form>
        </div>
        
        <div class="clear"></div>

        <!-- Formulaire de suppression -->
        <div id="divSupprimer">
          <form method="post" action="#">
            <span class="txt"><label for="motif">Veuillez saisir le commentaire de justification de cette suppression : </label></span><br />
            <textarea name="motif" id="motif" rows="10" cols="50"></textarea><BR/>  
            <button type="submit" id="btnvaliderSup">Valider</button>
            <button type="submit" id="btnannulerSup">Annuler</button>
            <input type="hidden" name="svgId" id="svgId" value="null" />
            <input type="hidden" name="svgLogin" id="svgLogin" value="null" />
            <input type="hidden" name="svgEmail" id="svgEmail" value="null" />
          </form>
        </div>
        
        <div class="clear"></div>

        <br />
        <div id="infoResult"></div> <!-- Affiche le message de confirmation -->

        <div id="lesutilisateurs">
          <span class="txt">
          <?php if ($bd_utilisateurs->get_nombre_utilisateurs() > 1)
          {
            echo "Actuellement nous avons : " . $bd_utilisateurs->get_nombre_utilisateurs() . " abonnés";
          }
          else
          {
            echo "Actuellement nous avons : " . $bd_utilisateurs->get_nombre_utilisateurs() . " abonné";
          }
          ?>
          </span>
          <table id="tableau">
            <!-- Recherche -->
            <tr><td colspan='3'>Rechercher : <input class="InputSearch" type="text" name="recherche" id="recherche" />
              <button type='button' id='btnRech' onClick='rechercher()'>Rechercher</button></td>
              <!-- Bouton ajouter un abonné -->
              <td colspan='2'><button type='btnajouter' id='btnajouter' onClick='ajouter()'>Ajouter un abonné</button></td>
            </tr>
            <tr>
              <!-- Entete -->
              <th>Login</th>
              <th>Email</th>
              <th>Modifier</th>
              <th>Supprimer</th>
              <th>Réinitialiser</th>
            </tr>
            <?php 
          $sql = $bd_utilisateurs->get_utilisateurs();
          while ($donnees = mysql_fetch_array($sql))
          {
            ?>		
            <tr>
              <td><?php echo stripcslashes($donnees['utilisateur_login']); ?></td>
              <td><?php echo $donnees['utilisateur_email']; ?></td>

              <!-- MODIFIER -->
              <td><a href="#" onclick='modifier(<?php echo $donnees['utilisateur_id'] . ",\"".  $donnees['utilisateur_login']."\"". ",\"".  $donnees['utilisateur_email']."\"";?>)'>
                <img src="../../images/modifier.png" alt="modifier" title="modifier" border=""/></a></td>
                <!-- SUPPRIMER -->
                <td><a href="#"  onclick='supprimer(<?php echo $donnees['utilisateur_id'] . ",\"".  $donnees['utilisateur_login']."\"". ",\"".  $donnees['utilisateur_email']."\"";?>)'>
                  <img src="../../images/supprimer.png" alt="supprimer" title="supprimer" border=""/></a></td>
                  <!-- REINITIALISER -->
                  <td><a href="#" onclick='reinitialiser(<?php echo $donnees['utilisateur_id'] . ",\"".  $donnees['utilisateur_login']."\"". ",\"".  $donnees['utilisateur_email']."\"";?>)'>
                    <img src="../../images/valider.png" alt="valider" title="valider" border=""/></a></td>
                  </tr>			

                  <?php } ?>
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
        <script type="text/javascript" src="GestionUtilisateurs.js"></script>
</body> 
</html> 