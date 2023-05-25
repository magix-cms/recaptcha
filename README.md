![recaptcha-logo](https://user-images.githubusercontent.com/356674/48693498-ee1b5780-ebd9-11e8-81f3-e5d528f3475c.png)
# recaptcha
Plugin Google Recaptcha for Magix CMS 3

![Screenshot_2019-04-25 recaptcha Magix CMS Admin](https://user-images.githubusercontent.com/356674/56717181-66171c00-673c-11e9-9cb7-ee4c15755910.png)

## Installation
 * Décompresser l'archive dans le dossier "plugins" de magix cms
 * Connectez-vous dans l'administration de votre site internet
 * Cliquer sur l'onglet plugins du menu déroulant pour sélectionner advantage (points forts).
 * Une fois dans le plugin, laisser faire l'auto installation
 * Il ne reste que la configuration du plugin pour correspondre avec vos données.
 * Copier le fichier **recaptcha.tpl** dans le dossier form/recaptcha de votre skin.

### Ajouter dans contact.tpl la ligne suivante

```smarty
{recaptcha_data}
{include file="recaptcha/form/recaptcha.tpl" data=$recaptcha action="contact"}
````
