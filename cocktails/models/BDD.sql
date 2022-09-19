BDD   

-- Faire une requete pour recuperer les champs suivants :
id du cocktail, nom, nomFamille, description, urlPhoto, prixMoyen, Year (dateConception) As dateConception 
-- pour obtenir uniquement l'année 

    Select cocktail.id, nom, nomFamille, description, urlPhoto, Year(dateConception) As dateConception, prixMoyen
    From cocktail
    INNER JOIN Famille ON famille.id = cocktail.idFamille

-- Faire une requete pour recuperer les cocktails selon la famille :
id du cocktail, nom, nomFamille, description, urlPhoto, prixMoyen, Year (dateConception) As dateConception 

    SELECT cocktail.id, nom, nomFamille, description, urlPhoto, Year(dateConception) As dateConception, prixMoyen
    FROM cocktail
    INNER JOIN Famille ON famille.id = cocktail.idFamille
    WHERE nomFamille = ?


-- Faire une requete pour recuperer la liste des familles avec le nombre de cocktail par familles
// colonnes : nomFamille - nombre
// count() / Group by

    SELECT nomFamille, COUNT(idFamille) AS nombre
    FROM cocktail
    INNER JOIN famille ON idFamille = famille.id
    GROUP BY nomFamille

-- Recuperation la liste de toutes les familles
    SELECT id, nomFamille
    FROM famille
    ORDER BY nomFamille

-- Ajout dans dans la bdd à partir du formulaire
    INSERT INTO Cocktail 
    (id, nom, description, urlPhoto, dateConception, prixMoyen, idFamille) 
    VALUES (NULL,'','','','','','', NULL)

-- Creer une nouvelle table : ingredient
Champs :
    - id PK AI
    - nomIngredient: VARCHAR(255) index UNIQUE 