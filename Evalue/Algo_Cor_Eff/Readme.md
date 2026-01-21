# Validation de la correction et des performances de l’application

👉 **Lien Google Docs** :  
*https://docs.google.com/document/d/1lYMp0m5VYc-C3cp3HROEBcS9UUFjwCq_1j4LdJtBrUE/edit?tab=t.0#heading=h.f1w444iz2f2j*

---

## 🎯 Objectif

Ce document décrit les **méthodes mises en place pour démontrer** :
1. que l’application est **correcte fonctionnellement**,  
2. qu’elle est **optimisée en temps d’exécution et en consommation mémoire**.

Les choix de conception, d’implémentation et de déploiement sont justifiés afin de garantir
une application **fiable, performante et scalable**.

---

## 1️⃣ Validation de la correction fonctionnelle

### 1.1 Fonctionnalités attendues

Pour chaque fonctionnalité, les éléments suivants sont précisés :
- description de la fonctionnalité attendue,
- préconditions (conditions nécessaires à son utilisation),
- résultats attendus,
- résultats obtenus lors de l’exécution.


### 1.2 Justification par les choix de conception

Les fonctionnalités implémentées sont justifiées à partir :
- des choix d’architecture,
- des modèles de données,
- des diagrammes de conception,
- des patrons de conception utilisés (le cas échéant).

Ces choix garantissent que l’application répond précisément aux besoins exprimés.

---

### 1.3 Vérification par le code

Les mécanismes suivants sont utilisés pour assurer la correction :

- assertions intégrées dans le code lorsque cela est possible,
- jeux de tests unitaires et/ou fonctionnels,
- tests manuels documentés.

Exemples :
- vérification des états invalides,
- contrôle des préconditions,
- validation des résultats intermédiaires.

---

## 2️⃣ Validation des performances et de la consommation des ressources

### 2.1 Optimisation du traitement des données

Les données sont conçues et manipulées de manière optimale :
- normalisation des données jusqu’à la **troisième forme normale (3NF)**,
- choix d’un **SGBD adapté** au volume et au type de données,
- utilisation de procédures, requêtes ou index optimisés.

Ces choix limitent les redondances, améliorent la cohérence et réduisent les temps de traitement.

---

### 2.2 Analyse des points sensibles en temps et en mémoire

Les éléments susceptibles de consommer le plus de ressources sont identifiés :
- traitements lourds sur de grands volumes de données,
- requêtes complexes,
- affichage des vues (images lourdes/légères),
- enchaînement des écrans et des interactions utilisateur.

Chaque point critique est analysé et, si nécessaire, optimisé.

---

### 2.3 Montée en charge et pics d’activité

Les situations potentielles de montée en charge sont identifiées :
- augmentation du nombre d’utilisateurs simultanés,
- pics de ventes ou de consultations,
- accès concurrents aux données.

Les choix techniques permettent d’absorber ces charges sans dégrader significativement
les temps de réponse :
- architecture serveur adaptée,
- gestion des connexions,
- séparation des responsabilités (serveur applicatif / serveur de données).

---

### 2.4 Plan de dimensionnement

Le dimensionnement de l’application est justifié afin de :
- stocker correctement les données dans le temps,
- garantir des performances stables.

Cela inclut :
- dimensionnement des serveurs applicatifs,
- dimensionnement des serveurs de bases de données,
- estimation de l’évolution des volumes de données.

---

### 2.5 Organisation des données et déploiement

L’organisation des données et leur déploiement sont expliqués :
- répartition des données sur un ou plusieurs serveurs,
- interaction avec le SGBD choisi,
- cohérence entre architecture logicielle et architecture matérielle.

---

### 2.6 Impact de l’hébergement

L’impact du choix d’hébergement du serveur de données est analysé :
- hébergement local vs distant,
- latence réseau,
- bande passante,
- influence sur les temps de réponse des fonctionnalités.

Ces éléments sont pris en compte pour un déploiement final performant.

---

## ✅ Conclusion

Les méthodes mises en place permettent de démontrer que :
- l’application est **correcte par rapport aux fonctionnalités attendues**,
- les traitements sont **optimisés**,
- la consommation de ressources est maîtrisée,
- l’architecture est **dimensionnée pour évoluer**.

L’application répond ainsi aux exigences de qualité, de performance et de robustesse.
