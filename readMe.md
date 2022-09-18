# Projet Test Leboncoin

## 1- Lancement du projet

Après avoir téléchargé le projet, se placer dans le répertoire docker et lancer la commande suivante :
 - `docker-compose up --build`

## 2- Initialisation de la BDD

Lorsque l'initialisation du projet est terminée, ouvrir un autre terminal, et toujours dans le dossier docker, lancer le shell du container php avec la commande suivante :
`docker exec -it php sh`

Puis pour instancier la base de données, lancer les commandes suivantes :
- `bin/console doctrine:database:create` => commande permettant de créer la base.
- `bin/console doctrine:schema:create` => commande permettant de créer la structure de la base.
- `bin/console doctrine:migrations:migrate` => commande permettant d'insérer les données des marques et modèles.

## 3- Test de l'API

L'installation du projet est terminée, vous pouvez maintenant créer, modifier, récupérer, supprimer votre annonce.
Pour cela voici les routes :

| Action       | Route     | Méthode | Paramètres                                                                                                                                                                                                                                                                                    | Description |
|--------------|-----------|-----|-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------| --- |
| Création     | /annonce  | POST | - titre* : string(100), pour une annonce automobile la correspondance avec le modèle se fera à partir de ce paramètre.  <br/> - contenu* : string(255) <br/> - categorie_id* : int<br/>1 - pour la catégorie emploi<br/>2 - pour la catégorie immobilier<br/>3 - pour la catégorie Automobile |  route permettant la création d'une annonce|
| Edition      | /annonce/{id} | PUT | - titre : string(100)<br/>- contenu : string(255)                                                                                                                                                                                                                                             | route permettant l'édition d'une annonce (seul le titre et le contenu sont modifiables)|
| Affichage    | /annonce/{id} | GET | pas de paramètres                                                                                                                                                                                                                                                                             | route permettant de récupérer une annonce|
|  Suppression | /annonce/{id} | DELETE | pas de paramètres                                                                                                                                                                                                                                                                             | route permettant la suppression d'une annonce|

*champs obligatoire

Quelques exemples de requêtes curl pour tester l'API :
  - Pour créer une annonce emploi : `curl -X POST -d titre='annonce emploi' -d contenu='test contenue' -d categorie_id=1 localhost/annonce`
  - Pour créer une annonce immobilier : `curl -X POST -d titre='annonce immobilier' -d contenu='test contenue' -d categorie_id=2 localhost/annonce`
  - Pour créer une annonce automobile : `curl -X POST -d titre='annonce automobile Rs4' -d contenu='test contenue' -d categorie_id=3 localhost/annonce`
  - Pour afficher une annonce : `curl -X GET localhost/annonce/1`
  - Pour modifier une annonce (seuls les champs "titre" et "contenu" peuvent être modifiés):`curl -X PUT -d titre=titre='annonce emploi update' localhost/annonce/1`
  - Pour supprimer une annonce : `curl -X DELETE localhost/annonce/1`

  
## 4- Lancement des tests unitaires
Pour utiliser les tests unitaires, il faut d'abord réaliser les commandes suivantes :
- `bin/console --env=test doctrine:database:create`
- `bin/console --env=test doctrine:schema:create`
- `bin/console --env=test doctrine:migrations:migrate` => les 3 premières servent à créer une base de données pour les tests unitaires.

Puis lancer les tests : 
`bin/phpUnit Tests/Controller/AnnonceControllerTest.php`
