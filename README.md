ToDoList
========

Projet #8 : Améliorez une application existante de ToDo & Co

Contexte
========

Projet n°8 dans le cadre de ma formation OpenClassrooms : Développeur d'application PhP/Symfony. 

https://openclassrooms.com/projects/ameliorer-un-projet-existant-1

Description du projet par OpenClassrooms
========

Vous venez d’intégrer une startup dont le cœur de métier est une application permettant de gérer ses tâches quotidiennes. L’entreprise vient tout juste d’être montée, et l’application a dû être développée à toute vitesse pour permettre de montrer à de potentiels investisseurs que le concept est viable (on parle de Minimum Viable Product ou MVP).

Le choix du développeur précédent a été d’utiliser le framework PHP Symfony, un framework que vous commencez à bien connaître ! 

Bonne nouvelle ! ToDo & Co a enfin réussi à lever des fonds pour permettre le développement de l’entreprise et surtout de l’application.

Votre rôle ici est donc d’améliorer la qualité de l’application. La qualité est un concept qui englobe bon nombre de sujets : on parle souvent de qualité de code, mais il y a également la qualité perçue par l’utilisateur de l’application ou encore la qualité perçue par les collaborateurs de l’entreprise, et enfin la qualité que vous percevez lorsqu’il vous faut travailler sur le projet.

Ainsi, pour ce dernier projet de spécialisation, vous êtes dans la peau d’un développeur expérimenté en charge des tâches suivantes :

l’implémentation de nouvelles fonctionnalités ;
la correction de quelques anomalies ;
et l’implémentation de tests automatisés.
Il vous est également demandé d’analyser le projet grâce à des outils vous permettant d’avoir une vision d’ensemble de la qualité du code et des différents axes de performance de l’application.

Il ne vous est pas demandé de corriger les points remontés par l’audit de qualité de code et de performance. Cela dit, si le temps vous le permet, ToDo & Co sera ravi que vous réduisiez la dette technique de cette application.

Spécification Technique
========

Environnement
========

Version du projet Symfony : 5.4

Il require :
- Php : >=7.2.5

Installation 
========

1) Git clone the project
git@github.com:Amael7/oc-p8-todo-list.git

2) Installez les librairies
php bin/console composer install

3) Créer la base de données :
  a) Mettre à jour le fichier .env avec votre configuration de base de donnée.
    - DATABASE_URL=mysql://db_user:db_password@127.0.0.1:3306/db_name
  b) Creation de la base de données: 
    - php bin/console doctrine:database:create
  c) Creation de la structure de la base de données: 
    - php bin/console doctrine:schema:update --force
  d) Creation de données fictives: 
    - php bin/console doctrine:fixtures:load --group=UserFixtures --group=TaskFixtures
4) Ensuite vous pouvez lancez le serveur avec la commande:
    - symfony serve
  
Pour lancer d'autre commande dans le terminal il suffit soit d'ouvrir un nouveau terminal soit de lancer la commande "symfony serve -d" à la place de "symfony serve".

Tests
========

Tout les tests unitaires et fonctionnels ont été implémenté avec PhP Unit.

Procédure pour lancer les tests

1) Créer la base de données de test : 
  - php bin/console doctrine:database:create --env=test  
  
2) Créer la structure de la base de données de test : 
  - php bin/console doctrine:schema:create --env=test   
  
3) Créer les données fictives sur l'environnement de test :
  - php bin/console doctrine:fixtures:load --group=UserTestFixtures --group=TaskTestFixtures --env=test
  
4) Utiliser la commande pour lancer les tests : 
  - vendor/bin/phpunit
  
Commande pour obtenir le rapport de couverture de test :
  - vendor/bin/phpunit --coverage-html public/test-coverage
   
Comment Utiliser l'application
========

Afin de pouvoir utiliser l'application correctement, il existe plusieurs compte utilisateur généré.

Identifiant de connexion d'un compte **utilisateur classique** :
  - Username: user1
  - password: password
  
Identifiant de connexion d'un compte **Administrateur** :
  - Username: admin1
  - password: password
   
Documentation
========

- Diagrammes UML 
- Guide d'authentification
- Audit de qualité et de performance
