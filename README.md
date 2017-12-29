Pilot
===========

## Participants

- Carmona Anthony
- Pyz Maxime

## Comptes

### Compte writer

- Username : redac
- Mot de passe : passwordRedac

### Compte admin

 - Username : admin
 - Mot de passe : passwordAdmin


## Commentaire / Fonctionnalités

### Commentaire

Framework css : Spectre-css
la traduction d'éléments fixes du site ont été réalisés

Bundle utilisés:

- FosUserBundle
- TinyMce : https://github.com/stfalcon/TinymceBundle

### Fonctionnalités

#### Profile

- [x] Login habillé
- [x] Enregistrement habillé
- [x] Gestion des rôles (Utilisateur, Redacteur, Admin)
- [x] Modification de profil habillé
- [x] Modification de profil (Description)
- [ ] Gestion des utilisateurs par l'admin (modification de droit)

#### Articles

- [x] Page d'acceuil
- [x] Pagination
- [x] Ajout d'articles
- [x] Visualisation d'article
- [x] Modification d'article
- [x] Suppression d'article
- [x] Affichage de message feedback (message quand une opération réussie ou renvoi une erreur)
- [ ] Afficher les articles pin sur la page d'acceuil et les mettres en avant

#### Commentaires

- [x] Rédaction d'un commentaire (utilisateur)
- [x] Visualisation des commentaires
- [ ] Modification d'un commentaire (Admin ou Redacteur ou utilisateur lui même)
- [x] Suppression d'un commentaire (Admin ou Redacteur ou utilisateur lui même)
 
#### Recherche

- [x] Rechercher les articles d'une série

## Installation / mise à jour

### Installation

```
composer install
yarn install
```

### Mise à jour de la BDD 

```
php bin/console doctrine:schema:update --force
```

### Commandes pour compiler le css

```
yarn run encore dev
```
ou, pour compiler à chaque fois que les fichiers changent
```
yarn run encore dev --watch
```
ou
```
./node_modules/.bin/encore dev
```

### tinymce

Si tinymce ne marche pas, essayer de faire cette commande :
```bash
php bin/console assets:install web/
```
