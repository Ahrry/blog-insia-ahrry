<?php
//#############################################################//
//############# BFP = BIBLIOTHEQUE FONCTIONS PHP  #############//
//#############################################################//

//###############################################################################################//
//############# BD_UTILISATEURS : Requêtes pour la table utilisateurs ###########################//
//###############################################################################################//
//// Historique des mises à jour //////////////////////////////////////////////////////////////////////
// 05/12/10 :   get_utilisateurs : (JB) : Création
//              get_nombre_utilisateurs : (JB) : Création
//              get_nombre_utilisateursLogin : (JB) : Création
//              get_nombre_utilisateursEmail : (JB) : Création
///////////////////////////////////////////////////////////////////////////////////////////////////////

final class BD_Utilisateurs
{
	//########## PROJECTION ########################################################################//
		//---- Retourne la liste des utilisateurs abonnés
		public function get_utilisateurs($recherche=null)
		{
			if($recherche==null){
				$sql="SELECT * From utilisateurs WHERE utilisateur_type=1";
			}else{			
				$sql="SELECT * From utilisateurs WHERE utilisateur_type=1";
				$sql.=" AND (lower(utilisateur_login) LIKE lower('%".$recherche."%') OR lower(utilisateur_email) LIKE lower('%".$recherche."%'))";
			}
			return mysql_query($sql);
			echo $sql;
		}	
		//---- Retourne le nombre d'article visible et validé
		public function get_nombre_utilisateurs($recherche=null)
		{
			if($recherche==null){
				$sql="SELECT COUNT(*) FROM utilisateurs WHERE utilisateur_type=1";
			}else{
				$sql="SELECT COUNT(*) FROM utilisateurs WHERE utilisateur_type=1";
				$sql.=" AND (lower(utilisateur_login) LIKE lower('%".$recherche."%') OR lower(utilisateur_email) LIKE lower('%".$recherche."%'))";
			}		
			$sql=mysql_query($sql);
			$valeur=mysql_fetch_array($sql);
			return $valeur[0];
		}
		//---- Retourne le nombre d'utilisateur en fonction du login
		public function get_nombre_utilisateursLogin($login,$id=null)
		{
			$sql="SELECT COUNT(*) FROM utilisateurs WHERE lower(utilisateur_login)=lower('".$login."')";
			if($id!=null){$sql.=" AND utilisateur_id!=$id";}
			$sql=mysql_query($sql);
			$valeur=mysql_fetch_array($sql);
			return $valeur[0];
		}
		//---- Retourne le nombre d'utilisateur en fonction de email
		public function get_nombre_utilisateursEmail($email,$id=null)
		{
			$sql="SELECT COUNT(*) FROM utilisateurs WHERE lower(utilisateur_email)=lower('".$email."')";
			if($id!=null){$sql.=" AND utilisateur_id!=$id";}
			$sql=mysql_query($sql);
			$valeur=mysql_fetch_array($sql);
			return $valeur[0];
		}
	//##############################################################################################//
	
	//########## SUPPRESSION #######################################################################//
		//---- Supprime un utilisateur
		public function supprimer($id)
		{
			$sql="DELETE FROM utilisateurs WHERE utilisateur_id='$id'";	
			mysql_query($sql);
		}	
	//##############################################################################################//
	
	//########## MODIFICATION ######################################################################//
		//---- Modifier un utilisateur
		public function modifier($id,$login,$email)
		{
			$sql="UPDATE utilisateurs SET utilisateur_login='".$login."',utilisateur_email='".$email."' WHERE utilisateur_id='".$id."'";
			mysql_query($sql);
		}
		//---- Modifier un utilisateur
		public function modifier_password($id,$password)
		{
			$sql="UPDATE utilisateurs SET utilisateur_password='".$password."' WHERE utilisateur_id='".$id."'";
			mysql_query($sql);
		}
	//##############################################################################################//
}
?>