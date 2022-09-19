<?php
require 'lib/database.php';
require 'lib/debug.php';
require 'models/cocktail.php';
session_start();
/*
01 - créer une vue (phtml) et un controller (php) pour la page detailsCocktail
02 - depuis index.phtml mettre en place des liens pour accéder à la page details
03 - sur cette page nous avons besoin de l'id du cocktail que l'on doit afficher
      -- si l'id n'est pas présent on redirige vers index.php
04 - récupération des informations du cocktail en BDD detailsCocktail()
      -- si on ne récupère rien de la BDD, on redirige sur index.php
05 - Faire une structure html pour afficher toutes les informations du cocktail.
*/

// on vérifie si l'id est présent dans l'url et de type numérique sinon on redirige
if(!isset($_GET['id'])) {
      header('location:index.php');
      exit(); // cette fonction prédéfinie permet de bloquer l'exécution du code à la suite.
}

$infosCocktail = detailsCocktails($_GET['id']);

// echo '<pre>'; var_dump($infosCocktail); echo '</pre>';
if(!$infosCocktail) { // si $infosCocktail == false : l'id ne correspond à aucun cocktail, on redirige
      header('location:index.php');
      exit(); // cette fonction prédéfinie permet de bloquer l'exécution du code à la suite.
}

// si le cocktail est proposer sur cette page alors on affiche que ceux qui n'est affiche pas 
$proposition = propositionFamille($infosCocktail['nomFamille']);
// debugVarDump($detailCocktailsSelonFamille);

$listeIngredients = detailsIngredient($_GET['id']);


include 'templates/detailsCocktail.phtml';