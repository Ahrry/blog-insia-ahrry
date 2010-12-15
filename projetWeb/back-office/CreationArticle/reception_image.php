<?php
    $ret = false;
    $img_blob = '';
    $img_taille = 0;
    $img_type = '';
    $img_nom = '';
    $taille_max = 250000;
    
    $ret = is_uploaded_file ($_FILES['image']['tmp_name']);
    if ( !$ret )
    {
        echo "probleme";
        return false;
    }
    else
    {
        $img_taille = $_FILES['image']['size'];
        if ($img_taille > $taille_max)
        {
            echo "trop gros";
            return false;
        }
        $img_type = $_FILES['image']['type'];
        $img_nom = $_FILES['image']['name'];
    }
    include('../../init.php');
    $img_blob = file_get_contents ($_FILES['image']['tmp_name']);
    
    $req = "INSERT INTO articles VALUES (    
?>