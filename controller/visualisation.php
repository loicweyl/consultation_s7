<?php

// FONCTIONS
function showVisualisateur(){
    $articleManager = new \LoicW\Blog\Model\ArticleManager();

    if (isset($_GET['tb'])){
        if($_GET['tb'] =="all"){
            $liste_article = $articleManager->getVisualisation() ;
            $title ='Visualisateur plan - Tous';
        }
    }
    else{
        $liste_article = $articleManager->getVisualisation() ;
        $title ='Visualisateur plan - Tous';
    }

    // Affichage planificateur
    require('view/visualisationView.php');
}

function showPlan($path,$format,$no_art){

    copy($path, "/var/www/html/visu_plan/public/temp/"  . $no_art . "." . $format) ;

    // Affichage du plan
    header('Location: public/temp/' . $no_art . '.' . $format);
}

