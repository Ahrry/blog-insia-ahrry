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
		if($_POST['controle']=="modifier"){
			if($_POST['login']=='' || $_POST['email']==''){ //controle si un champ est vide
				echo "La saisie de tous les champs est obligatoire";
			}else{
				//controle si le login existe
				$nbLogin = $bd_utilisateurs->get_nombre_utilisateursLogin($_POST['login'],$_POST['id']);
				//---- Contrôle si l'email existe 		
				$nbEmail = $bd_utilisateurs->get_nombre_utilisateursEmail($_POST['email'],$_POST['id']);
				
				if($nbLogin>0){
					echo "Le login est déjà utilisé!";
				}elseif ($nbEmail>0){
					echo "Email est déjà utilisé!";
				}elseif (!preg_match("#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#", $_POST['email'])){
					echo "Adresse email non valide!";
				}else{		
					echo "<script>traitement('modifier',".$_POST['id'].",'".$_POST['login']."','".$_POST['email']."')</script>";
				}
			}
		}elseif($_POST['controle']=="supprimer"){	//envoi email
			if($_POST['motif']==''){ 
				//commentaire par défaut
				$commentaire="Bonjour, l'administrateur du blog2p0 à supprimer votre compte!";
			}else{ $commentaire=$_POST['motif']; }
				//récupère le serveur SMTP
				$xml = simplexml_load_file('../../config.xml');
				$smtp = $xml->smtp;

				$email_param=array('smtp'=>$smtp,
								   'message'=>$commentaire,
							       'objet'=>'Suppression du compte sur le blog 2p0',
							       'email_expediteur'=>'admin@blog.com',
							       'expediteur'=>'admin@blog.com',                  
							       'destinataire'=>$_POST['email'],
							       'mail_reponse'=>'admin@blog.com',    
								);   

			if($email->bfp_envoyer_email($email_param)){
				//"Email envoyé";
				echo "<script>traitement('supprimer',".$_POST['id'].",'".$_POST['login']."','".$_POST['email']."')</script>";
			}
			else{
				//"Erreur Email";
				echo "Erreur lors de l'envoi du motif à l'utilisateur";
			}
		}
	}else{
		//Action de traitement MODIFIER/SUPPRIMER
		if(isset($_POST['action'])){			
			$id=$_POST['id'];
			if($_POST['action']=="supprimer"){ //---- Supprimer un utilisateur 
				$bd_utilisateurs->supprimer($id);
			}elseif($_POST['action']=="modifier"){	//---- Modifier un utilisateur
				$login=mysql_real_escape_string(addslashes($_POST['login'])); 
				$email=mysql_real_escape_string(addslashes($_POST['email'])); 
				$bd_utilisateurs->modifier($id,$login,$email);
			}elseif($_POST['action']=="reinitialiser"){	//---- reinitialiser le pass d'un utilisateur
				//Password aléatoire
			    $password = ""; //init				
				$possibilites = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789@";
				$nbPossibilite = strlen($possibilites);
				
				for($i = 1; $i <= 8; $i++) {					
					$nb = mt_rand(0,($nbPossibilite-1)); //génére un nombre aléatoire entre 0 et le nombre de possibilité
					$password.=$possibilites[$nb];
				}
				$pass=crypt($password); //Crypt le password
				$bd_utilisateurs->modifier_password($id,$pass); //enregistre le nouveau password
				
				//envoi le password a l'utilisateur
				$message = "Bonjour ".$_POST['login'].", <BR/> Votre password à été réinitialiser : nouveau = $password";
				//récupère le serveur SMTP
				$xml = simplexml_load_file('../../config.xml');
				$smtp = $xml->smtp;
				
				$email_param=array('smtp'=>$smtp,
								'message'=>$message,
								'objet'=>'Nouveau password',
								'email_expediteur'=>'admin@blog.com',
								'expediteur'=>'admin@blog.com',                  
								'destinataire'=>$_POST['email'],
								'mail_reponse'=>'admin@blog.com',    
							);   

				$email->bfp_envoyer_email($email_param);
			}
		}

		if(isset($_POST['action']) && $_POST['action']=="recherche"){
			$valRech=$_POST['login'];
			$valrecherche=addslashes($_POST['login']);
			$sql = $bd_utilisateurs->get_utilisateurs($valrecherche);
		}else{
			$sql = $bd_utilisateurs->get_utilisateurs();	
		}
		
		?>		 
		<span class="txt">	
		<?php
		if(isset($valRech)){$nbUtil = $bd_utilisateurs->get_nombre_utilisateurs($valRech);}else{$nbUtil = $bd_utilisateurs->get_nombre_utilisateurs();}
		if ($nbUtil > 1)
		{
		  echo "Actuellement nous avons : " . $nbUtil . " abonnés";
		}
		else
		{
		  echo "Actuellement nous avons : " . $nbUtil . " abonné";
		}
		?>
		</span>
		
	<table id="tableau" border="2">
		<tr><td colspan='3'>Rechercher : <input class="InputSearch" type="text" name="recherche" id="recherche" 
		<?php if(isset($valRech)){ echo " value='".$valRech."'"; } ?>/>		
		<button type='button' id='btnRech' onClick='rechercher()'>Rechercher</button></td>
		<td colspan='2'><button type='btnajouter' id='btnajouter' onClick='ajouter()'>Ajouter un abonné</button></td>
		</tr>
		<tr>
		<th>Login</th>
		<th>Email</th>
		<th>Modifier</th>
		<th>Supprimer</th>
		<th>Réinitialiser</th>
		</tr>
	<?php 
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
	<td><a href="#" onclick='traitement("reinitialiser",<?php echo $donnees['utilisateur_id'];?>)'><img src="../../images/valider.png" alt="valider" title="valider" border=""/></a></td>
	</tr>				
	
	<?php } ?>
	</table>
	
	<input type="hidden" name="rech" id="rech" value="null" />
		
	<script type="text/javascript" src="GestionUtilisateurs.js"></script>	

	<?php } ?>