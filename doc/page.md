## Créer un nouveau modèle de pages

### Côté admin

_Au préalable, connectez-vous avec le compte développeur et allez dans la section "**Modèle de page**" et créer en un nouveau, ATTENTION la **clé de template** doit être en un seul mot, ex: "list", "imageText", "newListOfArticles" ..._

1. Créer une nouvelle entité (et le repository) avec le nom du template + "**Template**", ex: "ListTemplate", "ImageTextTemplate" ...
- _Il ne faut pas oublier d'ajouter la liaison avec l'entité "**Page**" et celle avec l'entité "**Template**", des exemples de templates sont présents : **`src/Entity/------Template`**_
2. Dans l'entité **`src/Entity/Template`** ajouter une nouvelle constante avec la _**"clé de template"**_ créé au préalable de l'étape 1.
3. Créer une nouvelle migration : `symfony console make:migration`
3. Faire la review de la migration et l'executer : `symfony console doctrine:migrations:migrate -n`
4. Créer le **FormType** correspondant avec la nomenclature : nom de l'entité + "**Type**"
5. Éditer le **PageType** en ajoutant dans l'event de "**PRE_SET_DATA**" une condition pour ce type de page
- _Dans la fonction manageElements() il y a des exemples_
6. Si il y a une logique métier à implementer pour le nouveau **Modèle de page**, il faut l'ajouter dans le : **`src/Handler/PageHandler`**

### Côté utilisateur

1. Modifier la fonction **handlePage()** dans le **`src/Controller/Front/Page/Read`**
2. Créer un nouveau template twig avec comme nom, la _**clé de template**_ créé depuis l'interface "**Modèle de page**" de l'espace d'administration, ex : _"list.html.twig"_, _"imageText.html.twig"_
- _Des exemples sont présents via les autres templates_
