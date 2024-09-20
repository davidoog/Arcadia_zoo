# Arcadia_zoo
# Déploiement de l'application en local

## Prérequis

Avant de lancer l'application en local, assurez-vous d'avoir les éléments suivants installés sur votre machine :

- [XAMPP](https://www.apachefriends.org/index.html) ou un autre serveur local pour exécuter PHP
- [MySQL Workbench](https://www.mysql.com/products/workbench/) pour gérer la base de données

## Cloner le dépôt

Clonez le dépôt Git en utilisant la commande suivante :   

git clone https://github.com/monpseudo/arcadia_zoo.git

Ensuite,

	1.	Créer la base de données :
Ouvrez MySQL Workbench et exécutez le fichier SQL fourni pour créer la base de données. Utilisez le script suivant (modifiez le nom de la base de données si nécessaire) :
CREATE DATABASE arcadia_db;

## Importer les données

2. **Importer les données :**

   Dans MySQL Workbench, ouvrez votre base de données `arcadia_db` que vous venez de créer. Ensuite, importez le fichier SQL d'intégration de données (par exemple, `data_integration.sql`) en utilisant la fonction d'importation :

   - Cliquez avec le bouton droit sur votre base de données dans l'arborescence.
   - Sélectionnez "Run SQL Script" ou "Exécuter un script SQL".
   - Choisissez votre fichier SQL et exécutez-le.

## Configuration de l'environnement

1. **Modifier le fichier de configuration :**

   Accédez au fichier de configuration de votre application (généralement `config.php` ou un équivalent) et modifiez les paramètres de connexion à la base de données pour qu'ils correspondent à votre configuration locale :

   ```php
   $dbHost = 'localhost';       // Adresse du serveur de base de données
   $dbName = 'arcadia_db';      // Nom de votre base de données
   $dbUser = 'votre_utilisateur_mysql'; // Votre utilisateur MySQL
   $dbPass = 'Votre mot de passe';       // Votre mot de passe MySQL


## Lancer le serveur

1. **Démarrer le serveur :**

   Ouvrez XAMPP (ou votre serveur local) et démarrez les services Apache et MySQL. Vérifiez que les deux services sont bien en cours d'exécution. Cela vous permettra de servir votre application PHP et d'accéder à la base de données.

2. **Accéder à l'application :**

   Ouvrez votre navigateur web et saisissez l'URL suivante :   http://localhost/arcadia_zoo
Remplacez `nom_du_depot` par le nom du dossier de votre dépôt cloné. Cela vous amènera à la page d'accueil de l'application.

