<?php

function searchForm(){
    // Affichage page de recherche
    require('view/searchFormView.php');
}

function search($no_art){
    // Résultat de la recherche
    $articleManager = new \LoicW\Blog\Model\ArticleManager();

    $title ='Résultat recherche';

    // Remplace * par %
    $no_art = str_replace("*", "%", $no_art);

    // Récupère la liste des articles
    $liste_article = $articleManager->getSearch($no_art) ;


    // Affichage planificateur
    require('view/visualisationView.php');
}
