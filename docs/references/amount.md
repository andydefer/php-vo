# Amount - Value Object Documentation

## Table des matières

1. [Description](#1-description)
2. [Installation](#2-installation)
3. [Création d'un Amount](#3-création-dun-amount)
4. [Méthodes principales](#4-méthodes-principales)
5. [Opérations arithmétiques](#5-opérations-arithmétiques)
6. [Comparaisons](#6-comparaisons)
7. [Conversions](#7-conversions)
8. [Formatage](#8-formatage)
9. [Exemples complets](#9-exemples-complets)
10. [Bonnes pratiques](#10-bonnes-pratiques)

---

## 1. Description

`Amount` est un Value Object qui représente une quantité monétaire ou numérique avec une précision de **2 décimales**. Il est **immutable** et utilise les fonctions `bcmath` pour des calculs précis sans erreurs d'arrondi.

### Caractéristiques

| Caractéristique | Description |
|-----------------|-------------|
| **Précision** | 2 décimales fixes |
| **Immutabilité** | Toute opération retourne une nouvelle instance |
| **Précision numérique** | Utilise `bcmath` (pas d'erreurs de flottants) |
| **Validation** | Format et décimales validés à la construction |
| **Sérialisation** | `getValue()` retourne un `float` pour JSON |

---

## 2. Installation

### Prérequis

```bash
composer require andydefer/php-vo
```

**Extension PHP requise :**
```bash
sudo apt-get install php-bcmath
```

**Vérification :**
```bash
php -m | grep bcmath
```

---

## 3. Création d'un Amount

### 3.1. Depuis une chaîne

```php
use AndyDefer\PhpVo\ValueObjects\Amount;

$amount = Amount::from('99.99');
$amount = Amount::from('100');        // Devient '100.00'
$amount = Amount::from('99,99');      // Virgule acceptée → '99.99'
$amount = Amount::from('-50.50');     // Valeur négative
```

### 3.2. Depuis un entier

```php
$amount = Amount::from(100);          // '100.00'
$amount = Amount::from(0);            // '0.00'
```

### 3.3. Depuis un flottant

```php
$amount = Amount::from(99.99);        // '99.99'
$amount = Amount::from(99.999);       // Arrondi à '100.00'
```

### 3.4. Format invalide

```php
try {
    $amount = Amount::from('abc');
} catch (InvalidArgumentException $e) {
    echo $e->getMessage(); // 'Invalid amount format: '
}
```

---

## 4. Méthodes principales

### 4.1. `getValue(): float`

Retourne la valeur pour la sérialisation (JSON, base de données) :

```php
$amount = Amount::from('99.99');
$value = $amount->getValue(); // 99.99 (float)
```

### 4.2. `toString(): string`

Retourne la valeur au format string pour les calculs :

```php
$amount = Amount::from('99.99');
$string = $amount->toString(); // '99.99'
```

### 4.3. `__toString(): string`

Magic method pour l'affichage :

```php
$amount = Amount::from('99.99');
echo $amount; // '99.99'
```

---

## 5. Opérations arithmétiques

### 5.1. Addition

```php
$a = Amount::from('50.50');
$b = Amount::from('25.25');
$result = $a->add($b); // '75.75'
```

### 5.2. Soustraction

```php
$a = Amount::from('100.00');
$b = Amount::from('30.30');
$result = $a->subtract($b); // '69.70'
```

### 5.3. Multiplication

```php
$amount = Amount::from('10.50');
$result = $amount->multiply(2.5); // '26.25'
$result = $amount->multiply('2.5'); // String accepté
```

### 5.4. Division

```php
$amount = Amount::from('100.00');
$result = $amount->divide(4); // '25.00'
```

**⚠️ Division par zéro :**
```php
try {
    $amount->divide(0);
} catch (InvalidArgumentException $e) {
    echo $e->getMessage(); // 'Division by zero'
}
```

### 5.5. Pourcentage

```php
$amount = Amount::from('200.00');
$result = $amount->percentage(15);   // '30.00'
$result = $amount->percentage(15.5); // '31.00'
```

---

## 6. Comparaisons

### 6.1. `isZero(): bool`

```php
$amount = Amount::from('0.00');
$amount->isZero(); // true

$amount = Amount::from('10.00');
$amount->isZero(); // false
```

### 6.2. `isPositive(): bool`

```php
$amount = Amount::from('10.00');
$amount->isPositive(); // true

$amount = Amount::from('-10.00');
$amount->isPositive(); // false

$amount = Amount::from('0.00');
$amount->isPositive(); // false
```

### 6.3. `isNegative(): bool`

```php
$amount = Amount::from('-10.00');
$amount->isNegative(); // true

$amount = Amount::from('10.00');
$amount->isNegative(); // false
```

### 6.4. Comparaison avec `equals()` (hérité)

```php
$a = Amount::from('99.99');
$b = Amount::from('99.99');
$a->equals($b); // true
```

---

## 7. Conversions

### 7.1. `toFloat(): float`

```php
$amount = Amount::from('99.99');
$float = $amount->toFloat(); // 99.99
```

### 7.2. `toInt(): int`

Retourne la valeur en cents :

```php
$amount = Amount::from('99.99');
$cents = $amount->toInt(); // 9999
```

---

## 8. Formatage

### 8.1. Affichage simple

```php
echo Amount::from('99.99');     // '99.99'
echo Amount::from('100');       // '100.00'
echo Amount::from('99,99');     // '99.99'
```

### 8.2. Formatage personnalisé

```php
$amount = Amount::from('99.99');

// Pour l'affichage utilisateur
echo number_format($amount->toFloat(), 2, ',', ' '); // "99,99"

// Pour l'API
return response()->json(['amount' => $amount->getValue()]); // 99.99
```

---

## 9. Exemples complets

### 9.1. Calcul de TVA

```php
$price = Amount::from('100.00');
$taxRate = 20;

$tax = $price->percentage($taxRate);     // '20.00'
$total = $price->add($tax);              // '120.00'

echo "HT: {$price}\n";        // HT: 100.00
echo "TVA: {$tax}\n";         // TVA: 20.00
echo "TTC: {$total}\n";       // TTC: 120.00
```

### 9.2. Panier d'achat

```php
class CartItem
{
    public function __construct(
        private Amount $price,
        private int $quantity
    ) {}
    
    public function getTotal(): Amount
    {
        return $this->price->multiply($this->quantity);
    }
}

$item1 = new CartItem(Amount::from('49.99'), 2);
$item2 = new CartItem(Amount::from('29.99'), 1);

$total = $item1->getTotal()->add($item2->getTotal());
echo "Total: {$total}"; // Total: 129.97
```

### 9.3. Remise

```php
$original = Amount::from('100.00');
$discountPercent = 15;

$discount = $original->percentage($discountPercent); // '15.00'
$final = $original->subtract($discount);              // '85.00'

echo "Prix original: {$original}\n";
echo "Remise: {$discount}\n";
echo "Prix final: {$final}\n";
```

### 9.4. Immutabilité

```php
$original = Amount::from('100.00');
$new = $original->add(Amount::from('50.00'));

$original->toString(); // '100.00' (inchangé)
$new->toString();      // '150.00'

$original === $new;    // false
```

### 9.5. Chaînage d'opérations

```php
$result = Amount::from('100.00')
    ->add(Amount::from('50.00'))
    ->subtract(Amount::from('30.00'))
    ->multiply(2)
    ->percentage(10);

echo $result; // '24.00'
```

---

## 10. Bonnes pratiques

### 10.1. Toujours utiliser `from()` pour créer

```php
// ✅ Bon
$amount = Amount::from('99.99');

// ❌ Mauvais - contourne la validation
$amount = new Amount('99.99');
```

### 10.2. Utiliser `toString()` pour les calculs internes

```php
// ✅ Bon - précis
$result = $amount->add($other);

// ❌ Mauvais - perte de précision possible
$result = $amount->getValue() + $other->getValue();
```

### 10.3. Stocker en base de données

```php
// ✅ Bon - stocker comme DECIMAL(10,2)
$db->insert('orders', [
    'total' => $total->getValue()  // float
]);

// ✅ Alternative - stocker en cents (integer)
$db->insert('orders', [
    'total_cents' => $total->toInt()  // integer
]);
```

### 10.4. Retour API

```php
// ✅ Bon - getValue() retourne un float
return response()->json([
    'amount' => $amount->getValue()  // 99.99 (number)
]);

// ❌ Mauvais - toString() retourne une string
return response()->json([
    'amount' => $amount->toString()  // "99.99" (string)
]);
```

### 10.5. Immutabilité

```php
// ✅ Bon - nouvelle instance
$newAmount = $amount->add($other);

// ❌ Mauvais - modification impossible
$amount->value = '150.00'; // Erreur
```

---

## 11. Dépendances

| Dépendance | Utilisation |
|------------|-------------|
| `bcmath` | Calculs précis (addition, soustraction, multiplication, division) |
| `AbstractValueObject` | Héritage (getValue, equals, from, etc.) |

---

## 12. Résumé des méthodes

| Méthode | Retour | Description |
|---------|--------|-------------|
| `from(mixed)` | `static` | Crée une instance |
| `getValue()` | `float` | Valeur pour sérialisation |
| `toString()` | `string` | Valeur pour calculs |
| `toFloat()` | `float` | Conversion en float |
| `toInt()` | `int` | Conversion en cents |
| `add(self)` | `self` | Addition |
| `subtract(self)` | `self` | Soustraction |
| `multiply(float\|int\|string)` | `self` | Multiplication |
| `divide(float\|int\|string)` | `self` | Division |
| `percentage(float\|int\|string)` | `self` | Pourcentage |
| `isZero()` | `bool` | Test zéro |
| `isPositive()` | `bool` | Test positif |
| `isNegative()` | `bool` | Test négatif |
| `equals(self)` | `bool` | Égalité |
| `__toString()` | `string` | Affichage |

---

## Support

- **Package** : `andydefer/php-vo`
- **Documentation** : [VALUE_OBJECTS.md](https://github.com/andydefer/domain-structures/blob/main/concepts/VALUE_OBJECTS.md)
- **Issues** : [GitHub Issues](https://github.com/andydefer/php-vo/issues)