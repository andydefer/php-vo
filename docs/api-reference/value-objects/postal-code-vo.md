# PostalCodeVO - Référence Technique

## Description

Value Object immuable représentant un code postal français validé, composé exactement de 5 chiffres.

## Hiérarchie

```
AbstractValueObject (andydefer/domain-structures)
    └── PostalCodeVO
```

## Rôle principal

Encapsuler un code postal français avec validation stricte (5 chiffres exactement), normalisation (trim des espaces), et fournir un accès sécurisé à la valeur.

## Installation

```bash
composer require andydefer/php-vo
```

## API / Méthodes publiques

### `getValue(): string`

| Paramètre | Type | Description |
|-----------|------|-------------|
| - | - | Aucun paramètre |

**Retourne :** `string` - Le code postal à 5 chiffres

**Exemple :**
```php
$postalCode = PostalCodeVO::from('75001');
echo $postalCode->getValue(); // '75001'
```

---

### `__toString(): string`

| Paramètre | Type | Description |
|-----------|------|-------------|
| - | - | Aucun paramètre |

**Retourne :** `string` - Identique à `getValue()`

**Exemple :**
```php
$postalCode = PostalCodeVO::from('75001');
echo $postalCode; // '75001'
```

## Cas d'utilisation

### Cas 1 : Validation de code postal utilisateur

```php
<?php

declare(strict_types=1);

use AndyDefer\PhpVo\ValueObjects\PostalCodeVO;

try {
    $postalCode = PostalCodeVO::from($_POST['postal_code']);
    
    // Code postal valide
    $code = $postalCode->getValue();
    echo "Code postal validé : {$code}";
    
} catch (InvalidArgumentException $e) {
    echo "Code postal invalide : " . $e->getMessage();
}
```

### Cas 2 : Regroupement par département

```php
<?php

declare(strict_types=1);

use AndyDefer\PhpVo\ValueObjects\PostalCodeVO;

$postalCodes = [
    PostalCodeVO::from('75001'),
    PostalCodeVO::from('75002'),
    PostalCodeVO::from('13001'),
    PostalCodeVO::from('69001'),
];

$byDepartment = [];

foreach ($postalCodes as $postalCode) {
    $department = substr($postalCode->getValue(), 0, 2);
    $byDepartment[$department][] = $postalCode->getValue();
}

// $byDepartment = [
//     '75' => ['75001', '75002'],
//     '13' => ['13001'],
//     '69' => ['69001']
// ]
```

### Cas 3 : Stockage en base de données

```php
<?php

declare(strict_types=1);

use AndyDefer\PhpVo\ValueObjects\PostalCodeVO;

class Address
{
    private PostalCodeVO $postalCode;
    
    public function setPostalCode(string $code): void
    {
        $this->postalCode = PostalCodeVO::from($code);
    }
    
    public function getPostalCode(): string
    {
        return $this->postalCode->getValue();
    }
}

$address = new Address();
$address->setPostalCode('75001');
echo $address->getPostalCode(); // '75001'
```

### Cas 4 : Filtrage par région

```php
<?php

declare(strict_types=1);

use AndyDefer\PhpVo\ValueObjects\PostalCodeVO;

function isIleDeFrance(PostalCodeVO $postalCode): bool
{
    $department = substr($postalCode->getValue(), 0, 2);
    $ileDeFranceDepartments = ['75', '77', '78', '91', '92', '93', '94', '95'];
    
    return in_array($department, $ileDeFranceDepartments);
}

$postalCode = PostalCodeVO::from('75001');
echo isIleDeFrance($postalCode) ? 'Île-de-France' : 'Autre région'; // 'Île-de-France'
```

## Gestion des erreurs

| Situation | Exception | Message |
|-----------|-----------|---------|
| Source non string | `InvalidArgumentException` | `Postal code must be a string` |
| Moins de 5 chiffres | `InvalidArgumentException` | `Invalid postal code format: "...". Must be 5 digits.` |
| Plus de 5 chiffres | `InvalidArgumentException` | `Invalid postal code format: "...". Must be 5 digits.` |
| Contient des lettres | `InvalidArgumentException` | `Invalid postal code format: "...". Must be 5 digits.` |
| Source null | `InvalidArgumentException` | `Postal code must be a string` |
| Source array | `InvalidArgumentException` | `Postal code must be a string` |

## Intégration

| Composant | Relation |
|-----------|----------|
| `AbstractValueObject` | Héritage - Fournit `from()`, `fromJson()`, `equals()` |
| `Transformable` | Implémentation indirecte via AbstractValueObject |
| `Hydratable` | Hérité - Permet l'hydratation automatique |

## Performance

- **Création** : O(1) - validation par regex simple
- **Comparaison** : O(1) - via `equals()` hérité
- **Mémoire** : Stockage d'une seule string

## Compatibilité

| Version PHP | Support |
|-------------|---------|
| PHP 8.1+ | ✅ Complet |
| PHP 8.0 | ✅ Complet |
| PHP 7.x | ❌ Non supporté (propriétés readonly) |

## Exemple complet

```php
<?php

declare(strict_types=1);

use AndyDefer\PhpVo\ValueObjects\PostalCodeVO;

// Création valide
$postalCode = PostalCodeVO::from('75001');
echo $postalCode->getValue(); // '75001'

// Normalisation des espaces
$withSpaces = PostalCodeVO::from(' 75001 ');
echo $withSpaces->getValue(); // '75001'

// Création depuis instance existante (retourne la même instance)
$original = PostalCodeVO::from('75001');
$duplicate = PostalCodeVO::from($original);
var_dump($original === $duplicate); // true

// Tentatives invalides (lèvent des exceptions)
try {
    PostalCodeVO::from('1234');     // 4 chiffres
} catch (InvalidArgumentException $e) {
    echo $e->getMessage(); // 'Invalid postal code format: "1234". Must be 5 digits.'
}

try {
    PostalCodeVO::from('ABCDE');    // Lettres
} catch (InvalidArgumentException $e) {
    echo $e->getMessage(); // 'Invalid postal code format: "ABCDE". Must be 5 digits.'
}

try {
    PostalCodeVO::from(75001);      // Integer
} catch (InvalidArgumentException $e) {
    echo $e->getMessage(); // 'Postal code must be a string'
}

// Comparaison
$code1 = PostalCodeVO::from('75001');
$code2 = PostalCodeVO::from('75001');
$code3 = PostalCodeVO::from('69001');

var_dump($code1->equals($code2)); // true
var_dump($code1->equals($code3)); // false

// String casting
echo $code1; // '75001'
```

## Limitations

- Format français uniquement (5 chiffres)
- Ne valide pas l'existence du code postal (format uniquement)
- Ne supporte pas les codes postaux étrangers
