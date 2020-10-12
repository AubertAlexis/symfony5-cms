# Sym'MS - Cms Symfony 5

## 1 - Installation

### Depuis Github
```
git clone https://github.com/AubertAlexis/symfony5-cms.git
cd symfony5-cms
composer install
yarn install
```

### Depuis Packagist
```
composer create-project aubert-alexis/symfony5-cms
cd symfony5-cms
yarn install
```

## 2 - Configuration
Créer un fichier `.env.local` :
> N'oubliez pas de modifier avec vos informations.
```dotenv
DATABASE_URL=mysql://root:password@127.0.0.1:3306/symfony5-cms

EMAIL="Votre_email_d_envoie"

MAILER_URL=gmail://${EMAIL}:votre_mot_de_passe_d_email@localhost
```

## 3 - Génération du CMS
```
composer prepare-cms
```

## 4 - Démarer le serveur en local
```
symfony serve
yarn encore dev
```

---------------

## Documentation technique

 [Voir la documentation ici](https://github.com/AubertAlexis/symfony5-cms/blob/develop/doc/index.md)
