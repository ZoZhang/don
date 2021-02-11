## Exemple Module Donation Product
C'est un exemple de module donation product sous Magento 2

## Prérequis

* Magento 2.3.5

## Fonctionnalités Backend
* [x] Adminhtml CURD (Donation Product / Configuration)
* [x] Activer/Désactiver le module sur le front
* [x] Saisir le SKU d’un produit qui sera utilisé pour faire le don
* [x] Ajouter un titre
* [x] Ajouter un titre
* [x] Ajouter une description (avec WYSIWYG)
* [x] Ajouter un montant
* [ ] Order grid 

## Fonctionnalités  Frontend
* [x] L’image du produit saisi dans la configuration
* [x] Le titre ajouté depuis la configuration
* [x] la description ajoutée depuis la configuration
* [x] Un champ “Montant” pour saisir le montant du don. Par défaut ça sera le montant saisi dans la configuration du module
* [x] Un bouton “Faire un don” permettra à l’utilisateur de faire un don. En cliquant sur ce bouton cela va ajouter le produit dans le panier avec comme prix le montant saisi dans le champ “Montant”.

## Problèmes rencontrés

Lorsqu'on surprime un product en mode Ajax.
`http://localhost/donation/cart/delete/product/2/`

```
Magento\GoogleShoppingAds\Plugin\DeleteProductFromShoppingCart::afterExecute() must be an instance of Magento\Framework\Controller\Result\Redirect, instance
```

## Démonstration

### Backend
![](https://i.imgur.com/nFnn7PU.jpg)

![](https://i.imgur.com/bEpX9u7.jpg)

![](https://i.imgur.com/WLM0xGC.jpg)

### Frontend
![](https://i.imgur.com/DK4Ckxg.jpg)

![](https://i.imgur.com/VbMO2Tw.jpg)


