<?php
//#############################################################//
//############# BFP = BIBLIOTHEQUE FONCTIONS PHP  #############//
//#############################################################//

//###############################################################################################//
//############# BD_COMMENTAIRES : Requ�tes pour la table commentaires ###########################//
//###############################################################################################//
//// Historique des mises � jour //////////////////////////////////////////////////////////////////////
// 01/12/10 :   supprimer_commentaire_Article : (JB) : Cr�ation
// 10/12/10 :	creer_commentaire : (BG) : Cr�ation
///////////////////////////////////////////////////////////////////////////////////////////////////////

final class BD_Commentaires
{
	//########## PROJECTION ########################################################################//
		
	//##############################################################################################//
	
	//########## SUPPRESSION #######################################################################//
	//---- Supprime un commentaire � partir d'un article
	public function supprimer_commentaire_Article($idArticle)
	{
		$sql="DELETE FROM commentaires WHERE commentaire_idArticle='$idArticle'";					
		mysql_query($sql);
	}
	//##############################################################################################//
	
	//########## MODIFICATION ######################################################################//
		
	//##############################################################################################//
	
	//########## CREATION ######################################################################//
	//---- Creer un article
	
	public function creer_commentaire($commentaire, $article)
	{
		$date = date('Y-m-d H:i:s');
		$sql="INSERT INTO commentaires VALUES('', '$date','$commentaire','$article','5')";
		mysql_query($sql);
	}
}
?>