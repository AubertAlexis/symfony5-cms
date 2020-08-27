# Sym'MS - Cms Symfony 5

## Installation

### Depuis Github
```
git clone https://github.com/AubertAlexis/symfony5-cms.git
cd symfony5-cms
composer install
yarn install
composer prepare-cms
```

### Depuis Packagist
```
composer create-project aubert-alexis/symfony5-cms
cd symfony5-cms
yarn install
composer prepare-cms
```

## Configuration
Créer un fichier `.env.local` :
> N'oubliez pas de modifier avec vos informations.
```dotenv
DATABASE_URL=mysql://root:password@127.0.0.1:3306/symfony5-cms
```

## Démarer le serveur en local
```
symfony serve
yarn encore dev
```
