# Projet Test Leboncoin

## 1- Lancement du projet

Après avoir télécharger le projet, ce placer dans le répertoire docker et lancer les commandes suivantes :
 - `docker-compose up -d --build`
 - `docker exec -it php sh`
 - `composer install`

## 2- Initialisation de la BDD

Toujours dans le shell du container php lancer les commandes suivantes :
- `bin/console doctrine:database:create` => commande permettant de créer la base.
- `bin/console doctrine:schema:create` => commande permettant de créer la structure de la base.
- `bin/console doctrine:migrations:migrate` => commande permettant d'insérer les données des marques et modèles.

## 3- Test du projet
Après ces deux étapes, le projet est pret à être tester.

Pour cela deux possiblitées : 

- Utiliser des requêtes curl, quelques exemples :
  
  - Pour créer une annonce emploi : `curl -X POST -d titre='annonce emploi' -d contenu='test contenue' -d categorie_id=1 localhost/annonce`
  - Pour afficher une annonce : `curl -X GET localhost/annonce/1`
  - Pour modifier une annonce (seul les champs titre et contenu peuvent être modifiés):`curl -X PUT -d titre=titre='annonce emploi update' localhost/annonce/1`
  - Pour supprimer une annonce : `curl -X DELETE localhost/annonce/1`

- Utiliser les tests unitaires, pour cela réaliser d'abord les commandes suivantes : 
  - `bin/console --env:test doctrine:database:create`
  - `bin/console --env:test doctrine:schema:create`
  - `bin/console --env:test doctrine:migrations:migrate` => les 3 premières servent à créer une base de données pour les tests unitaires.
  - `bin/phpUnit Tests/Controller/AnnonceControllerTest.php`

