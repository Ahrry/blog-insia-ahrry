<?php
    {/////////////Initialisation////////////////////////////////////////////////////////////////////////////////////
        include '..\lib-php\bfp.inc.php'; // Librairie BicRWeb
        
        //// Ouverture de la base de donnée
        $xml = simplexml_load_file('config.xml');
        $dbserver = $xml->database1->server;
        $dbbase = $xml->database1->base;
        $dbuser = $xml->database1->user;
        $dbpass = $xml->database1->password;
        $conn=odbc_connect("Driver={SQL Server};Server=".$dbserver.";Database=".$dbbase, $dbuser,$dbpass,SQL_CUR_USE_ODBC); 
    }/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    
    {/////////////Traitement//////////////////////////////////////////////////////////////////////////////////////////
    if (isset($_POST['Valider']))
    {
        if ($_POST['login']!=null && $_POST['motdepasse']!=null){   //On vérifie que les champs ne sont pas vides
            $par['login'] =$_POST['login']; 
            $req=bfp_query_depuis_xml("login-query.xml",'query1',$par);     //On verifie que le login existe 
            $result=bfp_t2d_depuis_odbc($conn, $req, true);
            odbc_close($conn);
            if ($result[0]['nombre_Id'] != 0){
                $req=bfp_query_depuis_xml("login-query.xml",'query3',$par);     //On récupère l'id utilisateur
                $result_Util=bfp_t2d_depuis_odbc($conn, $req, true);
                odbc_close($conn);
                if ($result_Util[0]['Utilisateur_Statut']=='mis' || $_POST['application']!=null){  //Seul le mis ne renseigne pas l'application 
                    $_SESSION['IdUtilisateur']=$result_Util[0]['Utilisateur_Id'];                
                    $par['IdUtil'] =$result_Util[0]['Utilisateur_Id'];
                    
                    if ($result_Util[0]['Utilisateur_Statut']=='mis'){
                        $req=bfp_query_depuis_xml("login-query.xml",'query2',$par);     //On vérifie si le password correspond
                    }
                    else{
                        //on récupère l'id de l'application
                        $par['nom'] = $_POST['application'];
                        $req=bfp_query_depuis_xml("login-query.xml",'query6',$par);     //On récupère l'id utilisateur
                        $result_Appli=bfp_t2d_depuis_odbc($conn, $req, true);
                        odbc_close($conn);
                        $par['IdAppli'] = $result_Appli[0]['Application_Id'];
                        $req=bfp_query_depuis_xml("login-query.xml",'query5',$par);     //On vérifie si le password correspond
                    }                    
                    $result=bfp_t2d_depuis_odbc($conn, $req, true);
                    odbc_close($conn);               
                    
                    if (crypt($_POST['motdepasse'],$result[0]['Acces_Password']) == $result[0]['Acces_Password']){
                        //on mémorise les informations sur l'utilisateur
                        $_SESSION['Login'] = $_POST['login'];                     
                        $_SESSION['IdRole'] = $result[0]['Acces_IdRole'];
                        $_SESSION['IdApplication'] = $result[0]['Acces_IdAppli'];
                        $_SESSION['statut'] = $result[0]['Utilisateur_Statut']; //Autorisation de modifier le password
                        if (isset($_POST['url'])){
                            $_SESSION['url']=$_POST['url'];
                        }
                        else{
                            $_SESSION['url']='';
                        }
                        header('Location: GestionUtilisateurs/GestionUtil_liste.php'); // Redirection du navigateur
                    }
                    else{
                        header('Location: index.php?erreur=1');
                    }
                }
                else{
                    header('Location: index.php?erreur=3');
                }
            }
            else {
                header('Location: index.php?erreur=2');
            }
        }
        else
        {
            header('Location: index.php?erreur=3');
        }
    }
    }/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
?>
