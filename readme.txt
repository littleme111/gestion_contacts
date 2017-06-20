Mise en Production :

        Changements a éffectuer sur le formulaire de contact "rejoignez la team"
            - duplication du champ d'upload de fichiers. (cv lm)
            - passage en obligatoire du champ "poste recherché"

        Plugin gestion_contacts :
            - Requires : contact forms 7 et Flamingo.
            - l'activation du plugin devrait creer toutes les tables nécéssaires et inserer les données nécéssaires.
            - il y a un hook "register_deactivation_hook" dans gestion_contacts.php qui supprime toutes les bases crées lors de la
                deactivation : a voir si vous souhaitez le garder, sachant qu'il supprimera donc tout l'historique des demandes.
            - si la partie gestion des référents dans la page gestion des mails, est vide c'est car l'insertion dans la table
                correspondante utilise l'id user 1 et il se pourrait qu'il n'y ait pas de user avec ce nom. Il suffit soit de les rajouter
                a la main a travers le back office ou alors directement dans la base de données.
            - frontController.php : ce controlleur explode l'url et récupère l'index 3 qui doit correspondre au nom du controlleur appelé.
              Si la structure de l'url change, il faudra peut etre changer l'index correspodant.

