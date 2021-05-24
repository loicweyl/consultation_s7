<?php

namespace LoicW\Blog\Model;
require_once("model/Manager.php");

class ArticleManager extends \LoicW\Blog\Model\Manager{

    function getVisualisation(){
        //// Remonte la liste des articles à afficher ////
        $db = $this->dbConnect();

        $req= $db->prepare("SELECT plan_article.id, plan_article.no_art, plan_article.designation, plan_article.indice, plan_article.niveau, plan_article.plans, plan_dossier.nom AS dossier_path, plan_dossier.couleur AS color
                FROM plan_article LEFT JOIN plan_dossier ON plan_article.dossier = plan_dossier.id") ;

        $req->execute();
		return $req ;

        // Fermeture BDD
        $db = null ;
    }

    function getSearch($no_art){
        //// Remonte la liste des articles à afficher ////
        $db = $this->dbConnect();

        $req= ("SELECT plan_article.id, plan_article.no_art, plan_article.designation, plan_article.indice, plan_article.niveau, plan_article.plans, plan_dossier.nom AS dossier_path, plan_dossier.couleur AS color
        FROM (plan_article LEFT JOIN plan_dossier ON plan_article.dossier = plan_dossier.id)
        WHERE plan_article.no_art LIKE :no_art") ;

        if(((isset($_GET['search'])) && $_GET['search']=='up-to-date') or (!isset($_GET['search']))) {
            $req = $req . " AND plan_article.dossier IN (4, 5, 6, 7, 8, 9, 10, 11, 12, 13)" ;
        }

        $req= $db->prepare($req) ;

        $req->execute(array('no_art' => $no_art));
		return $req ;

        // Fermeture BDD
        $db = null ;
    }

	public function insertArticle($no_art,$designation,$indice,$dossier,$niveau, $plans){
        //// Insertion d'un nouvel article ////
        $db = $this->dbConnect();
		$req = $db->prepare("INSERT INTO plan_article (no_art, designation, indice, dossier, niveau, plans) VALUES (:no_art, :designation, :indice, :dossier, :niveau, :plans)");
		$affectedLines = $req->execute(array('no_art' => $no_art, 'designation' => $designation, 'indice' => $indice, 'dossier' => $dossier, 'niveau' => $niveau, 'plans' => $plans));
		return $affectedLines;

        // Fermeture BDD
        $db = null ;
	}

    function resetPlanArticle(){
        //// Réinitialise la table plan_article ////
        $db = $this->dbConnect();
        $req = $db->prepare("DELETE FROM plan_article") ;
        $req->execute();

        // Fermeture BDD
        $db = null ;
    }

    function resetPlanArticleDossier($dossier_id){
        //// Réinitialise la table plan_article ////
        $db = $this->dbConnect();
        $req = $db->prepare("DELETE FROM plan_article WHERE dossier = :dossier") ;
        $req->execute(array('dossier' => $dossier_id));

        // Fermeture BDD
        $db = null ;
    }


    function getArticlesDisctincts($dossier_id){
        //// Remonte la liste des article dans le dossier $no_dossier, sans doublon ////
        $db = $this->dbConnect();
        $req= $db->prepare("SELECT DISTINCT no_art,dossier
                            FROM plan_plan
                            WHERE dossier = :dossier") ;
        $req->execute(array('dossier' => $dossier_id));
		return $req ;

        // Fermeture BDD
        $db = null ;
    }
}
