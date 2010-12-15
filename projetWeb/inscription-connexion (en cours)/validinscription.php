<?php

include ('init.php'); 
$mysql->bfp_connexion('../../config.xml');
function VerifierAdresseMail($adresse) 
{ 
   $Syntaxe='#^[\w.-]+@[\w.-]+\.[a-zA-Z]{2,6}$#'; 
   if(preg_match($Syntaxe,$adresse)) 
      return true; 
   else 
     return false; 
}

$pseudo = $_POST['pseudo'];
$mdp = $_POST['mdp'];
$mdp2 = $_POST['mdp2'];
$email = $_POST['email'];
$verifpseudo =mysql_query("SELECT utilisateur_id FROM utilisateurs WHERE utilisateur_login='$pseudo'");
$verifmail =mysql_query("SELECT utilisateur_id FROM utilisateurs WHERE utilisateur_email='$email'");
$valpseudo = mysql_fetch_array($verifpseudo);
$valmail = mysql_fetch_array($verifmail);

if ( $pseudo !=NULL and $mdp!=NULL and $mdp2!=NULL and $email!=NULL )
	{
	if ($valpseudo!=NULL)
		{
		Echo "Ce pseudo existe deja";
		?> <br/><br/><a href="inscription.html">Retour au formulaire d'inscription</a> <?php
		}
	
	if ($mdp != $mdp2)
		{
		echo " Mot de passe incorrect ";
		?><br/><br/><a href="inscription.html">Retour au formulaire d'inscription</a> <?php
		}	
	if(VerifierAdresseMail($email)) 
		{
		if ($valmail!=NULL)
			{
			echo "Cette adresse existe deja";
			?><br/><br/><a href="inscription.html">Retour au formulaire d'inscription</a> <?php
			}
		else 
			{
			$mdp= crypt($mdp) ;
			mysql_query("INSERT INTO utilisateurs VALUES ('', '$pseudo','1', '$email','$mdp' ,'0')");
			$xml = simplexml_load_file('../../config.xml');
			$smtp = $xml->smtp;
	
			$message="blog2P0.php?action=valider&id=$pseudo";

			$email_param=array('smtp'=>$smtp,
					   'message'=>$message,
					   'objet'=>'Validation de votre inscription',
					   'email_expediteur'=>'admin@blog.com',
					   'expediteur'=>'admin@blog.com',                  
					   'destinataire'=>$email,
					   'mail_reponse'=>'admin@blog.com',    
					);   
			if($email->bfp_envoyer_email($email_param)){
				//"Email envoyé";
				echo "Un email vien de vous être envoyer , Vérifiez votre boite mail pour activer définitivement votre compte";
			}
			else{
				//"Erreur Email";
				echo "Erreur Connection";
			}
			}
			
		}
	else 
		{
		echo '<p>Votre adresse e-mail n\'est pas valide.</p>';
		?><br/><br/><a href="inscription.html">Retour au formulaire d'inscription</a> <?php
		}
	}
	
else 
	{
	echo "Merci de remplir tous les champs";
	?><br/><br/><a href="inscription.html">Retour au formulaire d'inscription</a> <?php
	}
?>























