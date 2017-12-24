Pilot
===========

## Participants
- Carmona Anthony
- Pyz Maxime

## Compte admin
 - Compte Admin : root
 - Mot de passe : UnSuperMotDePasseAvecDesChiffresTelQue15Ou16EtPuisFautRajouterDesCaracteresSpeciauxCestB1gLesCaracteresSpeciaux


## Commentaire
Framework css : Spectre-css
Bundle : 
- FosUserBundle
- TinyMce : https://github.com/stfalcon/TinymceBundle

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