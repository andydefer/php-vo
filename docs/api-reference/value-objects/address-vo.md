# AddressVO - Référence Technique

## Description

Value Object immuable représentant une adresse postale complète (rue, ville, code postal, pays) avec support optionnel des coordonnées géographiques.

## Hiérarchie

```
AbstractValueObject (andydefer/domain-structures)
    └── AddressVO
```

## Rôle principal

Encapsuler une adresse postale avec validation des champs obligatoires, fournir des méthodes de formatage adaptées à différents contextes (affichage standard, étiquette d'expédition), permettre des opérations métier comme la vérification du pays ou l'extraction du préfixe postal, et optionnellement associer des coordonnées géographiques.

## Installation

```bash
composer require andydefer/php-vo
```

## API / Méthodes publiques

### `getValue(): AddressRecord`

| Paramètre | Type | Description |
|-----------|------|-------------|
| - | - | Aucun paramètre |

**Retourne :** `AddressRecord` - L'adresse convertie en Record (structure de données)

**Exemple :**
```php
$address = AddressVO::from(['street' => '10 Rue de Rivoli', 'city' => 'Paris', 'postalCode' => '75001', 'country' => 'France']);
$record = $address->getValue();
// $record->street === '10 Rue de Rivoli'
```

### `format(): string`

| Paramètre | Type | Description |
|-----------|------|-------------|
| - | - | Aucun paramètre |

**Retourne :** `string` - Adresse formatée sur une seule ligne

**Exemple :**
```php
echo $address->format(); // "10 Rue de Rivoli, 75001 Paris, France"
```

### `formatShippingLabel(): string`

| Paramètre | Type | Description |
|-----------|------|-------------|
| - | - | Aucun paramètre |

**Retourne :** `string` - Adresse formatée avec sauts de ligne pour étiquette d'expédition

**Exemple :**
```php
echo $address->formatShippingLabel();
// Résultat :
// 10 Rue de Rivoli
// 75001 Paris
// France
```

### `isInCountry(string $country): bool`

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$country` | `string` | Nom du pays à vérifier (insensible à la casse) |

**Retourne :** `bool` - True si l'adresse est dans le pays spécifié

**Exemple :**
```php
if ($address->isInCountry('France')) {
    // Appliquer la livraison nationale
}
```

### `getPostalCodePrefix(): string`

| Paramètre | Type | Description |
|-----------|------|-------------|
| - | - | Aucun paramètre |

**Retourne :** `string` - Les deux premiers chiffres du code postal

**Exemple :**
```php
$prefix = $address->getPostalCodePrefix(); // "75" pour "75001"
```

### `getCoordinates(): ?CoordinatesVO`

| Paramètre | Type | Description |
|-----------|------|-------------|
| - | - | Aucun paramètre |

**Retourne :** `?CoordinatesVO` - Les coordonnées géographiques si présentes, null sinon

**Exemple :**
```php
$coordinates = $address->getCoordinates();
if ($coordinates) {
    $distance = $coordinates->distanceTo($otherCoordinates);
}
```

### `hasCoordinates(): bool`

| Paramètre | Type | Description |
|-----------|------|-------------|
| - | - | Aucun paramètre |

**Retourne :** `bool` - True si l'adresse a des coordonnées associées

**Exemple :**
```php
if ($address->hasCoordinates()) {
    // Afficher la carte
}
```

### `__toString(): string`

| Paramètre | Type | Description |
|-----------|------|-------------|
| - | - | Aucun paramètre |

**Retourne :** `string` - Identique à `format()`

**Exemple :**
```php
echo $address; // "10 Rue de Rivoli, 75001 Paris, France"
```

## Cas d'utilisation

### Cas 1 : Création et validation d'adresse client avec coordonnées

```php
<?php

declare(strict_types=1);

use AndyDefer\PhpVo\ValueObjects\AddressVO;
use AndyDefer\PhpVo\ValueObjects\CoordinatesVO;

try {
    $address = AddressVO::from([
        'street' => '123 Main Street',
        'city' => 'Lyon',
        'postalCode' => '69001',
        'country' => 'France',
        'coordinates' => [
            'latitude' => 45.7640,
            'longitude' => 4.8357
        ]
    ]);
    
    // Valider que c'est bien une adresse française
    if ($address->isInCountry('France')) {
        $shippingLabel = $address->formatShippingLabel();
        // Envoyer à l'imprimante d'étiquettes
    }
    
    // Utiliser les coordonnées pour le calcul de distance
    if ($address->hasCoordinates()) {
        $distance = $address->getCoordinates()->distanceTo($warehouseCoordinates);
    }
} catch (InvalidArgumentException $e) {
    error_log('Adresse invalide : ' . $e->getMessage());
}
```

### Cas 2 : Groupement géographique par département

```php
<?php

declare(strict_types=1);

use AndyDefer\PhpVo\ValueObjects\AddressVO;

$addresses = [
    AddressVO::from(['street' => '1 Rue A', 'city' => 'Paris', 'postalCode' => '75001', 'country' => 'France']),
    AddressVO::from(['street' => '2 Rue B', 'city' => 'Marseille', 'postalCode' => '13001', 'country' => 'France']),
    AddressVO::from(['street' => '3 Rue C', 'city' => 'Lyon', 'postalCode' => '69001', 'country' => 'France']),
];

$groupedByDepartment = [];

foreach ($addresses as $address) {
    $department = $address->getPostalCodePrefix(); // "75", "13", "69"
    $groupedByDepartment[$department][] = $address;
}
```

## Gestion des erreurs

| Situation | Exception | Message |
|-----------|-----------|---------|
| Rue vide | `InvalidArgumentException` | `Street address cannot be empty` |
| Ville vide | `InvalidArgumentException` | `City cannot be empty` |
| Pays vide | `InvalidArgumentException` | `Country cannot be empty` |
| Code postal invalide | `InvalidArgumentException` | Levée par `PostalCodeVO` |
| Coordonnées invalides | `InvalidArgumentException` | Levée par `CoordinatesVO::from()` |
| Données source invalides | `InvalidArgumentException` | Levée par `AbstractValueObject::from()` |

## Intégration

| Composant | Relation |
|-----------|----------|
| `PostalCodeVO` | Composition - L'adresse contient un code postal validé |
| `CoordinatesVO` | Composition optionnelle - L'adresse peut contenir des coordonnées |
| `AddressRecord` | Conversion - `getValue()` retourne un Record pour sérialisation |
| `AbstractValueObject` | Héritage - Fournit `from()`, `fromJson()`, `equals()` |

## Exemple complet

```php
<?php

declare(strict_types=1);

use AndyDefer\PhpVo\ValueObjects\AddressVO;

// Création depuis un tableau sans coordonnées
$address = AddressVO::from([
    'street' => '10 Rue de la Paix',
    'city' => 'Paris',
    'postalCode' => '75002',
    'country' => 'France'
]);

// Création depuis un tableau avec coordonnées
$addressWithCoords = AddressVO::from([
    'street' => 'Tour Eiffel',
    'city' => 'Paris',
    'postalCode' => '75007',
    'country' => 'France',
    'coordinates' => [
        'latitude' => 48.8584,
        'longitude' => 2.2945
    ]
]);

// Formatage standard
echo $address->format();
// "10 Rue de la Paix, 75002 Paris, France"

// Formatage pour étiquette d'expédition
echo $address->formatShippingLabel();
// "10 Rue de la Paix\n75002 Paris\nFrance"

// Vérification géographique
if ($address->isInCountry('France')) {
    $department = $address->getPostalCodePrefix(); // "75"
    echo "Département : {$department}";
}

// Vérification des coordonnées
if ($addressWithCoords->hasCoordinates()) {
    $coords = $addressWithCoords->getCoordinates();
    echo "Latitude : " . $coords->latitude;
    echo "Longitude : " . $coords->longitude;
}

// Conversion en Record pour stockage
$record = $address->getValue();

// Comparaison d'adresses
$sameAddress = AddressVO::from($address);
var_dump($address->equals($sameAddress)); // true

// Utilisation en chaîne (__toString)
echo $address; // "10 Rue de la Paix, 75002 Paris, France"
```

## Notes additionnelles

- L'adresse ne peut pas être créée avec des champs vides
- Le code postal est délégué à `PostalCodeVO` qui garantit le format à 5 chiffres
- Les coordonnées sont optionnelles et déléguées à `CoordinatesVO`
- La comparaison (`equals()`) est héritée de `AbstractValueObject` et vérifie tous les champs (y compris les coordonnées)
- L'immutabilité est garantie par les propriétés `readonly` (PHP 8.1+)