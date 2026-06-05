# AmountVO - Référence Technique

## Description

Value Object immuable représentant un montant monétaire avec une précision de 2 décimales, utilisant bcmath pour des calculs arithmétiques précis.

## Hiérarchie

```
AbstractValueObject (andydefer/domain-structures)
    └── AmountVO
```

## Rôle principal

Encapsuler un montant monétaire avec validation stricte (2 décimales max), fournir des opérations arithmétiques précises (addition, soustraction, multiplication, division, pourcentage), et éviter les erreurs de virgule flottante grâce à bcmath.

## Prérequis

- Extension PHP `bcmath` activée

```bash
sudo apt-get install php-bcmath
```

## API / Méthodes publiques

### `getValue(): float`

| Paramètre | Type | Description |
|-----------|------|-------------|
| - | - | Aucun paramètre |

**Retourne :** `float` - Le montant sous forme de nombre flottant (pour sérialisation)

**Exemple :**
```php
$amount = AmountVO::from('99.99');
echo $amount->getValue(); // 99.99 (float)
```

---

### `toString(): string`

| Paramètre | Type | Description |
|-----------|------|-------------|
| - | - | Aucun paramètre |

**Retourne :** `string` - Le montant sous forme de chaîne avec 2 décimales (pour calculs)

**Exemple :**
```php
$amount = AmountVO::from('99.99');
echo $amount->toString(); // '99.99'
```

---

### `toFloat(): float`

| Paramètre | Type | Description |
|-----------|------|-------------|
| - | - | Aucun paramètre |

**Retourne :** `float` - Le montant sous forme de nombre flottant

**Exemple :**
```php
$amount = AmountVO::from('99.99');
echo $amount->toFloat(); // 99.99
```

---

### `toInt(): int`

| Paramètre | Type | Description |
|-----------|------|-------------|
| - | - | Aucun paramètre |

**Retourne :** `int` - Le montant en centimes (montant × 100)

**Exemple :**
```php
$amount = AmountVO::from('99.99');
echo $amount->toInt(); // 9999
```

---

### `add(self $other): self`

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$other` | `self` | Autre montant à additionner |

**Retourne :** `self` - Nouvelle instance avec la somme

**Exemple :**
```php
$amount1 = AmountVO::from('50.50');
$amount2 = AmountVO::from('25.25');
$total = $amount1->add($amount2);
echo $total; // '75.75'
```

---

### `subtract(self $other): self`

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$other` | `self` | Autre montant à soustraire |

**Retourne :** `self` - Nouvelle instance avec la différence

**Exemple :**
```php
$amount1 = AmountVO::from('100.00');
$amount2 = AmountVO::from('30.30');
$diff = $amount1->subtract($amount2);
echo $diff; // '69.70'
```

---

### `multiply(float|int|string $multiplier): self`

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$multiplier` | `float\|int\|string` | Multiplicateur |

**Retourne :** `self` - Nouvelle instance avec le produit

**Exemple :**
```php
$amount = AmountVO::from('10.50');
$result = $amount->multiply(2.5);
echo $result; // '26.25'
```

---

### `divide(float|int|string $divisor): self`

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$divisor` | `float\|int\|string` | Diviseur (ne peut pas être zéro) |

**Retourne :** `self` - Nouvelle instance avec le quotient

**Exceptions :** `InvalidArgumentException` - Si le diviseur est zéro

**Exemple :**
```php
$amount = AmountVO::from('100.00');
$result = $amount->divide(4);
echo $result; // '25.00'
```

---

### `percentage(float|int|string $percent): self`

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$percent` | `float\|int\|string` | Pourcentage à calculer (ex: 20 pour 20%) |

**Retourne :** `self` - Nouvelle instance avec la valeur du pourcentage

**Exemple :**
```php
$amount = AmountVO::from('200.00');
$result = $amount->percentage(15);
echo $result; // '30.00'
```

---

### `isZero(): bool`

| Paramètre | Type | Description |
|-----------|------|-------------|
| - | - | Aucun paramètre |

**Retourne :** `bool` - True si le montant est égal à zéro

**Exemple :**
```php
$amount = AmountVO::from('0.00');
echo $amount->isZero(); // true
```

---

### `isPositive(): bool`

| Paramètre | Type | Description |
|-----------|------|-------------|
| - | - | Aucun paramètre |

**Retourne :** `bool` - True si le montant est supérieur à zéro

**Exemple :**
```php
$amount = AmountVO::from('10.00');
echo $amount->isPositive(); // true
```

---

### `isNegative(): bool`

| Paramètre | Type | Description |
|-----------|------|-------------|
| - | - | Aucun paramètre |

**Retourne :** `bool` - True si le montant est inférieur à zéro

**Exemple :**
```php
$amount = AmountVO::from('-10.00');
echo $amount->isNegative(); // true
```

---

### `__toString(): string`

| Paramètre | Type | Description |
|-----------|------|-------------|
| - | - | Aucun paramètre |

**Retourne :** `string` - Identique à `toString()`

**Exemple :**
```php
$amount = AmountVO::from('99.99');
echo $amount; // '99.99'
```

## Cas d'utilisation

### Cas 1 : Calcul de panier

```php
<?php

declare(strict_types=1);

use AndyDefer\PhpVo\ValueObjects\AmountVO;

$items = [
    ['name' => 'Produit A', 'price' => '29.99', 'quantity' => 2],
    ['name' => 'Produit B', 'price' => '49.99', 'quantity' => 1],
];

$subtotal = AmountVO::from('0.00');

foreach ($items as $item) {
    $price = AmountVO::from($item['price']);
    $quantity = AmountVO::from($item['quantity']);
    $lineTotal = $price->multiply($quantity->toFloat());
    $subtotal = $subtotal->add($lineTotal);
}

echo $subtotal; // '109.97'
```

### Cas 2 : Calcul TVA

```php
<?php

declare(strict_types=1);

use AndyDefer\PhpVo\ValueObjects\AmountVO;

$amount = AmountVO::from('100.00');
$tvaRate = 20; // 20%

$tva = $amount->percentage($tvaRate);
$total = $amount->add($tva);

echo $tva;   // '20.00'
echo $total; // '120.00'
```

### Cas 3 : Répartition équitable

```php
<?php

declare(strict_types=1);

use AndyDefer\PhpVo\ValueObjects\AmountVO;

$total = AmountVO::from('100.00');
$people = 3;

$share = $total->divide($people);
echo $share; // '33.33'
```


## Gestion des erreurs

| Situation | Exception | Message |
|-----------|-----------|---------|
| Format invalide | `InvalidArgumentException` | `Invalid amount format: {$value}` |
| Division par zéro | `InvalidArgumentException` | `Division by zero` |
| Source non convertible | `InvalidArgumentException` | `AmountVO::from expects string, int, or float, got ...` |

## Intégration

| Composant | Relation |
|-----------|----------|
| `AbstractValueObject` | Héritage - Fournit `from()`, `fromJson()`, `equals()` |
| `Transformable` | Implémentation indirecte via AbstractValueObject |
| `bcmath` | Dépendance externe pour les calculs précis |

## Performance

- **Création** : O(1) - validation et formatage simples
- **Opérations** : O(1) - fonctions bcmath natives
- **Comparaison** : O(1) - via `bccomp()`

## Compatibilité

| Version PHP | Support |
|-------------|---------|
| PHP 8.1+ | ✅ Complet |
| PHP 8.0 | ⚠️ (bcmath extension requise) |
| PHP 7.x | ❌ Non supporté (propriétés readonly) |

## Exemple complet

```php
<?php

declare(strict_types=1);

use AndyDefer\PhpVo\ValueObjects\AmountVO;

// Création depuis différents types
$fromString = AmountVO::from('99.99');
$fromInt = AmountVO::from(100);      // '100.00'
$fromFloat = AmountVO::from(99.99);  // '99.99'

// Normalisation automatique
$withComma = AmountVO::from('99,99'); // '99.99'
$noDecimals = AmountVO::from('100');  // '100.00'
$negative = AmountVO::from('-99.99'); // '-99.99'

// Opérations arithmétiques
$total = AmountVO::from('100.00')
    ->add(AmountVO::from('50.00'))
    ->subtract(AmountVO::from('30.00'))
    ->multiply(2)
    ->percentage(10);

echo $total; // '24.00'

// Conversion
echo $total->toFloat();   // 24.00
echo $total->toInt();     // 2400 (cents)
echo $total->toString();  // '24.00'

// Comparaison
$zero = AmountVO::from('0.00');
echo $zero->isZero();      // true
echo $total->isPositive(); // true
echo $total->isNegative(); // false

// String casting
echo $total; // '24.00'
```
---