<?php
// index.php: appel de tous les cocktails avec le nom de leur famille

function listerCocktails(){
    // connexion a la bdd
    $pdo = connectToDatabase();

    // preparation de la requete :
    $query = $pdo->prepare("
        Select cocktail.id, nom, nomFamille, description, urlPhoto, Year(dateConception) As dateConception, prixMoyen
        From cocktail
        INNER JOIN Famille ON famille.id = cocktail.idFamille
    ");

    // on execute 
    $query->execute();

    // on renvoie les données
    return $query->fetchAll(PDO::FETCH_ASSOC);
}

// index.php: appel de tous les cocktails selon leur famille

function listerCocktailsSelonFamille( $nomFamille ){
    // connexion a la bdd
    $pdo = connectToDatabase();

    // preparation de la requete :
    $query = $pdo->prepare("
        Select cocktail.id, nom, nomFamille, description, urlPhoto, Year(dateConception) As dateConception, prixMoyen
        From cocktail
        INNER JOIN Famille ON famille.id = cocktail.idFamille
        WHERE nomFamille = ?
    ");

    // on execute 
    $query->execute( [$nomFamille] );

    // on renvoie les données
    return $query->fetchAll(PDO::FETCH_ASSOC);
}

// recuperation des familles ayant des cocktails affecte ainsi que le nombre de cocktails par famille

function NbDeCocktailParFamilles(){
    // connexion a la bdd
    $pdo = connectToDatabase();

    // preparation de la requete :
    $query = $pdo->prepare("
        SELECT nomFamille, COUNT(idFamille) AS nombre
        FROM cocktail
        INNER JOIN famille ON idFamille = famille.id
        GROUP BY nomFamille
    ");

    // on execute 
    $query->execute();

    // on renvoie les données
    return $query->fetchAll(PDO::FETCH_ASSOC);
}

// Recuperation la liste de toutes les familles
function ListerFamille(){
    // connexion a la bdd
    $pdo = connectToDatabase();

    // preparation de la requete :
    $query = $pdo->prepare("
        SELECT id, nomFamille
        FROM famille
        ORDER BY nomFamille
    ");

    // on execute 
    $query->execute();

    // on renvoie les données
    return $query->fetchAll(PDO::FETCH_ASSOC);
}


// Enregistrement des cocktails dans la Bdd
function AjoutCocktails($nom, $description, $urlPhoto, $anneeConception, $prixMoyen, $idFamille){
    // connexion a la bdd
    $pdo = connectToDatabase();

    // preparation de la requete :
    $query = $pdo->prepare("
    INSERT INTO Cocktail 
    (nom, description, urlPhoto, dateConception, prixMoyen, idFamille) 
    VALUES (?, ?, ?, ?, ?, ?)
    ");

    // on execute 
    $query->execute(
        [
            $nom, 
            $description, 
            $urlPhoto, 
            $anneeConception, 
            $prixMoyen, 
            $idFamille 
        ]
    );
}

// Recuperer la liste de tous les cocktails
function listerCocktailsBO(){
    $pdo = connectToDatabase();

    $query = $pdo->prepare("
        Select cocktail.id, nom, nomFamille, urlPhoto
        From cocktail
        INNER JOIN Famille ON famille.id = cocktail.idFamille
        Order BY id;
    ");

    $query->execute();

    return $query->fetchAll(PDO::FETCH_ASSOC);
}


// backoffice.php : suppression d'un cocktail
function SupprimerCocktails($id){
    // connexion a la bdd
    $pdo = connectToDatabase();

    // preparation de la requete :
    $query = $pdo->prepare("
    DELETE FROM cocktail WHERE id = ?
    ");

    // on execute 
    $query->execute( [$id] );

// debugPrintR($query->rowCount());
}

// editionCocktail.php : Recuperation des details d'un cocktail
function detailsCocktails($id){
    // connexion a la bdd
    $pdo = connectToDatabase();

    // preparation de la requete :
    $query = $pdo->prepare("
    Select 
        cocktail.id, nom, nomFamille, idFamille, description, urlPhoto, Year(dateConception) As dateConception, prixMoyen
    From cocktail
    INNER JOIN Famille ON famille.id = cocktail.idFamille
    WHERE cocktail.id = ?
    ");

    // on execute 
    $query->execute( [$id] );

    // une seule ligne => un fetch()
    return $query->fetch(PDO::FETCH_ASSOC);

// debugPrintR($query->rowCount());
}

// editionCocktail.php : modification d'un cocktails
function ModifierCocktails($id, $nom, $description, $prixMoyen, $anneeConception, $idFamille){
    // connexion a la bdd
    $pdo = connectToDatabase();

    // preparation de la requete :
    $query = $pdo->prepare("
    UPDATE cocktail SET
        nom = ?,
        description = ?,
        prixMoyen = ?,
        dateConception = ?,
        idFamille = ?
    WHERE id = ?
    ");

    // on execute 
    $query->execute(
        [
            $nom, 
            $description, 
            $prixMoyen, 
            $anneeConception, 
            $idFamille,
            $id
        ]
    );
}

function propositionFamille( $nomFamille ){
    // connexion a la bdd
    $pdo = connectToDatabase();

    // preparation de la requete :
    $query = $pdo->prepare("
        Select nom, cocktail.id, nomFamille, urlPhoto
        From cocktail
        INNER JOIN Famille ON famille.id = cocktail.idFamille
        WHERE nomFamille = ?
    ");

    // on execute 
    $query->execute( [$nomFamille] );

    // on renvoie les données
    return $query->fetchAll(PDO::FETCH_ASSOC);
}



function verifieFamilleExiste($famille){

    $pdo = connectToDatabase();

    $query = $pdo->prepare("
        SELECT *
        FROM famille
        WHERE nomFamille = ?
    ");

    $query->execute([$famille]);

    // rowCount() est une methode presente dans l'objet d'une requete avec PDO(PdoStatement) qui nous renvoie le nombre de ligne dans la reponse
    return $query->rowCount();
}


function ajoutFamille($nom){

    $pdo = connectToDatabase();

    $query = $pdo->prepare("
        INSERT INTO famille (nomFamille)
        VALUES (?)
    ");

    $query->execute([$nom]);
}


function verifieIngredientExiste($Ingredient){

    $pdo = connectToDatabase();

    $query = $pdo->prepare("
        SELECT *
        FROM ingredient
        WHERE nomIngredient = ?
    ");

    $query->execute([$Ingredient]);

    // rowCount() est une methode presente dans l'objet d'une requete avec PDO(PdoStatement) qui nous renvoie le nombre de ligne dans la reponse
    return $query->rowCount();
}

function ajoutIngredient($nomIngredient){

    $pdo = connectToDatabase();

    $query = $pdo->prepare("
        INSERT INTO ingredient (nomIngredient)
        VALUES (?)
    ");

    $query->execute([$nomIngredient]);
}

// AjouterCocktail.php recuperation des ingredients
function listerIngredients(){
    $pdo = connectToDatabase();

    $query = $pdo->prepare("
    SELECT *
    FROM ingredient
    ORDER BY nomIngredient
    ");

    $query->execute();

    return $query->fetchAll(PDO::FETCH_ASSOC);
}

// AjouterCocktail.php Verification relation cocktail ingredient
function verifieRelationCocktailIngredient($idIngredient, $idCocktail){
    $pdo = connectToDatabase();

    $query = $pdo->prepare("
    SELECT * 
    FROM relationCocktailIngredient
    WHERE idIngredient = ?
    AND idCocktail = ? ");

    $query->execute( [$idIngredient, $idCocktail] );

    return $query->rowCount();
}


function ajouterRelationCocktailIngredient($idIngredient, $idCocktail){
    $pdo = connectToDatabase();

    $query = $pdo->prepare("
    INSERT INTO
    relationCocktailIngredient (idIngredient, idCocktail) VALUES (?, ?) ");

    $query->execute( [$idIngredient, $idCocktail] );

    // return $query->rowCount();
}

// detailsCocktail.php : recuperation des ingredients
function detailsIngredient($idCocktail){
    $pdo = connectToDatabase();

    $query = $pdo->prepare("
    SELECT nomIngredient
    FROM ingredient
    INNER JOIN relationCocktailIngredient ON ingredient.id = idingredient
    WHERE idCocktail = ?
    ORDER BY nomIngredient
    ");

    $query->execute( [$idCocktail] );

    return $query->fetchall(PDO::FETCH_ASSOC);
}
