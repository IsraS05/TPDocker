1. Instructions de déploiement

A. Construire l'application (Dossier AppDocker)
Si le code source est modifié, il faut reconstruire et publier les images :

Build : docker-compose build pour créer les images locales.

Tag : docker tag appdocker-backend:latest ndondo26/todo-backend:latest pour préparer l'envoi.

Push : docker push ndondo26/todo-backend:latest pour héberger l'image sur le Docker Hub.

B. Démarrage de l'application (Dossier TP-FinalS)

Placez-vous dans le dossier TP-FinalS.

Vérifiez la présence du secret : un fichier "datab_root_password.txt" contenant le mot de passe.

Lancez la commande :

docker-compose up -d
Docker téléchargera automatiquement les images depuis le Docker Hub sans nécessiter le code source local.

2. Architecture Docker et Services
L'architecture repose sur une séparation stricte des responsabilités:

Description des Services
frontend : Basé sur React, il sert l'interface utilisateur. Il expose le port 3000 vers l'extérieur.

backend : Serveur Apache/PHP 7.4. Il contient la logique métier et l'API. Il expose le port 8080.

database : Moteur MySQL 8.0. Il est le seul service à ne pas exposer de port public pour garantir la sécurité.

Réseaux (Networks)
Nous avons configuré deux réseaux isolés (bridge) :

frontend-net : Permet la communication entre le client (navigateur) et l'API Backend.

backend-net : Réseau privé permettant au Backend de discuter avec la Database.

Sécurité : La base de données est invisible pour le Frontend.

Volumes et Secrets
Volume db-data : Monté sur /var/lib/mysql, il permet de conserver les données même si le conteneur est supprimé.

Secrets : Le fichier datab_root_password.txt est monté en lecture seule dans /run/secrets/. Cela évite d'exposer le mot de passe root dans les variables d'environnement.

3. Guide de test et validation
Tester la communication entre conteneurs
Pour vérifier que le Backend parvient à joindre la Database :

Exécutez la commande : docker-compose logs backend.

Succès : Vous devez voir des codes HTTP 200 sur les requêtes GET /api/todos.

Vérification manuelle : Connectez-vous directement à la DB depuis le conteneur :

Bash
docker exec -it tp-finals-database-1 mysql -u root -proot
Si vous accédez au prompt mysql>, la communication et l'authentification par secret sont validées.

Tester la persistance des données
Ouvrez l'application sur http://localhost:3000 et ajoutez une tâche (ex: "Test Persistance").

Arrêtez les services : docker-compose stop.

Relancez les services : docker-compose start.

Actualisez la page : La tâche doit toujours être présente, prouvant que le volume db-data fonctionne.
