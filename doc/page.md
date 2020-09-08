## Créer un nouveau modèle de pages

### Côté admin

1. Créer une nouvelle entité avec le nom du template **Template**
2. Créer une nouvelle migration : `symfony console make:migration`
3. Faire la review de la migration et l'executer : `symfony console doctrine:migrations:migrate -n`
4. Éditer le **FormType** et ajouter les champs si nécessaire : **`src/Form/HomePageType`**
5. Si vous avez ajouté des champs dans le **FormType**, n'oubliez pas de les ajouter dans le template : **`templates/admin/homePage/_form.html.twig`**

### Côté utilisateur

1. Afficher les champs comme vous voulez dans le template front : **`templates/home/index.html.twig`**
