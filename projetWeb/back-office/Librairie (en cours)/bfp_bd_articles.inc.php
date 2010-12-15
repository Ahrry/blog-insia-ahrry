<?php
//#############################################################//
//############# BFP = BIBLIOTHEQUE FONCTIONS PHP  #############//
//#############################################################//

//###############################################################################################//
//############# BD_ARTICLES : Requ�tes pour la table article ####################################//
//###############################################################################################//
//// Historique des mises � jour //////////////////////////////////////////////////////////////////////
// 01/12/10 :   get_articlesVisible : (JB) : Cr�ation
//              get_articlesNonValide : (JB) : Cr�ation
//              get_articlesMasques : (JB) : Cr�ation
//              get_nombre_articlesVisible : (JB) : Cr�ation
//              get_nombre_articlesNonValide : (JB) : Cr�ation
//              get_nombre_articlesMasques : (JB) : Cr�ation
//              get_nombre_articles_categorie : (JB) : Cr�ation
//              supprimer : (JB) : Cr�ation
//              modifier_position : (JB) : Cr�ation
//              valider : (JB) : Cr�ation
//              visible : (JB) : Cr�ation
// 01/12/10 :   creer_article : (BG) : Cr�ation
//		recup_categorie : (BG) : Cr�ation
//		modif_article : (BG) : Cr�ation
///////////////////////////////////////////////////////////////////////////////////////////////////////

final class BD_Articles
{
	//########## PROJECTION ########################################################################//		
		//---- Retourne la liste des articles visible
		public function get_articlesVisible($limit=null,$recherche=null)
		{
			if($recherche==null){
				$sql="SELECT * From articles WHERE article_validation=true AND article_visible=true ";
				$sql.="ORDER BY article_position,article_date";
				if($limit!=null){$sql.=" LIMIT $limit";}	
			}else{			
				$sql="SELECT * From articles,categories WHERE article_idCategorie=categorie_id AND article_validation=true AND article_visible=true ";
				foreach($recherche as $val){
					$sql.=" AND (lower(article_titre) LIKE lower('%".$val."%') OR lower(categorie_libelle) LIKE lower('%".$val."%'))";
				}
				$sql.=" LIMIT $limit";
			}
			return mysql_query($sql);
		}		
		//---- Retourne la liste des articles non valid�s
		public function get_articlesNonValide($limit,$recherche=null)
		{
			if($recherche==null){
				$sql="SELECT * From articles WHERE article_validation=false LIMIT $limit";	
			}else{
				$sql="SELECT * From articles,categories WHERE article_idCategorie=categorie_id AND article_validation=false ";
				foreach($recherche as $val){
					$sql.=" AND (lower(article_titre) LIKE lower('%".$val."%') OR lower(categorie_libelle) LIKE lower('%".$val."%')) ";
				}	
				$sql .= " LIMIT $limit";
			}
			return mysql_query($sql);		
		}			
		//---- Retourne la liste des articles masqu�s
		public function get_articlesMasques($limit,$recherche=null)
		{
			if($recherche==null){
				$sql="SELECT * From articles WHERE article_visible=false LIMIT $limit";	
			}else{
				$sql="SELECT * From articles,categories WHERE article_idCategorie=categorie_id AND article_visible=false ";
				foreach($recherche as $val){
					$sql.=" AND (lower(article_titre) LIKE lower('%".$val."%') OR lower(categorie_libelle) LIKE lower('%".$val."%')) ";
				}
				$sql .= " LIMIT $limit";
			}
			return mysql_query($sql);
		}		
		//---- Retourne le nombre d'article visible et valid�
		public function get_nombre_articlesVisible($recherche=null)
		{
			if($recherche==null){
				$sql="SELECT COUNT(*) FROM articles WHERE article_validation=true AND article_visible=true";
			}else{
				$sql="SELECT COUNT(*) From articles,categories WHERE article_idCategorie=categorie_id AND article_validation=true AND article_visible=true ";
				$sql .= " AND (lower(article_titre) LIKE '%".$recherche."%' OR lower(categorie_libelle) LIKE '%".$recherche."%')";
			}		
			$sql=mysql_query($sql);
			$valeur=mysql_fetch_array($sql);
			return $valeur[0];
		}			
		//---- Retourne le nombre d'article non valid�
		public function get_nombre_articlesNonValide($recherche=null)
		{
			if($recherche==null){
				$sql="SELECT COUNT(*) FROM articles WHERE article_validation=false";	
			}else{
				$sql="SELECT COUNT(*) From articles,categories WHERE article_idCategorie=categorie_id AND article_validation=false ";
				$sql .= "AND (lower(article_titre) LIKE '%".$recherche."%' OR lower(categorie_libelle) LIKE '%".$recherche."%')";
			}
			$sql=mysql_query($sql);
			$valeur=mysql_fetch_array($sql);
			return $valeur[0];
		}		
		//---- Retourne le nombre d'article masqu�s
		public function get_nombre_articlesMasques($recherche=null)
		{
			if($recherche==null){
				$sql="SELECT COUNT(*) FROM articles WHERE article_visible=false";
			}else{
				$sql="SELECT COUNT(*) From articles,categories WHERE article_idCategorie=categorie_id AND article_visible=false ";
				$sql .= " AND (lower(article_titre) LIKE '%".$recherche."%' OR lower(categorie_libelle) LIKE '%".$recherche."%')";
			}
			$sql=mysql_query($sql);
			$valeur=mysql_fetch_array($sql);
			return $valeur[0];
		}		
		//---- Retourne le nombre d'article d'une cat�gorie
		public function get_nombre_articles_categorie($idCategorie)
		{
			$sql="SELECT COUNT(*) FROM articles WHERE article_idCategorie=$idCategorie";			
			$sql=mysql_query($sql);
			$valeur=mysql_fetch_array($sql);
			return $valeur[0];
		}			
	//##############################################################################################//
	
	//########## SUPPRESSION #######################################################################//
		//---- Supprime un article
		public function supprimer($id, $date=null)
		{
			$sql="DELETE FROM articles WHERE article_id='$id'";
			if($date!=null){ $sql.=" AND article_date='$date'";}		
			mysql_query($sql);
		}		
	//##############################################################################################//
	
	//########## MODIFICATION ######################################################################//
		//---- Modifie la position d'un article
		public function modifier_position($position, $id)
		{
			$sql="UPDATE articles SET article_position = $position WHERE article_id = $id AND article_validation=true";
			mysql_query($sql);
		}		
		//---- Valide un article
		public function valider($id, $date)
		{
			$datePublication=date("Y-m-d H:i:s");
			$sql="UPDATE articles SET article_validation=true,article_date='$datePublication' WHERE article_id='$id' AND article_date='$date'";
			mysql_query($sql);
		}	
		//---- Modifie la valeur de visible
		public function visible($id, $date,$visible)
		{
			$sql="UPDATE articles SET article_visible=$visible WHERE article_id='$id' AND article_date='$date'";			
			mysql_query($sql);
		}
		//---- Modifier un article
		public function modif_article($titre, $contenu, $idarticle)
		{
			$date = date('Y-m-d H:i:s');
			$sql="UPDATE articles SET article_titre='$titre', article_texte='$contenu', article_date='$date' WHERE article_id='$idarticle' ";
			mysql_query($sql);
		}
	//##############################################################################################//
	
	//########## CREATION ######################################################################//
		//---- Creer un article
		public function creer_article($titre, $contenu, $categorie)
		{
			$date = date('Y-m-d H:i:s');
			$sql="INSERT INTO articles VALUES('', '$date','$titre','$contenu','0','TRUE','TRUE','5', '$categorie')";
			mysql_query($sql);
		}
		//---- Recup�rer cat�gorie
		public function recup_categorie($categorie)
		{
			$nom_categorie=$categorie;
			$res = mysql_query("SELECT categorie_id FROM categories WHERE categorie_libelle LIKE '$nom_categorie'");
			$categorie=mysql_fetch_array($res);
			$categorie=$categorie["categorie_id"];
			return $categorie;
		}	
}
?>