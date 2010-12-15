<?php
//#############################################################//
//############# BFP = BIBLIOTHEQUE FONCTIONS PHP  #############//
//#############################################################//

//// Historique des mises à jour //////////////////////////////////////////////////////////////////////
// 05/12/10 :   bfp_envoyer_email : (JB) : Création
///////////////////////////////////////////////////////////////////////////////////////////////////////

final class Email
{
	/*///////////////////// bfp_envoyer_email ///////////////////////////////////
	Envoi un email
	/////////////////////////////////////////////////////////////////////////////
	Recoit : 
		$email_param=array('smtp'=>$smtp, //serveur SMTP
						   'message'=>'toto', ==>texte ou html (ex:'<div text-align: center;>Un Bonjour de toto!</div>')
						   'objet'=>'contenu objet',
						   'email_expediteur'=>'Mis.Verberie@bicworld.com',  ==> une adresse bicworld.com
						   'expediteur'=>'Big.Brother@bicworld.com',                 ==> adresse afficher dans l'entête de l'email (from:)
						   'mail_reponse'=>'jean-baptiste.derome@bicworld.com',
						   'destinataire'=>'jean-baptiste.derome@bicworld.com',
						   'copie'=>'null',         ==>liste d'adresses emails à mettre en copie
						   'copie_cachee'=>'null',  ==>liste d'adresses emails à mettre en copie mais cachées les unes des autres
						   'accuse_reception'=>'jean-baptiste.derome@bicworld.com'             
							);
			// Pour les champs $destinataire / $copie / $copie_cachee, séparer par une virgule s'il y a plusieurs adresses
			
		$pieces_jointes=array("image.jpg","Doc1.doc");    
			 
	Retourne : vrai si réussite; faux sinon
	///////////////////////////////////////////////////////////////////////////*/
	function bfp_envoyer_email($email_param, $pieces_jointes=null){
		//---- traitement des paramètres :
		$smtp=$email_param['smtp'];
		$message=$email_param['message'];
		$objet=$email_param['objet'];
		$email_expediteur=$email_param['email_expediteur'];
		$expediteur=$email_param['expediteur'];
		$destinataire=$email_param['destinataire'];

		ini_set('sendmail_from', $email_expediteur);
		//Paramètres du server SMTP
		ini_set('SMTP',$smtp);   
	  
		$frontiere = "_lafrontiere";  //séparateur

		$headers ="From: <$expediteur> \r\n"; 
		$headers .= "MIME-Version: 1.0\r\nContent-Type: multipart/mixed; boundary=\"$frontiere\"\r\n"; 
		if(isset($email_param['mail_reponse'])){$headers .= 'Reply-To: '.$mail_reponse."\n";}
		$headers .= 'From: <'.$expediteur.'>'."\n"; 
		$headers .= 'Delivered-to: '.$destinataire."\n";
		if (isset($email_param['copie'])) {$headers .= 'Cc: '.$email_param['copie']."\n";} // permet de mettre plusieurs destinataires
		if (isset($email_param['copie_cachee'])){ $headers .= 'Bcc: '.$email_param['copie_cachee']."\n";} // permet de mettre plusieurs destinataires mais de cacher les adresses    
		if (isset($email_param['accuse_reception'])){$headers .= 'Disposition-Notification-To: '.$email_param['accuse_reception']."\n\n";} 
		
		//---- Message texte
		// $corps = "--". $frontiere ."\n";
		// $corps .= "Content-Type: text/plain; charset=ISO-8859-1\r\n\n";
		// $corps .= "Mon message"."\n"; 
		
		//---- Message html
		$corps = "--". $frontiere ."\n";
		$corps .= "Content-Type: text/html; charset=ISO-8859-1\r\n\n";
		$corps .= $message ."\r\n"; 

		//---- pieces jointes 
		if(isset($pieces_jointes)){
			foreach($pieces_jointes as $val){
				$corps .= "--" .$frontiere . "\r\n";      
				
				$fichier=$val;
				$piece_jointe = file_get_contents($fichier); //
				$piece_jointe = chunk_split(base64_encode($piece_jointe)); 
				
				$corps .= "Content-Type: application/msword; name=\"$fichier\"\r\n";
				$corps .= "Content-Transfer-Encoding: base64\r\n";
				$corps .= "Content-Disposition: inline; filename=\"$fichier\"\r\n\r\n";
				$corps .= $piece_jointe."\r\n";
				$corps .= "\r\n\r\n";
			}
		}  

		return mail($destinataire, $objet, $corps, $headers); // Envoi du message
	}
}  
        
{///////ENVOI EMAIL EXEMPLE///////////////////////////////////////////////  

	//---- On récupère le serveur SMTP dans le fichier config
	//$xml = simplexml_load_file('../../config.xml');
	//$smtp = $xml->root->smtp;
          
	// $message="<div text-align: center;>Email numéro</div>";
	
	// ---- Paramètres
	// $email_param=array('smtp'=>$smtp,
						//'message'=>$message,
					   // 'objet'=>'contenu objet',
					   // 'email_expediteur'=>'Mis.Verberie@bicworld.com',
					   // 'expediteur'=>'Big.Brother@bicworld.com',                  
					   // 'destinataire'=>'jean-baptiste.derome@bicworld.com',
					   // 'mail_reponse'=>'jean-baptiste.derome@bicworld.com',
					   // 'zzcopie'=>'',
					   // 'zzcopie_cachee'=>'toto.toto@bicworld.com',
					   // 'zzaccuse_reception'=>'jean-baptiste.derome@bicworld.com'             
						// );   
	
	// ---- Pièces jointes
	// $pieces_jointes=array("image.jpg","Doc1.doc");
	
	// ---- Envoi
	// if(bfp_envoyer_email($email_param,$pieces_jointes)){
		// echo "Email envoyé";
	// }
	// else{
		// echo "Erreur Email";
	// }
}/////////////////////////////////////////////////////////////////////////////
?>
