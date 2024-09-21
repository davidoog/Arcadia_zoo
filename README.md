# Arcadia_zoo

# Déploiement de l'application en local

## Prérequis

Avant de lancer l'application en local, assurez-vous d'avoir les éléments suivants installés sur votre machine :

- [XAMPP](https://www.apachefriends.org/index.html) ou un autre serveur local pour exécuter PHP
- [MySQL Workbench](https://www.mysql.com/products/workbench/) pour gérer la base de données
- [git pour Windows](https://git-scm.com/download/win)

## Cloner le dépôt

Clonez le dépôt Git en utilisant la commande suivante :   

git clone https://github.com/davidoog/arcadia_zoo.git

(Si problème avec Git qui n'est pas reconnue, utiliser git bash)


!! IMPORTANT, si le fichier "arcadia_db.sql n'est pas présent lors du clonage, il faudras le télécharger manuellement:
Allez sur GitHub, ouvrez le fichier arcadia_db.sql.
Cliquez sur le bouton "Raw" pour voir le contenu brut du fichier.
Faites un clic droit et sélectionnez "Enregistrer sous..." pour sauvegarder le fichier sur votre ordinateur.
Ensuite : Ajouter le fichier manuellement dans votre projet local
Une fois le fichier téléchargé manuellement, placez-le dans le bon répertoire de votre projet

## Choix 1	Créer la base de données avec MySQL Workbench :

Ouvrez MySQL Workbench et exécutez le fichier SQL qui ce nomme "arcadia_db.sql" pour créer la base de données.

    CREATE DATABASE arcadia_db;

## Choix 2       Utiliser XAMPP

Ouvrez XAMPP Control Panel et démarrez Apache et MySQL.
Ensuite, ouvrez un navigateur et accédez à phpMyAdmin en saisissant cette URL : http://localhost/phpmyadmin.

## Importer les données

2. **Importer les données :**

Solution avec MySQL Workbench

   Dans MySQL Workbench, ouvrez votre base de données `arcadia_db` que vous venez de créer. Ensuite, importez le fichier SQL d'intégration de données (arcadia_db.sql) en utilisant la fonction d'importation :

   - Cliquez avec le bouton droit sur votre base de données dans l'arborescence.
   - Sélectionnez "Run SQL Script" ou "Exécuter un script SQL".
   - Choisissez votre fichier SQL et exécutez-le.

Solution avec XAMPP

  Sélectionnez votre base de données arcadia_db dans la liste à gauche.
Allez dans l'onglet Importer.
Cliquez sur Parcourir, puis choisissez le fichier SQL (arcadia_db.sql) situé dans le répertoire /sql de votre projet.
Cliquez sur Exécuter pour importer le fichier SQL et créer les tables avec les données.

## Configuration de l'environnement

1. **Modifier le fichier `db.php` :**

   Accédez au fichier `db.php` à la racine de votre projet et modifiez les paramètres de connexion à la base de données pour qu'ils correspondent à votre configuration locale :

   ```php
   $dbHost = 'localhost';       // Adresse du serveur de base de données
   $dbName = 'arcadia_db';      // Nom de votre base de données
   $dbUser = 'root';            // Votre utilisateur MySQL
   $dbPass = '';                // Votre mot de passe MySQL (vide si aucun mot de passe)


## Lancer le serveur

1. **Démarrer le serveur :**

   Ouvrez un terminal, placez-vous dans le dossier de votre projet et lancez un serveur PHP local avec la commande suivante :
   ```bash
   cd /chemin/vers/ton/projet
   php -S localhost:8000

Cela démarrera un serveur PHP local sur l’adresse http://localhost:8000.



# Accéder à l'application 
Ouvrez votre navigateur web et saisissez l’URL suivante :
	
 	http://localhost:8000


## Présentation de l'application

L'application **Arcadia Zoo** est une plateforme web permettant la gestion des animaux, habitats, et services d'un zoo fictif. Elle offre différentes interfaces adaptées aux besoins des administrateurs, vétérinaires, employés, et visiteurs.

### Identifiants pour tester l'application :

Voici les identifiants pour accéder aux différents rôles disponibles dans l'application et tester les fonctionnalités associées :

#### Compte Administrateur :
- **URL d'accès** : `http://localhost:8000/admin_dashboard.php`
- **Nom d'utilisateur** : `admin_user`
- **Mot de passe** : `motdepasseclair`

#### Compte Vétérinaire :
- **URL d'accès** : `http://localhost:8000/veterinaire_dashboard.php`
- **Nom d'utilisateur** : `veterinaire_user`
- **Mot de passe** : `4776fff2daf2af77f0353d2ca1815f07f7e5759d68baf9749ded946f6e8450b7`

#### Compte Employé :
- **URL d'accès** : `http://localhost:8000/employee_dashboard.php`
- **Nom d'utilisateur** : `employee_user`
- **Mot de passe** : `dd80e1549790cddeef43504fc5555781d42b90ecc5af68ba9195c84a63233211`

### Fonctionnalités possibles :

1. **Parcours Administrateur** : Gérer les animaux, habitats, services et horaires du zoo.
2. **Parcours Vétérinaire** : Remplir des comptes rendus de santé pour chaque animal, vérifier la nourriture consommée, et surveiller les états de santé des animaux.
3. **Parcours Employé** : Gérer les tâches quotidiennes et l'alimentation des animaux.

Ces identifiants permettent de réaliser les différents scénarios de gestion et de test au sein de l'application. 

