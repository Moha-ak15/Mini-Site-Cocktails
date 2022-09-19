<?php 
require 'lib/database.php';
require 'lib/debug.php';
require 'models/cocktail.php';
session_start();
// session_start() permet de creer un fichier de session coté serveur et un cookie coté utilisateur (navigateur)
// cette fonction doit etre executée AVANT le moindre affichage dans la page html sinon : erreur
// c'est une superglobale donc un tableau array
// nous pouvons placer des informations dedans afin de pouvoir les appeler à tout moment 
// $_SESSION

$msg = '';
$msg_ok = '<div class="alert alert-success mb-3">Votre modification à bien été pris en charge.</div>';
// variable de controle pour tester dans un deuxieme temps pour savoir si on peut lancer l'enregistrement.
$erreur = false;


// on verifie si l'id est presente dans l'url et de type numérique sinon on redirige
if(!isset($_GET['id'])){
    header('location:index.php');
    exit(); // va bloquer l'execution du code à la suite
}

$infosCocktail = detailsCocktails($_GET['id']);

// debugPrintR($infosCocktail);

if (!$infosCocktail) { // si $infosCocktail == false : l'id ne correspond à aucun cocktail, on redirige
    header('location:index.php');
    exit();
}

// ici on est sur que l'id existe et surtout que l'on a bien recuperer les informations du cocktail.

// On recupere les familles pour l'affichage dans le select
$listeFamille = ListerFamille();
    
// Declencher l'update :
// verification de l'existence des informations attendues dans $_POST
// appel de la fonction permettant de declencher l'update 
// mettre en place une redirection vers backOffice.php

if(isset($_POST['id']) && isset($_POST['nom']) && isset($_POST['description']) && isset($_POST['prixMoyen']) && isset($_POST['anneeConception']) && isset($_POST['famille'])){
    // famille au lieu de idfamille car dans le .phtml le name est famille
    if (!is_numeric($_POST['prixMoyen'])) {
        $msg .= '<div class="alert alert-danger mb-3">Attention, le prix moyen doit être numerique</div>';
        // cas d'erreur
        $erreur = true;
    }
        // option : gestion de la virgule (str_replace)
    // l'annee conception doit etre numerique et doit avoir 4 chiffres !
    
    if (!is_numeric($_POST['anneeConception'])) {
        $msg .= '<div class="alert alert-danger mb-3">Attention, l\'annee n\'est pas correct, elle doit être numerique</div>';
        // cas d'erreur
        $erreur = true;
    }

    if ($erreur == false) {
    $anneeConception = $_POST['anneeConception'] . '-01-01';

    ModifierCocktails($_POST['id'], $_POST['nom'], $_POST['description'], 
    $_POST['prixMoyen'], $anneeConception, $_POST['famille']);
    // on place un message dans la session afin de pouvoir l'afficher sur la page cible (backOffice.php)
    $_SESSION['message'] = '<div class="alert alert-success">Le cocktail '. $_POST['nom'] .' à bien été modifié </div>';

    header('location:backOffice.php');
    }

}




require 'templates/editionCocktail.phtml';

