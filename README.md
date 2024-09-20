# Arcadia_zoo
# Déploiement de l'application en local

## Prérequis

Avant de lancer l'application en local, assurez-vous d'avoir les éléments suivants installés sur votre machine :

- [XAMPP](https://www.apachefriends.org/index.html) ou un autre serveur local pour exécuter PHP
- [MySQL Workbench](https://www.mysql.com/products/workbench/) pour gérer la base de données

## Cloner le dépôt

Clonez le dépôt Git en utilisant la commande suivante :   

git clone https://github.com/monpseudo/arcadia_zoo.git



1.	Créer la base de données :

Ouvrez MySQL Workbench et exécutez le fichier SQL fourni dans le répertoire `/sql` pour créer la base de données. Utilisez le script suivant (modifiez le nom de la base de données si nécessaire) :
CREATE DATABASE arcadia_db;

## Importer les données

2. **Importer les données :**

   Dans MySQL Workbench, ouvrez votre base de données `arcadia_db` que vous venez de créer. Ensuite, importez le fichier SQL d'intégration de données (par exemple, `data_integration.sql`) en utilisant la fonction d'importation :

   - Cliquez avec le bouton droit sur votre base de données dans l'arborescence.
   - Sélectionnez "Run SQL Script" ou "Exécuter un script SQL".
   - Choisissez votre fichier SQL et exécutez-le.

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


