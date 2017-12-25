Pilot
===========

## Participants

- Carmona Anthony
- Pyz Maxime

## Compte admin

 - Compte Admin : root
 - Mot de passe : UnSuperMotDePasseAvecDesChiffresTelQue15Ou16EtPuisFautRajouterDesCaracteresSpeciauxCestB1gLesCaracteresSpeciaux


## Commentaire / Fonctionnalités

### Commentaire

Framework css : Spectre-css

Bundle utilisés:

- FosUserBundle
- TinyMce : https://github.com/stfalcon/TinymceBundle

### Fonctionnalités

#### Profile

- [x] Login habillé
- [x] Enregistrement habillé
- [ ] Gestion des rôles (Utilisateur, Redacteur, Admin)
- [ ] Modification de profil habillé
- [ ] Modification de profil (Description)

#### Articles

- [x] Page d'acceuil
- [x] Pagination
- [x] Ajout d'articles
- [x] Visualisation d'article
- [x] Modification d'article
- [x] Suppression d'article
- [ ] Affichage de message feedback (message quand une opération réussie ou renvoi une erreur)

#### Commentaires

- [ ] Rédaction d'un commentaire
- [ ] Visualisation d'un commentaire
- [ ] Modification d'un commentaire
- [ ] Suppression d'un commentaire
 
#### Recherche

- [ ] Rechercher les articles d'une série

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

### Commandes pour compiler le css

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