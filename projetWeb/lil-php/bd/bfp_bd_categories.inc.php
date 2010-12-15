<?php
//#############################################################//
//############# BFP = BIBLIOTHEQUE FONCTIONS PHP  #############//
//#############################################################//

//###############################################################################################//
//############# BD_CATEGORIES : Requêtes pour la table categories ###############################//
//###############################################################################################//
//// Historique des mises à jour //////////////////////////////////////////////////////////////////////
// 01/12/10 :   get_categories : (JB) : Création
//              get_visible : (JB) : Création
//              supprimer : (JB) : Création
//              valider : (JB) : Création
//              visible : (JB) : Création
// 02/12/10 :   ajouter : (JB) : Création
//              modifier : (JB) : Création
//              modifier_position : (JB) : Création
//              get_libelle : (JB) : Création
// 11/12/10     get_categories_visible : (JB) : Création
///////////////////////////////////////////////////////////////////////////////////////////////////////

final class BD_Categories
{
	//########## PROJECTION ########################################################################//
		//---- Retourne la liste des catégories
		public function get_categories($recherche=null)
		{
			if($recherche==null){
				$sql="SELECT * From categories ORDER BY categorie_position, categorie_validation, categorie_date DESC";
			}else{					
				$sql="SELECT * From categories WHERE lower(categorie_libelle) LIKE lower('%".$recherche[0]."%') ";
				for($index=1; $index<count($recherche);$index++){
					$sql.="AND lower(categorie_libelle) LIKE lower('%".$recherche[$index]."%') ";
				}			
				$sql.="ORDER BY categorie_position, categorie_validation, categorie_date DESC";
			}
			return mysql_query($sql);
		}	
		//---- Retourne la liste des catégories
		public function get_categories_visible()
		{
			$sql="SELECT * From categories WHERE categorie_validation=true AND categorie_visible=true ";
			$sql.= " ORDER BY categorie_position, categorie_validation, categorie_date DESC";
			return mysql_query($sql);
		}	
		//---- Retourne le libelle de la catégorie
		public function get_libelle($id)
		{
			$sql="SELECT categorie_libelle From categories WHERE categorie_id='$id'";
			$sql=mysql_query($sql);
			$valeur=mysql_fetch_array($sql);			
			return $valeur[0];
		}
		//---- Retourne la valeur de visible
		public function get_visible($id)
		{
			$sql="SELECT categorie_visible From categories WHERE categorie_id='$id'";
			$sql=mysql_query($sql);
			$valeur=mysql_fetch_array($sql);			
			return $valeur[0];
		}
		//---- Retourne le nombre de catégorie selon libelle
		public function get_nombre_categorie_libelle($libelle,$id=null)
		{
			$sql="SELECT COUNT(*) FROM categories WHERE lower(categorie_libelle)=lower('$libelle')";
			if($id!=null){ $sql .= " AND categorie_id!='$id'";	} 		
			$sql=mysql_query($sql);
			$valeur=mysql_fetch_array($sql);
			return $valeur[0];
		}	
		
	//##############################################################################################//
	
	//########## SUPPRESSION #######################################################################//
		//---- Supprime une catégorie
		public function supprimer($id)
		{
			$sql="DELETE FROM categories WHERE categorie_id='$id'";	
			mysql_query($sql);
		}	
	//##############################################################################################//
	
	//########## AJOUT #############################################################################//
		//---- Ajouter une catégorie
		public function ajouter($libelle,$valide,$idCreateur)
		{
			$date=date("Y-m-d H:i:s");
			$sql="INSERT INTO `categories` VALUES ('','$date','$libelle',$valide,0,true,$idCreateur);";
			mysql_query($sql);
		}	
	//##############################################################################################//
	
	//########## MODIFICATION ######################################################################//
		//---- modifie une catégorie
		public function modifier($id,$libelle)
		{
			$sql="UPDATE categories SET categorie_libelle='$libelle' WHERE categorie_id='$id'";
			mysql_query($sql);
		}	
		//---- Valide une catégorie
		public function valider($id)
		{
			$sql="UPDATE categories SET categorie_validation=true WHERE categorie_id='$id'";
			mysql_query($sql);
		}	
		//---- Modifie la valeur de visible
		public function visible($id)
		{
			$visibilite=$this->get_visible($id);
			
			if($visibilite){			
				$sql="UPDATE categories SET categorie_visible=false WHERE categorie_id='$id'";	
			}else{
				$sql="UPDATE categories SET categorie_visible=true WHERE categorie_id='$id'";
			}
			mysql_query($sql);
		}
		//---- Modifie la position d'une catégorie
		public function modifier_position($position, $id)
		{
			$sql="UPDATE categories SET categorie_position = $position WHERE categorie_id = $id AND categorie_validation=true";
			mysql_query($sql);
		}		
	//##############################################################################################//
}
?>