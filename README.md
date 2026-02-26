Dzis
dz_is
En ligne

ymir_windrunner — Hier à 16:53
ah merte
j'essaye de régler avec chatgpt
ndoya — Hier à 16:58
ok
ymir_windrunner — Hier à 17:53
bon ça me clc
ndoya — Hier à 17:54
mdrrr
attend isra elle va tester sur sa vm
ymir_windrunner — Hier à 18:14
Kk
Dzis — Hier à 20:28
Image
ndoya — Hier à 20:28
ouff
ymir_windrunner — Hier à 20:38
Claude a fait une maj les entreprises de cyber ont chuté de 15%
mon n+1 aujd m’a montré claude terminale et m’a dit qu’il faut apprendre à l utiliser sinon on est foutu
Dzis — Hier à 21:00
aaaah
ymir_windrunner — Hier à 21:00
Et t’as les écoles qui veulent pas qu’on utilise l’ia
C’est littéralement impossible
Faut qu’on apprenne à l’utiliser bien sinon on est foutu
Dzis — Hier à 21:01
on est mal barré
ymir_windrunner — Hier à 21:03
Si les ecoles et nous continuons comme ća
Dzis — Hier à 21:06
c'est  ce qu'on fait déjà
ymir_windrunner — Hier à 21:07
Chatgpt c bien nul
En plus maintenant c cramable
Faut prendre l’abonnement claude
Dzis — Hier à 21:09
on utilise de moins en moins chatgpt
tout le mondde passe sur claude, gemini...
ymir_windrunner — Hier à 21:09
Jsuis deja en retard mdrr
C une dinguerie comment claude c trop fort
ndoya — Hier à 21:10
Dingue
Dzis — Hier à 21:12
c'est normam c'est pas interactif?
MA VM est morte 😭
ymir_windrunner — Hier à 21:12
Merde
ndoya — Hier à 21:13
Ehhh s’il vous plaît🤣🤣 
T’as un message d’erreur ?
Pardon faites le reste on va présenter sur mon pc💀
Dzis — Hier à 21:22
ok
Dzis — 10:16
https://github.com/IsraS05/TPDocker
GitHub
GitHub - IsraS05/TPDocker
Contribute to IsraS05/TPDocker development by creating an account on GitHub.
GitHub - IsraS05/TPDocker
ndoya — 10:21
1. Instructions de déploiement

A. Construire l'application (Dossier AppDocker)
Si le code source est modifié, il faut reconstruire et publier les images :

Build : docker-compose build pour créer les images locales.

Documentation du Setup Application.txt
3 Ko
///////////////////////////
Dans ton Dockerfile backend, tu as probablement une ligne comme RUN a2enmod rewrite. Cette commande active la lecture du fichier .htaccess dans Apache. Sans cela :

Tes appels API renverraient une erreur 404 Not Found.

Ton navigateur bloquerait les requêtes pour cause de CORS Policy.
ymir_windrunner — 10:42
jsuis choqué de claude
il viens de faire 80% du pa de l'année dernière
en 5min
avec un prompt éclaté au so
sol
php javascript html structure
﻿
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
Documentation du Setup Application.txt
3 Ko
