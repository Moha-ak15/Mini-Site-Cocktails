<?php
// require permet comme include d'incorporer le contenu d'un autre fichier dans celui-ci
// difference entre include & require
// En cas d'erreur (fichier non trouvé), include provoque un warning et continu l'execution du code, require provoque une erreur fatale et bloque l'execution du code. 
require 'lib/database.php';
require 'lib/debug.php';
require 'models/cocktail.php';
session_start();

$message_ok = '<div class="alert alert-success mb-3">Votre cocktail à bien été supprimer.</div>';

// DELETE
if(isset($_GET['action']) && $_GET['action'] == 'supprimer' && !empty($_GET['id']) && is_numeric($_GET['id'])) {
    // is_numeric() permet de tester si une valeur est numérique quelque soit son type
    // une valeur dans $_GET et $_POST est toujours de type string !!!
    SupprimerCocktails($_GET['id']);

    echo $message_ok;
}


// EXERCICE :
// Creer une fonction sur Models/cocktail.php nommée listerCocktailsBO()
// Cette fonction doit recuperer la liste de tous les cocktails, champs attendus :
    // - id (table cocktail)
    // - nom
    // - urlPhoto
    // - nomFamille
// Recuperer la reponse de cette fonction : $listeCocktails
// Afficher ces informations dans le tableau html sur .phtml (l'image doit etre dans un img src)

 $listerCocktailsBO = listerCocktailsBO() ;

$messageSession = '';
if (!empty($_SESSION['message'])) {
    $messageSession = $_SESSION['message'];
    // on recupere le message et on le supprime de la sesssion pour ne pas le reafficher 
    // pour supprimer un element d'un tableau array : unset
    unset($_SESSION['message']);
}

include 'templates/backOffice.phtml';
