<?php

namespace LoicW\Blog\Model;
require_once("model/Manager.php");

class DossierManager extends \LoicW\Blog\Model\Manager{

    function getDossiers(){
        //// Remonte la liste des dossiers dans la table plan_dossier ////
        $db = $this->dbConnect();
        $req= $db->prepare("SELECT plan_dossier.id,nom,chemin_serveur, chemin,couleur,ordre, last_update, nb_files, update_time, plan_frequence.name, plan_frequence.id AS freq_id
                             FROM plan_dossier LEFT JOIN plan_frequence ON plan_dossier.frequence = plan_frequence.id
                             WHERE visible = 1
                             ORDER BY ordre") ;
        $req->execute();
		return $req ;

        // Fermeture BDD
        $db = null ;
    }

    function getDossiersFrequence($frequence){
        //// Remonte la liste des dossiers dans la table plan_dossier ////
        $db = $this->dbConnect();
        $req= $db->prepare("SELECT plan_dossier.id,nom,chemin_serveur, chemin,couleur,ordre, last_update, nb_files, update_time, plan_frequence.name
                             FROM plan_dossier LEFT JOIN plan_frequence ON plan_dossier.frequence = plan_frequence.id
                             WHERE visible = 1 AND frequence = :frequence
                             ORDER BY ordre") ;
        $req->execute(array('frequence' => $frequence));
		return $req ;

        // Fermeture BDD
        $db = null ;
    }

    function saveLastUpdate($dossier_id,$update_time,$nb_files) {
        //// Met à jour la date de la dernière mise à jour du dossier ////
        $db = $this->dbConnect();
        $req= $db->prepare("UPDATE plan_dossier
                             SET last_update = NOW(), nb_files = :nb_files, update_time = :update_time
                             WHERE id = :dossier_id") ;
        $req->execute(array('dossier_id' => $dossier_id, 'nb_files' => $nb_files, 'update_time' => $update_time));
		return $req ;

        // Fermeture BDD
        $db = null ;
    }
}
