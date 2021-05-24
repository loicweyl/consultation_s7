<?php

namespace LoicW\Blog\Model;
require_once("model/Manager.php");

class PlanManager extends \LoicW\Blog\Model\Manager{

// ---------------- PLAN ---------------- //

    public function insertPlan($chemin,$no_art,$format,$dossier){
        //// Insertion d'un nouvel article ////
        $db = $this->dbConnect();
		$req = $db->prepare("INSERT INTO plan_plan (chemin, no_art, format, dossier) VALUES (:chemin, :no_art, :format, :dossier)");
		$affectedLines = $req->execute(array('chemin' => $chemin, 'no_art' => $no_art, 'format' => $format, 'dossier' => $dossier));
		return $affectedLines; ;

        // Fermeture BDD
        $db = null ;
	}

    function resetPlanPlan(){
        //// Réinitialise la table plan_plan ////
        $db = $this->dbConnect();
        $req= $db->prepare("DELETE FROM plan_plan") ;
        $req->execute();

        // Fermeture BDD
        $db = null ;
    }


    function resetPlanPlanDossier($dossier_id){
        //// Réinitialise la table plan_plan ////
        $db = $this->dbConnect();
        $req= $db->prepare("DELETE FROM plan_plan WHERE dossier = :dossier") ;
        $req->execute(array('dossier' => $dossier_id));

        // Fermeture BDD
        $db = null ;
    }

    function getPlans($no_art,$dossier){
        //// Remonte la liste des dossiers dans la table plan_dossier ////
        $db = $this->dbConnect();
        $req= $db->prepare("SELECT chemin,format
                             FROM plan_plan
                             WHERE no_art = :no_art AND dossier = :dossier") ;
        $req->execute(array('no_art' => $no_art, 'dossier' => $dossier));
		return $req ;

        // Fermeture BDD
        $db = null ;
    }
}
