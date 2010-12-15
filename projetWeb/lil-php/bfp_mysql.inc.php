<?php
//#############################################################//
//############# BFP = BIBLIOTHEQUE FONCTIONS PHP  #############//
//#############################################################//

//// Historique des mises à jour //////////////////////////////////////////////////////////////////////
// 04/12/10 :   bfp_connexion : (JB) : Création
//              bfp_deconnexion : (JB) : Création
///////////////////////////////////////////////////////////////////////////////////////////////////////

final class Mysql
{	
    //================== bfp_connexion ========================
    // Se connecte a la base de données MySQL
    //=============================================================
    // Recoit : $fichier = fichier de configuration
	//    exemple : mysql_connect("localhost", "root", "");
	// Retourne : true si la connexion à réussit, false sinon
    //=============================================================
	public function bfp_connexion($fichier)
	{
		$xml = simplexml_load_file($fichier);
		$dbserver = $xml->database->server;
		$dbbase = $xml->database->base;
		$dbuser = $xml->database->user;
		$dbpass = $xml->database->password;
		
		$result = mysql_connect($dbserver, $dbuser, $dbpass);
		if ($result) { $result=mysql_select_db($dbbase);}	
		return $result;
	}
	
	//================== bfp_deconnexion ========================
    // Se déconnecte a la base de données MySQL
    //=============================================================
	// Retourne : true si la connexion à réussit, false sinon
    //=============================================================
	public function bfp_deconnexion()
	{
		$result = mysql_close();
		return $result;
	}	
}
?>