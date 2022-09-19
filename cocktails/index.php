<?php
// require permet comme include d'incorporer le contenu d'un autre fichier dans celui-ci
// difference entre include & require
// En cas d'erreur (fichier non trouvé), include provoque un warning et continu l'execution du code, require provoque une erreur fatale et bloque l'execution du code. 
require 'lib/database.php';
require 'lib/debug.php';
require 'models/cocktail.php';
session_start();

// Rajouter un if else pour appeler tous les cocktails ou uniquement ceux d'une famille 
// $listeCocktails = listerCocktails($_GET['famille']);

if (empty($_GET['famille'])) {
    $listeCocktails = listerCocktails();

    if (count($listeCocktails) < 1) { // si on ne recupere aucune ligne de le bdd, on apelle tous les cocktails ! 
        $listeCocktails = listerCocktails();
    }
} else {
    $listeCocktails = listerCocktailsSelonFamille($_GET['famille']);
}

// recuperation du contenu de la page via la fonction provenant de Models
// $listeCocktails = listerCocktails();

// echo '<pre>'; print_r($listeCocktails); echo '</pre>';


// on recupere la liste des familles ainsi que le nb de cocktails pour l'affichage dans le bouton filtre
$NbDeCocktailParFamilles = NbDeCocktailParFamilles();




include 'templates/index.phtml';
