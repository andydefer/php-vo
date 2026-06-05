# IdVO - Référence Technique

## Description

Value Object immuable représentant un identifiant entier positif.

## Hiérarchie

```
AbstractValueObject (andydefer/domain-structures)
    └── IdVO
```

## Rôle principal

Encapsuler un identifiant entier avec validation stricte (valeur positive), fournir des méthodes de transformation (incrémentation, décrémentation, formatage), et garantir l'immutabilité.

## Installation

```bash
composer require andydefer/php-vo
```

## API / Méthodes publiques

### `getValue(): int`

| Paramètre | Type | Description |
|-----------|------|-------------|
| - | - | Aucun paramètre |

**Retourne :** `int` - La valeur brute de l'identifiant

**Exemple :**
```php
$id = IdVO::from(123);
echo $id->getValue(); // 123
```

---

### `toReadableString(): string`

| Paramètre | Type | Description |
|-----------|------|-------------|
| - | - | Aucun paramètre |

**Retourne :** `string` - L'identifiant formaté de manière lisible

**Exemple :**
```php
$id = IdVO::from(123);
echo $id->toReadableString(); // 'ID #123'
```

---

### `toZeroPadded(int $length): string`

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$length` | `int` | Longueur totale de la chaîne après padding |

**Retourne :** `string` - L'identifiant complété avec des zéros à gauche

**Exemple :**
```php
$id = IdVO::from(123);
echo $id->toZeroPadded(5); // '00123'
```

---

### `isValid(): bool`

| Paramètre | Type | Description |
|-----------|------|-------------|
| - | - | Aucun paramètre |

**Retourne :** `bool` - True si l'identifiant est valide (toujours vrai après construction)

**Exemple :**
```php
$id = IdVO::from(123);
echo $id->isValid(); // true
```

---

### `increment(): self`

| Paramètre | Type | Description |
|-----------|------|-------------|
| - | - | Aucun paramètre |

**Retourne :** `self` - Nouvelle instance avec la valeur incrémentée de 1

**Exceptions :** `InvalidArgumentException` - Si le résultat dépasse `PHP_INT_MAX`

**Exemple :**
```php
$id = IdVO::from(123);
$nextId = $id->increment();
echo $nextId->getValue(); // 124
```

---

### `decrement(): self`

| Paramètre | Type | Description |
|-----------|------|-------------|
| - | - | Aucun paramètre |

**Retourne :** `self` - Nouvelle instance avec la valeur décrémentée de 1

**Exceptions :** `InvalidArgumentException` - Si le résultat devient ≤ 0

**Exemple :**
```php
$id = IdVO::from(123);
$prevId = $id->decrement();
echo $prevId->getValue(); // 122
```

---

### `__toString(): string`

| Paramètre | Type | Description |
|-----------|------|-------------|
| - | - | Aucun paramètre |

**Retourne :** `string` - Identique à `(string) $id->getValue()`

**Exemple :**
```php
$id = IdVO::from(123);
echo $id; // '123'
```

## Cas d'utilisation

### Cas 1 : Identifiant utilisateur

```php
<?php

declare(strict_types=1);

use AndyDefer\PhpVo\ValueObjects\IdVO;

class UserRepository
{
    public function find(IdVO $userId): ?User
    {
        return $this->db->find($userId->getValue());
    }
}

$userId = IdVO::from(123);
$user = $repository->find($userId);
echo "Utilisateur: " . $userId->toReadableString(); // 'User #123'
```

### Cas 2 : Pagination par ID

```php
<?php

declare(strict_types=1);

use AndyDefer\PhpVo\ValueObjects\IdVO;

class ProductRepository
{
    public function getNextProduct(IdVO $currentId): ?Product
    {
        $nextId = $currentId->increment();
        return $this->find($nextId);
    }
    
    public function getPreviousProduct(IdVO $currentId): ?Product
    {
        try {
            $prevId = $currentId->decrement();
            return $this->find($prevId);
        } catch (InvalidArgumentException $e) {
            // Premier produit, pas de précédent
            return null;
        }
    }
}

$currentId = IdVO::from(5);
$nextProduct = $repository->getNextProduct($currentId); // ID 6
```

### Cas 3 : Formatage pour affichage

```php
<?php

declare(strict_types=1);

use AndyDefer\PhpVo\ValueObjects\IdVO;

$orderId = IdVO::from(12345);

// Format pour facture
echo "Commande " . $orderId->toZeroPadded(8); // 'Commande 00012345'

// Format pour API
echo $orderId->getValue(); // '12345'

// Format pour email
echo $orderId->toReadableString(); // 'ID #12345'
```

### Cas 4 : Génération séquentielle

```php
<?php

declare(strict_types=1);

use AndyDefer\PhpVo\ValueObjects\IdVO;

$lastId = IdVO::from(1000);

$newIds = [];
for ($i = 0; $i < 5; $i++) {
    $lastId = $lastId->increment();
    $newIds[] = $lastId;
}

// $newIds = [1001, 1002, 1003, 1004, 1005]
```

## Gestion des erreurs

| Situation | Exception | Message |
|-----------|-----------|---------|
| Valeur = 0 | `InvalidArgumentException` | `ID must be positive, got: 0` |
| Valeur négative | `InvalidArgumentException` | `ID must be positive, got: -X` |
| Décrémentation à 0 | `InvalidArgumentException` | `ID must be positive, got: 0` |

## Intégration

| Composant | Relation |
|-----------|----------|
| `AbstractValueObject` | Héritage - Fournit `from()`, `fromJson()`, `equals()` |
| `Transformable` | Implémentation indirecte via AbstractValueObject |
| `Hydratable` | Hérité - Permet l'hydratation automatique |

## Performance

- **Création** : O(1) - simple validation
- **Méthodes** : O(1) - opérations arithmétiques et string
- **Comparaison** : O(1) - via `equals()` hérité

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

use AndyDefer\PhpVo\ValueObjects\IdVO;

// Création
$id = IdVO::from(123);
echo $id->getValue();           // 123
echo $id->toReadableString();   // 'ID #123'
echo $id->toZeroPadded(5);      // '00123'
echo $id->isValid();            // true

// Immutabilité
$incremented = $id->increment();
echo $id->getValue();           // 123 (inchangé)
echo $incremented->getValue();  // 124

// Décrémentation
$decremented = $id->decrement();
echo $decremented->getValue();  // 122

// Chaînage
$result = IdVO::from(10)
    ->increment()
    ->increment()
    ->decrement();
echo $result->getValue();       // 11

// Comparaison
$id1 = IdVO::from(123);
$id2 = IdVO::from(123);
$id3 = IdVO::from(456);

var_dump($id1->equals($id2));   // true
var_dump($id1->equals($id3));   // false

// String casting
echo $id;                       // '123'

// Gestion des erreurs
try {
    $invalid = IdVO::from(0);
} catch (InvalidArgumentException $e) {
    echo $e->getMessage(); // 'ID must be positive, got: 0'
}

try {
    $invalid = IdVO::from(-5);
} catch (InvalidArgumentException $e) {
    echo $e->getMessage(); // 'ID must be positive, got: -5'
}

try {
    $one = IdVO::from(1);
    $zero = $one->decrement(); // Exception
} catch (InvalidArgumentException $e) {
    echo $e->getMessage(); // 'ID must be positive, got: 0'
}

// Création depuis instance existante
$original = IdVO::from(123);
$duplicate = IdVO::from($original);
var_dump($original === $duplicate); // true
```

## Notes additionnelles

- L'identifiant doit toujours être positif (`> 0`)
- La classe est générique et peut être utilisée pour tout type d'identifiant (utilisateur, produit, commande, etc.)
- Les opérations d'incrémentation et décrémentation retournent de nouvelles instances (immutabilité)
- La méthode `toZeroPadded()` est utile pour les numéros de facture, tickets, etc.
- La validation est effectuée uniquement dans le constructeur, les méthodes `increment()` et `decrement()` valident également le résultat