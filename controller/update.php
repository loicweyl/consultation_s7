<?php

// FONCTIONS

function showUpdate(){
    $dossierManager = new \LoicW\Blog\Model\DossierManager();
    // On récupère tous les dossiers
    $liste_dossier = $dossierManager->getDossiers() ;
    require('view/updateView.php');
}

function resetArtPlan(){
    // Vide totalement les tables plan_article et plan_plan
    $articleManager = new \LoicW\Blog\Model\ArticleManager();
    $planManager = new \LoicW\Blog\Model\PlanManager();

    $planManager->resetPlanPlan() ;
    $articleManager->resetPlanArticle() ;

    echo "Tables plan_plan et plan_article vides" ;
}

function update($frequence){
    // Met à jour les dossiers plan_plan et plan_article pour tous les dossiers ayant la frenquence en argumant
    $articleManager = new \LoicW\Blog\Model\ArticleManager();
    $planManager = new \LoicW\Blog\Model\PlanManager();
    $dossierManager = new \LoicW\Blog\Model\DossierManager();
    $sylobManager = new \LoicW\Blog\Model\SylobManager();

    // Récupération des dossiers ayant la frequence souhaitée
    $liste_dossier = $dossierManager->getDossiersFrequence($frequence) ;

    while ($dossier = $liste_dossier->fetch()){
        //// Début du chronomètre ////
        $debut = microtime(true);

        $dossier_id = $dossier['id'] ;
        // Suppression des plans et des articles de ce dossier dans les tables plan_article et plan_plan
        $planManager->resetPlanPlanDossier($dossier_id) ;
        $articleManager->resetPlanArticleDossier($dossier_id) ;

        // Fonction pour mettre à jour la table plan_plan pour un dossier
        $update_plan = update_planplan($dossier) ;

        // Fonction pour mettre à jour la table plan_article pour un dossier
        $update_article = update_planarticle($dossier) ;

        //// Fin du chronomètre ////
        $fin = microtime(true);
        $delai = $fin - $debut;

        // Sauvegarde last_update
        $dossierManager->saveLastUpdate($dossier['id'], $delai, $update_plan['nb_files']) ;

        //// Retour ////
        echo "<h1>Dossier " . $dossier['nom'] . "</h1>" ;
        echo "<p>Chemin : " . $dossier['chemin_serveur'] . "</p>" ;
        echo "<p>Nombre de fichier : " . $update_plan['nb_files'] . " </p>" ;
        echo "<p>Chonomètre : " . $delai . " sec</p>" ;
    }
}

function update_planplan($dossier){
    // Met à jour la table plan_plan pour un dossier

    $planManager = new \LoicW\Blog\Model\PlanManager();

    // Scanne tous les fichiers dans le dossier
    $files = array_diff(scandir($dossier['chemin_serveur']), array('..', '.'));

    foreach($files as $file){
        $file_split = explode(".", $file);

        $chemin  = $dossier['chemin'] . '/' . $file ;
        $no_art = strtoupper($file_split[0]) ;

        // Recherche des fichiers images
        $pos = strpos($no_art, '_IMG') ;

        if (!($pos === false)) {
            $art_split = explode("_IMG", $no_art);
            $no_art = $art_split[0] ;
        }

        if(isset($file_split[1])){
            $format = strtoupper($file_split[1]) ;
            $dossier_id = $dossier['id'] ;

            // Sauvergarde des plans
            if (!($format == "LOG") && !($format == "BAK") && !($format == "ERR") && !($format == "OUT")){
               $plan_id = $planManager->insertPlan($chemin,$no_art,$format,$dossier_id) ;
            }
        }
    }

    $nb_files = sizeof($files) ;
    return array("nb_files" => $nb_files) ;
}

function update_planarticle($dossier){
    // Met à jour la table plan_article pour un dossier

    $articleManager = new \LoicW\Blog\Model\ArticleManager();
    $planManager = new \LoicW\Blog\Model\PlanManager();
    $sylobManager = new \LoicW\Blog\Model\SylobManager();

    $liste_article_distinct = $articleManager->getArticlesDisctincts($dossier['id']) ; // Récupère la liste des articles disctincts pour ce dosssier, dans plan_plan

    while ($article = $liste_article_distinct->fetch()){

        $no_art = str_replace(' ','',$article['no_art']) ;
        $niveau = 10 ;

        // Récupération des plans associés dans la table plan_plan
        $plans = $planManager->getPlans($article['no_art'],$dossier['id']) ;
        $list_plans = [] ;

        while ($plan = $plans->fetch()){
            array_push($list_plans, [$plan['chemin'],$plan['format']]);
        }

        $list_plans_serialize = serialize($list_plans) ;

        // Récupération des informations de Sylob
        $article_sylob = $sylobManager->getArticle($no_art) ;

        if (sizeof($article_sylob)>0){
            $article_sylob = $article_sylob[0] ;
            $designation = skip_accents($article_sylob->design1)  ;
            $indice = $sylobManager->getIndice($no_art) ;

            if (sizeof($indice)>0){
                $indice = $indice[0]->ind_art ;
            }
            else{
                $indice = '' ;
            }
        }
        else{
            $designation = ''  ;
            $indice = ''      ;
        }

        // Insertion dans la table plan_article
        $articleManager->insertArticle($article['no_art'],$designation,$indice,$dossier['id'],$niveau, $list_plans_serialize) ;
    }

    return "ok" ;
}

function clear_temp() {

    $dir = "/var/www/html/visu_plan/public/temp/" ;
     if (is_dir($dir)) { // si le paramètre est un dossier
         $objects = scandir($dir); // on scan le dossier pour récupérer ses objets
         foreach ($objects as $object) { // pour chaque objet
              if ($object != "." && $object != "..") { // si l'objet n'est pas . ou ..
                   if (filetype($dir."/".$object) == "dir") rmdir($dir."/".$object);else unlink($dir."/".$object); // on supprime l'objet
                  }
         }
         reset($objects); // on remet à 0 les objets
         //rmdir($dir); // on supprime le dossier
         }
 }
