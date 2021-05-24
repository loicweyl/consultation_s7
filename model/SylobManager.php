<?php

namespace LoicW\Blog\Model;
require_once("model/Manager.php");

class SylobManager extends \LoicW\Blog\Model\Manager{
    // Les requêtes sont réalisées depuis le serveur 10.1.23.9

    function getArticle($no_art){
        //// récupère les information de l'article ////
        $data_art = file_get_contents($GLOBALS['serveur_intranet'].'/passerelle_fps/get_bas_art.php?no_art='. $no_art );
        $data_art = json_decode($data_art);

		return $data_art ;
    }

    function getIndice($no_art){
        //// récupère les information de l'article ////
        $data_art = file_get_contents($GLOBALS['serveur_intranet'].'/passerelle_fps/get_bas_indice.php?no_art='. $no_art );
        $data_art = json_decode($data_art);

		return $data_art ;
    }
}


