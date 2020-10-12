## Affichage d'une nouvelle navigation pour les utilisateurs front

### Côté admin

1. Il suffit de créer une nouvelle navigation et un **`keyname`** (clé) sera auto-généré en Uuid v4.
- _Il faut se connecter en compte développeur pour voir le keyname dans la liste des navigations dans l'espace d'administration_

### Côté utilisateur

1. Pour afficher la navigation, une fonction **Twig** a été développé : `{{ render_navigation_by_key(keyname) }}`

_Un exemple est disponible dans le fichier **`templates/partials/_footer.html.twig`**, il faut remplacer le **keyname** dans la fonction_
