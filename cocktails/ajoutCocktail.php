<?php
// require permet comme include d'incorporer le contenu d'un autre fichier dans celui-ci
// difference entre include & require
// En cas d'erreur (fichier non trouvé), include provoque un warning et continu l'execution du code, require provoque une erreur fatale et bloque l'execution du code. 
require 'lib/database.php';
require 'lib/debug.php';
require 'models/cocktail.php';
session_start();


// Mettre en place un formulaire  pour enregistrer des cocktails : 
// Champs attendus : 
    // - input type="text" name ="nom"
    // - textarea name="description"
    // - input type="file" name="urlPhoto"
    // - input type="text" name="prixMoyen"
    // - select name="idFamille"
// Les options du select idFamille doivent proposer les familles de la BDD
    // - requete + traitement + foreach pour les affichées
    // <option value="70(id de la famille)">Truc(le nom de la famille de concerné)</option>

    // bouton de validation type="submit"

// Remplir le champ select
// Creer une variable $listeFamille et placer dedans le resultat de le fonction listerFamille()
// ensuite faire un foreach dans le select pour generer les options du select
// <option value="70(id de la famille)">Truc(le nom de la famille de concerné)</option>


// Mettre en place un formulaire permettant de créer une nouvelle famille en BDD
// form method post
// champ: Nom famille (name='nomFamille')
// champ: submit

// Via un if demander si l'information existe dans post
// creer une fonction permettant un INSERT INTO dans la table famille
// si vous rentrer dans le if, il faut declencher l'appel de cette fonction afin de creer la nouvelle famille en BDD
$show = '';
$msgFamille = '';
if(isset($_POST['nomFamille'])){
    $show = 'show';
    // avant l'enregistrement, on verifie si la famille existe deja en BDD
    // requete SELECT, si on obtient une ligne, cette famille existe sinon on l'enregistre
    $_POST['nomFamille'] = trim($_POST['nomFamille']);
    $familleExiste = verifieFamilleExiste($_POST['nomFamille']);
    // debugVarDump($familleExiste);
    // $ajoutFamille = ajoutFamille($_POST['nomFamille']);
    if ($familleExiste > 0) {
        $msgFamille .='<div class="alert alert-danger mb-3">Attention, la famille '. $_POST['nomFamille'].' existe deja. Veuillez vérifier votre saisie.</div>'; 
    }
    else{
        $ajoutFamille = ajoutFamille($_POST['nomFamille']);
        $msgFamille .='<div class="alert alert-success mb-3">La famille <b>'. $_POST['nomFamille'].'</b>  à bien été ajoutée.</div>'; 
    }
}


// on recupere la liste de toutes les familles
$ListerFamille = ListerFamille();

// debugPrintR($_POST);
// debugPrintR($_FILES);

// echo '<pre>'; print_r($_POST); echo'</pre>';
// echo '<pre>'; print_r($_FILES); echo'</pre>';

$msg = ''; // variable destinée a afficher les message utilisateur (voir dans .phtml) 
$msg_ok = '<div class="alert alert-success mb-3">Votre enregistrement à bien été pris en charge.</div>';

// Est ce que le formulaire à été validé :
    if(isset($_POST['nom']) 
    && isset($_POST['description']) 
    && isset($_POST['prixMoyen']) 
    && isset($_POST['anneeConception'])) {

    // on applique un trim() sur toutes les valeurs dans $_POST
    foreach ($_POST as $indice => $valeur){
        $_POST[$indice] = trim($valeur);
    }

    // variable de controle pour tester dans un deuxieme temps pour savoir si on peut lancer l'enregistrement.
    $erreur = false;

    // controle sur la famille car obligatoire
    if (empty($_POST['idFamille'])) {
        $msg .= '<div class="alert alert-danger mb-3">Attention, il est obligatoire de choisir une famille</div>';
        // cas d'erreur
        $erreur = true;
    }


    // 2 controle à mettre en place :
    // le prixMoyen doit etre numérique !!! sinon message et on bloque l'ajout

    // controle du prix moyen
    $_POST['prixMoyen'] = str_replace(',', '.', $_POST['prixMoyen']);
    // str_replace(ce quon cherche, le remplacement, ou on cheche) 
    if (!is_numeric($_POST['prixMoyen'])) {
        $msg .= '<div class="alert alert-danger mb-3">Attention, le prix moyen doit etre numerique</div>';
        // cas d'erreur
        $erreur = true;
    }
        // option : gestion de la virgule (str_replace)
    // l'annee conception doit etre numerique et doit avoir 4 chiffres !

    // controle année conception
    if (!is_numeric($_POST['anneeConception']) || iconv_strlen($_POST['anneeConception']) != 4) {
        $msg .= '<div class="alert alert-danger mb-3">Attention, l\'annee doit etre numerique (AAAA) </div>';
        // cas d'erreur
        $erreur = true;

    }

    // controle sur le nom, obligatoire
    if (empty($_POST['nom'])) {
        echo '<div class="alert alert-danger mb-3">Attention, le nom du cocktail est obligatoire</div>';
        // cas d'erreur
        $erreur = true;
  }

// 1ere partie gestion de l'image
    $urlPhoto = ''; // on cree une variable vide pour la requete sql et si une photo à été chargé, on la placera dans cette varibale
    // Pour les PJ (input type="file"), on les retouvera dans la superglobale $_FILES
    // $_FILES est un tableau array multidimensionnel

    // debugPrintR($_FILES);
    /*
    Array
    (
        [urlPhoto] => Array
            (
                [name] => 
                [type] => 
                [tmp_name] => 
                [error] => 4
                [size] => 0
            )

    )
*/

// On verifie si une photo à bien été chargée
// if($_FILES['urlPhoto']['error'] == UPLOAD_ERR_OK)
    if (!empty($_FILES['urlPhoto']['name'])) {
        // on recupere l'extension de la photo pour pouvoir controler le format 
        // strrchr() // fonction predefinie qui permet de decouper la chaine en partant de la fin selon un caractere fourni en argument
        // Nous demandons decouper le nom de l'image jusqu'au point
        $extension = strrchr($_FILES['urlPhoto']['name'], '.');
        // debugPrintR($extension);
        // exemple : on charge une image truc.png => on recupere .png
        // on enleve le point
        $extension = substr($extension, 1); // substr() permet de decouper une chaine de caractere
        // substr(la_chaine_a_decouper, position_depart);
        // debugPrintR($extension);

        // on force la chaine à etre en minuscule : strtolower() ou strtoupper() pour majuscule
        $extension = strtolower($extension);
        // debugPrintR($extension);

        // on cree un tableau array contenant les extensions acceptées :
        $extensionValide =array('jpg', 'jpeg', 'webp', 'png', 'gif');

        // in_array() permet de verifier si le premier argument fait partie d'une des valeurs d'un tableau fourni en deuxieme argument. (true/false)
        if (in_array($extension, $extensionValide)) {
            // extension ok ! on peut enregistrer l'image
            // on renomme le nom de l'image car si on enregistre une image du même nom qu'une autre deja presente, l'ancienne serait ecrasée par la nouvelle
            // uniqueid() retourne un identifiant unique basé sur l'horodatage, sous la forme d'une chaîne de caractères.
            // uniqueid() fonction predefinie nous renvoyant un chiffre basé sur les micro seconde
            $urlPhoto = uniqid() . '-' . $_FILES['urlPhoto']['name'];
            // debugPrintR($urlPhoto) ;


            // Pour l'enregistrement de l'image sur le serveur, nous avons besoin du chemin racine serveur.
            // __DIR__ est une constante magique nous renvoyant le chemin racine serveur jusqu'au dossier contenant le fichier.

            $destination = __DIR__ . '/assets/images/cocktails/' . $urlPhoto;

            // move_uploaded_file() permet d'enregistrer un fichier depuis un emplacement (1er argument) vers un autre (2eme argument)
            // $_FILES['urlPhoto']['tmp_name'] correspond à l'emplacement où le fichier est conservé temporairement à la suite de la validation du formulaire 
            move_uploaded_file($_FILES['urlPhoto']['tmp_name'], $destination);
        } else {
            // .= permet de rajouter sans ecraser
            $msg .= '<div class="alert alert-danger mb-3">Attention, l\'extension de l\'image n\'est pas valide. <br> Extension autorisées : jpg / jpeg / webp / png / gif</div>';
            // cas d'erreur
            $erreur = true;

        } // FIN VERIF EXTENSION
    
    
    } // FIN PHOTO CHARGEE
    
    
    // 2eme partie recuperation des données pour enregistrement 

    // verification si il y a une erreur
    if ($erreur == false) {
        // on rajoute le mois et jour sur l'annee car champ date en BDD
        $anneeConception = $_POST['anneeConception'] . '-01-01';

        AjoutCocktails($_POST['nom'], $_POST['description'], $urlPhoto, $anneeConception, 
        $_POST['prixMoyen'], $_POST['idFamille']);
        
        echo $msg_ok;
    }

} // FIN DES ISSET POST

// Ajout nouveau ingredient
$show1 = '';
$msgIngredient = '';
if(isset($_POST['nomIngredient'])){
    $show1 = 'show';
    // avant l'enregistrement, on verifie si la famille existe deja en BDD
    // requete SELECT, si on obtient une ligne, cette famille existe sinon on l'enregistre
    $_POST['nomIngredient'] = trim($_POST['nomIngredient']);
    $IngredientExiste = verifieIngredientExiste($_POST['nomIngredient']);
    // debugVarDump($familleExiste);
    // $ajoutFamille = ajoutFamille($_POST['nomIngredient']);
    if ($IngredientExiste > 0) {
        $msgIngredient .='<div class="alert alert-danger mb-3">Attention, L\'ingredient '. $_POST['nomIngredient'].' existe deja. Veuillez vérifier votre saisie.</div>'; 
    }
    else{
        ajoutIngredient($_POST['nomIngredient']);
        $msgIngredient .='<div class="alert alert-success mb-3">L\'ingredient '. $_POST['nomIngredient'].' à bien été ajoutée.</div>'; 
    }
}

// Recuperation des cocktails et des ingredients pour le formulaire affecter ingredients

$listeCocktails = listerCocktails();
$listeIngredients = listerIngredients();


// Enregistrement relation cocktail ingredient
$msgRelation = '';
$show3 = '';

if(isset($_POST['idCocktail']) && isset($_POST['idIngredient'])) {
    $show3 = 'show';
    $_POST['idCocktail'] = trim($_POST['idCocktail']);
    $_POST['idIngredient'] = trim($_POST['idIngredient']);

    $verifRelation = verifieRelationCocktailIngredient($_POST['idIngredient'], $_POST['idCocktail'],);

    if ($verifRelation > 0) {
        $msgRelation .= '<div class="alert alert-danger mb-3">Attention, cette relation est deja présente. Veuillez vérifier votre saisie.</div>';
    } else {
        ajouterRelationCocktailIngredient($_POST['idIngredient'],$_POST['idCocktail']);
        $msgRelation .= '<div class="alert alert-success mb-3"> La relation a bien été ajouté dans la base de donnée </div>';
    }
}

include 'templates/ajoutCocktail.phtml';
