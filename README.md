# Google reCAPTCHA pour Magix CMS 3

Protégez vos formulaires contre le spam avec l'intégration officielle de Google reCAPTCHA pour Magix CMS.

[![release](https://img.shields.io/github/release/magix-cms/recaptcha.svg)](https://github.com/magix-cms/recaptcha/releases/latest)
![License](https://img.shields.io/github/license/magix-cms/recaptcha.svg)
![PHP Version](https://img.shields.io/badge/php-%3E%3D%207.4-blue.svg)

![Admin Screenshot](https://user-images.githubusercontent.com/356674/56717181-66171c00-673c-11e9-9cb7-ee4c15755910.png)

##  Installation

1. **Upload :** Décompressez l'archive dans le dossier `/plugins` de votre installation Magix CMS.
2. **Activation :** Connectez-vous à l'administration, allez dans le menu **Plugins** et sélectionnez **recaptcha**.
3. **Auto-install :** L'installation se finalise automatiquement lors de l'ouverture du plugin.
4. **Configuration :** Saisissez votre *Site Key* et *Secret Key* obtenus sur [Google reCAPTCHA](https://www.google.com/recaptcha/admin).
5. **Template :** Copiez le fichier `recaptcha.tpl` (situé dans le plugin) vers le dossier `form/recaptcha/` de votre skin actuel.

##  Utilisation

Pour afficher le captcha dans votre formulaire de contact, ajoutez ce bloc dans votre fichier `contact.tpl` :

```smarty
{if isset($contact_config.recaptcha) && $contact_config.recaptcha}
    {include file="recaptcha/form/recaptcha.tpl" action="contact"}
{/if}
```

## Caractéristiques
Compatible Google reCAPTCHA v2 et v3.
Installation automatisée des tables/fichiers.
Intégration native avec le module de contact Magix CMS.

## Licence

Ce projet est sous licence **GPLv3**. Voir le fichier [LICENSE](LICENSE) pour plus de détails.
Copyright (C) 2008 - 2026 Gerits Aurelien (Magix CMS)
Ce programme est un logiciel libre ; vous pouvez le redistribuer et/ou le modifier selon les termes de la Licence Publique Générale GNU telle que publiée par la Free Software Foundation ; soit la version 3 de la Licence, ou (à votre discrétion) toute version ultérieure.

---