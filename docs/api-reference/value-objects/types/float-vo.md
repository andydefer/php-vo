# FloatVO - Référence Technique

## Description

`FloatVO` est un Value Object représentant un nombre flottant. Il encapsule un `float` PHP natif et fournit un large éventail d'opérations mathématiques, trigonométriques et de comparaison dans un contexte immuable et typé.

## Hiérarchie

```
AbstractValueObject
    └── FloatVO
```

**Interfaces implémentées :** `NumberInterface`, `Transformable` (via `AbstractValueObject`)

## Rôle principal

`FloatVO` encapsule une valeur flottante dans un objet immutable. Il fournit des opérations arithmétiques (`add`, `subtract`, `multiply`, `divide`), des fonctions mathématiques avancées (`sqrt`, `log`, `exp`), des fonctions trigonométriques (`sin`, `cos`, `tan`), des fonctions hyperboliques (`sinh`, `cosh`, `tanh`) ainsi que des méthodes de comparaison et d'arrondi.

Contrairement à `IntVO`, les opérations de `FloatVO` retournent **toujours** un `FloatVO`, préservant la précision des nombres à virgule flottante.

---

## Installation

```bash
composer require andy-defer/php-vo
```

```php
use AndyDefer\PhpVo\ValueObjects\Types\FloatVO;
```

---

## API / Méthodes publiques

### `__construct(float $value = 0.0)`

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$value` | `float` | Valeur flottante (0.0 par défaut) |

**Retourne :** `void`

**Exemple :**
```php
$float = new FloatVO(3.14);
```

---

### `getValue(): float`

Retourne la valeur flottante brute.

**Retourne :** `float` - La valeur encapsulée

**Exemple :**
```php
$float = FloatVO::from(3.14);
$value = $float->getValue(); // 3.14
```

---

### `add(mixed $other): static`

Additionne un autre nombre.

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$other` | `mixed` | Nombre à ajouter (converti via `::from()`) |

**Retourne :** `static` - Nouvelle instance avec la somme

**Exceptions :** `InvalidArgumentException` - Si la conversion échoue

**Exemple :**
```php
$float = FloatVO::from(10.5);
$result = $float->add(5.5); // 16.0
```

---

### `subtract(mixed $other): static`

Soustrait un autre nombre.

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$other` | `mixed` | Nombre à soustraire (converti via `::from()`) |

**Retourne :** `static` - Nouvelle instance avec la différence

**Exceptions :** `InvalidArgumentException` - Si la conversion échoue

**Exemple :**
```php
$float = FloatVO::from(10.5);
$result = $float->subtract(5.5); // 5.0
```

---

### `multiply(mixed $other): static`

Multiplie par un autre nombre.

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$other` | `mixed` | Multiplicateur (converti via `::from()`) |

**Retourne :** `static` - Nouvelle instance avec le produit

**Exceptions :** `InvalidArgumentException` - Si la conversion échoue

**Exemple :**
```php
$float = FloatVO::from(10.5);
$result = $float->multiply(2.0); // 21.0
```

---

### `divide(mixed $other): static`

Divise par un autre nombre.

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$other` | `mixed` | Diviseur (converti via `::from()`) |

**Retourne :** `static` - Nouvelle instance avec le quotient

**Exceptions :** `InvalidArgumentException` - Si division par zéro ou conversion échoue

**Exemple :**
```php
$float = FloatVO::from(10.5);
$result = $float->divide(2.0); // 5.25
```

---

### `mod(mixed $other): static`

Reste de la division (modulo).

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$other` | `mixed` | Diviseur (converti via `::from()`) |

**Retourne :** `static` - Nouvelle instance avec le reste

**Exceptions :** `InvalidArgumentException` - Si modulo par zéro ou conversion échoue

**Exemple :**
```php
$float = FloatVO::from(10.5);
$result = $float->mod(3.0); // 1.5
```

---

### `pow(mixed $exponent): static`

Élève à une puissance.

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$exponent` | `mixed` | Exposant (converti via `::from()`) |

**Retourne :** `static` - Nouvelle instance avec la puissance

**Exceptions :** `InvalidArgumentException` - Si la conversion échoue

**Exemple :**
```php
$float = FloatVO::from(2.0);
$result = $float->pow(3.0); // 8.0
```

---

### `increment(mixed $by = null): static`

Incrémente la valeur.

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$by` | `mixed` | Valeur d'incrémentation (défaut: 1.0) |

**Retourne :** `static` - Nouvelle instance incrémentée

**Exceptions :** `InvalidArgumentException` - Si la conversion échoue

**Exemple :**
```php
$float = FloatVO::from(5.5);
$result = $float->increment(); // 6.5
$result = $float->increment(3.5); // 9.0
```

---

### `decrement(mixed $by = null): static`

Décrémente la valeur.

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$by` | `mixed` | Valeur de décrémentation (défaut: 1.0) |

**Retourne :** `static` - Nouvelle instance décrémentée

**Exceptions :** `InvalidArgumentException` - Si la conversion échoue

**Exemple :**
```php
$float = FloatVO::from(5.5);
$result = $float->decrement(); // 4.5
$result = $float->decrement(3.5); // 2.0
```

---

### `abs(): static`

Valeur absolue.

**Retourne :** `static` - Nouvelle instance avec la valeur absolue

**Exemple :**
```php
$float = FloatVO::from(-5.5);
$result = $float->abs(); // 5.5
```

---

### `negate(): static`

Négation (inverse de signe).

**Retourne :** `static` - Nouvelle instance avec la valeur négative

**Exemple :**
```php
$float = FloatVO::from(5.5);
$result = $float->negate(); // -5.5
```

---

### `sqrt(): static`

Racine carrée.

**Retourne :** `static` - Nouvelle instance avec la racine carrée

**Exceptions :** `InvalidArgumentException` - Si la valeur est négative

**Exemple :**
```php
$float = FloatVO::from(16.0);
$result = $float->sqrt(); // 4.0
```

---

### `log(): static`

Logarithme népérien (base e).

**Retourne :** `static` - Nouvelle instance avec le logarithme

**Exceptions :** `InvalidArgumentException` - Si la valeur est négative ou nulle

**Exemple :**
```php
$float = FloatVO::from(exp(1));
$result = $float->log(); // 1.0
```

---

### `log10(): static`

Logarithme en base 10.

**Retourne :** `static` - Nouvelle instance avec le logarithme

**Exceptions :** `InvalidArgumentException` - Si la valeur est négative ou nulle

**Exemple :**
```php
$float = FloatVO::from(100.0);
$result = $float->log10(); // 2.0
```

---

### `logBase(mixed $base): static`

Logarithme dans une base personnalisée.

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$base` | `mixed` | Base du logarithme (converti via `::from()`) |

**Retourne :** `static` - Nouvelle instance avec le logarithme

**Exceptions :** `InvalidArgumentException` - Si paramètres invalides

**Exemple :**
```php
$float = FloatVO::from(8.0);
$result = $float->logBase(2.0); // 3.0
```

---

### `exp(): static`

Exponentielle (e^x).

**Retourne :** `static` - Nouvelle instance avec l'exponentielle

**Exemple :**
```php
$float = FloatVO::from(1.0);
$result = $float->exp(); // 2.718...
```

---

### `sin(): static`

Sinus.

**Retourne :** `static` - Nouvelle instance avec le sinus

**Exemple :**
```php
$float = FloatVO::from(0);
$result = $float->sin(); // 0.0
```

---

### `cos(): static`

Cosinus.

**Retourne :** `static` - Nouvelle instance avec le cosinus

**Exemple :**
```php
$float = FloatVO::from(0);
$result = $float->cos(); // 1.0
```

---

### `tan(): static`

Tangente.

**Retourne :** `static` - Nouvelle instance avec la tangente

**Exemple :**
```php
$float = FloatVO::from(0);
$result = $float->tan(); // 0.0
```

---

### `asin(): static`

Arcsinus.

**Retourne :** `static` - Nouvelle instance avec l'arcsinus

**Exceptions :** `InvalidArgumentException` - Si la valeur est hors de l'intervalle [-1, 1]

**Exemple :**
```php
$float = FloatVO::from(0.5);
$result = $float->asin(); // 0.523...
```

---

### `acos(): static`

Arccosinus.

**Retourne :** `static` - Nouvelle instance avec l'arccosinus

**Exceptions :** `InvalidArgumentException` - Si la valeur est hors de l'intervalle [-1, 1]

**Exemple :**
```php
$float = FloatVO::from(0.5);
$result = $float->acos(); // 1.047...
```

---

### `atan(): static`

Arctangente.

**Retourne :** `static` - Nouvelle instance avec l'arctangente

**Exemple :**
```php
$float = FloatVO::from(1.0);
$result = $float->atan(); // 0.785...
```

---

### `atan2(mixed $x): static`

Arctangente de deux variables (y/x).

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$x` | `mixed` | Coordonnée x (convertie via `::from()`) |

**Retourne :** `static` - Nouvelle instance avec l'arctangente

**Exceptions :** `InvalidArgumentException` - Si la conversion échoue

**Exemple :**
```php
$float = FloatVO::from(1.0);
$result = $float->atan2(1.0); // 0.785...
```

---

### `sinh(): static`

Sinus hyperbolique.

**Retourne :** `static` - Nouvelle instance avec le sinus hyperbolique

**Exemple :**
```php
$float = FloatVO::from(0);
$result = $float->sinh(); // 0.0
```

---

### `cosh(): static`

Cosinus hyperbolique.

**Retourne :** `static` - Nouvelle instance avec le cosinus hyperbolique

**Exemple :**
```php
$float = FloatVO::from(0);
$result = $float->cosh(); // 1.0
```

---

### `tanh(): static`

Tangente hyperbolique.

**Retourne :** `static` - Nouvelle instance avec la tangente hyperbolique

**Exemple :**
```php
$float = FloatVO::from(0);
$result = $float->tanh(); // 0.0
```

---

### `round(mixed $precision = null): static`

Arrondit à la précision spécifiée.

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$precision` | `mixed` | Nombre de décimales (défaut: 0) |

**Retourne :** `static` - Nouvelle instance arrondie

**Exceptions :** `InvalidArgumentException` - Si la conversion échoue

**Exemple :**
```php
$float = FloatVO::from(3.14159);
$result = $float->round(2); // 3.14
```

---

### `ceil(): static`

Plafond (arrondi à l'entier supérieur).

**Retourne :** `static` - Nouvelle instance avec la valeur plafond

**Exemple :**
```php
$float = FloatVO::from(3.14);
$result = $float->ceil(); // 4.0
```

---

### `floor(): static`

Plancher (arrondi à l'entier inférieur).

**Retourne :** `static` - Nouvelle instance avec la valeur plancher

**Exemple :**
```php
$float = FloatVO::from(3.14);
$result = $float->floor(); // 3.0
```

---

### `toInt(): IntVO`

Convertit en entier (troncature).

**Retourne :** `IntVO` - La valeur convertie en entier

**Exemple :**
```php
$float = FloatVO::from(3.9);
$int = $float->toInt(); // IntVO(3)
```

---

### `toFloat(): static`

Retourne l'instance elle-même.

**Retourne :** `static` - L'instance actuelle

**Exemple :**
```php
$float = FloatVO::from(3.14);
$same = $float->toFloat(); // $float === $same
```

---

### `toIntCeil(): IntVO`

Convertit en entier plafond.

**Retourne :** `IntVO` - La valeur convertie en entier supérieur

**Exemple :**
```php
$float = FloatVO::from(3.1);
$int = $float->toIntCeil(); // IntVO(4)
```

---

### `toIntFloor(): IntVO`

Convertit en entier plancher.

**Retourne :** `IntVO` - La valeur convertie en entier inférieur

**Exemple :**
```php
$float = FloatVO::from(3.9);
$int = $float->toIntFloor(); // IntVO(3)
```

---

### `toIntRound(): IntVO`

Convertit en entier arrondi.

**Retourne :** `IntVO` - La valeur convertie en entier arrondi

**Exemple :**
```php
$float = FloatVO::from(3.5);
$int = $float->toIntRound(); // IntVO(4)
```

---

### `isZero(): BoolVO`

Vérifie si la valeur est zéro.

**Retourne :** `BoolVO` - `true` si la valeur est 0.0

**Exemple :**
```php
$float = FloatVO::from(0.0);
$result = $float->isZero(); // true
```

---

### `isPositive(): BoolVO`

Vérifie si la valeur est positive.

**Retourne :** `BoolVO` - `true` si la valeur > 0

**Exemple :**
```php
$float = FloatVO::from(5.5);
$result = $float->isPositive(); // true
```

---

### `isNegative(): BoolVO`

Vérifie si la valeur est négative.

**Retourne :** `BoolVO` - `true` si la valeur < 0

**Exemple :**
```php
$float = FloatVO::from(-5.5);
$result = $float->isNegative(); // true
```

---

### `isEven(): BoolVO`

Vérifie si la valeur est paire.

**Retourne :** `BoolVO` - `true` si la valeur est paire

**Exemple :**
```php
$float = FloatVO::from(4.0);
$result = $float->isEven(); // true
```

---

### `isOdd(): BoolVO`

Vérifie si la valeur est impaire.

**Retourne :** `BoolVO` - `true` si la valeur est impaire

**Exemple :**
```php
$float = FloatVO::from(5.0);
$result = $float->isOdd(); // true
```

---

### `isInteger(): BoolVO`

Vérifie si la valeur est un entier (pas de partie décimale).

**Retourne :** `BoolVO` - `true` si floor($value) === $value

**Exemple :**
```php
$float = FloatVO::from(5.0);
$result = $float->isInteger(); // true
```

---

### `isFinite(): BoolVO`

Vérifie si la valeur est finie.

**Retourne :** `BoolVO` - `true` si la valeur est finie

**Exemple :**
```php
$float = FloatVO::from(5.0);
$result = $float->isFinite(); // true
```

---

### `isInfinite(): BoolVO`

Vérifie si la valeur est infinie.

**Retourne :** `BoolVO` - `true` si la valeur est infinie

**Exemple :**
```php
$float = FloatVO::from(INF);
$result = $float->isInfinite(); // true
```

---

### `isNan(): BoolVO`

Vérifie si la valeur est NaN (Not a Number).

**Retourne :** `BoolVO` - `true` si la valeur est NaN

**Exemple :**
```php
$float = FloatVO::from(NAN);
$result = $float->isNan(); // true
```

---

### `isPrime(): BoolVO`

Vérifie si la valeur est un nombre premier (toujours false pour les floats).

**Retourne :** `BoolVO` - `false` (les floats ne sont pas premiers)

**Exemple :**
```php
$float = FloatVO::from(7.0);
$result = $float->isPrime(); // false
```

---

### `isDivisibleBy(mixed $other): BoolVO`

Vérifie si la valeur est divisible par un autre nombre.

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$other` | `mixed` | Diviseur (converti via `::from()`) |

**Retourne :** `BoolVO` - `true` si le résultat de la division est un entier

**Exceptions :** `InvalidArgumentException` - Si division par zéro ou conversion échoue

**Exemple :**
```php
$float = FloatVO::from(10.0);
$result = $float->isDivisibleBy(2.0); // true
$result = $float->isDivisibleBy(3.0); // false
```

---

### `sign(): static`

Retourne le signe du nombre.

**Retourne :** `static` - 1.0, -1.0 ou 0.0

**Exemple :**
```php
$float = FloatVO::from(10.0);
$sign = $float->sign(); // 1.0
```

---

### `compare(mixed $other): static`

Compare avec un autre nombre.

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$other` | `mixed` | Nombre à comparer (converti via `::from()`) |

**Retourne :** `static` - -1.0, 0.0 ou 1.0

**Exceptions :** `InvalidArgumentException` - Si la conversion échoue

**Exemple :**
```php
$float = FloatVO::from(10.0);
$result = $float->compare(5.0); // 1.0
$result = $float->compare(10.0); // 0.0
$result = $float->compare(15.0); // -1.0
```

---

### `equal(mixed $other): BoolVO`

Vérifie l'égalité avec un autre nombre.

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$other` | `mixed` | Nombre à comparer (converti via `::from()`) |

**Retourne :** `BoolVO` - `true` si les valeurs sont égales

**Exceptions :** `InvalidArgumentException` - Si la conversion échoue

**Exemple :**
```php
$float = FloatVO::from(10.0);
$result = $float->equal(10.0); // true
```

---

### `greaterThan(mixed $other): BoolVO`

Vérifie si la valeur est supérieure.

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$other` | `mixed` | Nombre à comparer (converti via `::from()`) |

**Retourne :** `BoolVO` - `true` si la valeur > other

**Exceptions :** `InvalidArgumentException` - Si la conversion échoue

**Exemple :**
```php
$float = FloatVO::from(10.0);
$result = $float->greaterThan(5.0); // true
```

---

### `greaterThanOrEqual(mixed $other): BoolVO`

Vérifie si la valeur est supérieure ou égale.

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$other` | `mixed` | Nombre à comparer (converti via `::from()`) |

**Retourne :** `BoolVO` - `true` si la valeur >= other

**Exceptions :** `InvalidArgumentException` - Si la conversion échoue

**Exemple :**
```php
$float = FloatVO::from(10.0);
$result = $float->greaterThanOrEqual(10.0); // true
```

---

### `lessThan(mixed $other): BoolVO`

Vérifie si la valeur est inférieure.

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$other` | `mixed` | Nombre à comparer (converti via `::from()`) |

**Retourne :** `BoolVO` - `true` si la valeur < other

**Exceptions :** `InvalidArgumentException` - Si la conversion échoue

**Exemple :**
```php
$float = FloatVO::from(10.0);
$result = $float->lessThan(15.0); // true
```

---

### `lessThanOrEqual(mixed $other): BoolVO`

Vérifie si la valeur est inférieure ou égale.

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$other` | `mixed` | Nombre à comparer (converti via `::from()`) |

**Retourne :** `BoolVO` - `true` si la valeur <= other

**Exceptions :** `InvalidArgumentException` - Si la conversion échoue

**Exemple :**
```php
$float = FloatVO::from(10.0);
$result = $float->lessThanOrEqual(10.0); // true
```

---

### `min(mixed $other): static`

Retourne le minimum entre la valeur et un autre nombre.

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$other` | `mixed` | Nombre à comparer (converti via `::from()`) |

**Retourne :** `static` - La plus petite valeur

**Exceptions :** `InvalidArgumentException` - Si la conversion échoue

**Exemple :**
```php
$float = FloatVO::from(10.0);
$result = $float->min(5.0); // 5.0
```

---

### `max(mixed $other): static`

Retourne le maximum entre la valeur et un autre nombre.

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$other` | `mixed` | Nombre à comparer (converti via `::from()`) |

**Retourne :** `static` - La plus grande valeur

**Exceptions :** `InvalidArgumentException` - Si la conversion échoue

**Exemple :**
```php
$float = FloatVO::from(10.0);
$result = $float->max(5.0); // 10.0
```

---

### `clamp(mixed $min, mixed $max): static`

Contraint la valeur entre un minimum et un maximum.

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$min` | `mixed` | Minimum (converti via `::from()`) |
| `$max` | `mixed` | Maximum (converti via `::from()`) |

**Retourne :** `static` - La valeur contrainte

**Exceptions :** `InvalidArgumentException` - Si min > max ou conversion échoue

**Exemple :**
```php
$float = FloatVO::from(10.0);
$result = $float->clamp(0.0, 5.0); // 5.0
```

---

### `equalsWithTolerance(mixed $other, float $tolerance = 1e-12): BoolVO`

Compare avec tolérance (utile pour les flottants).

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$other` | `mixed` | Nombre à comparer (converti via `::from()`) |
| `$tolerance` | `float` | Tolérance (défaut: 1e-12) |

**Retourne :** `BoolVO` - `true` si |value - other| < tolerance

**Exceptions :** `InvalidArgumentException` - Si la conversion échoue

**Exemple :**
```php
$float = FloatVO::from(0.1 + 0.2);
$result = $float->equalsWithTolerance(0.3, 1e-10); // true
```

---

### `format(...): StringVO`

Formate le nombre en chaîne.

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$decimals` | `mixed` | Nombre de décimales (défaut: 0) |
| `$decimalSeparator` | `mixed` | Séparateur décimal (défaut: '.') |
| `$thousandsSeparator` | `mixed` | Séparateur de milliers (défaut: ',') |

**Retourne :** `StringVO` - La valeur formatée

**Exceptions :** `InvalidArgumentException` - Si la conversion échoue

**Exemple :**
```php
$float = FloatVO::from(1234567.89);
$result = $float->format(2, ',', ' '); // '1 234 567,89'
```

---

### `__toString(): string`

Représentation textuelle du nombre.

**Retourne :** `string` - La valeur en chaîne

**Exemple :**
```php
$float = FloatVO::from(3.14);
echo $float; // '3.14'
```

---

## Cas d'utilisation

### Cas 1 : Calculs financiers

```php
use AndyDefer\PhpVo\ValueObjects\Types\FloatVO;

class PriceCalculator
{
    public function calculateTotal(FloatVO $price, FloatVO $quantity, FloatVO $tax): FloatVO
    {
        return $price
            ->multiply($quantity)    // Prix total HT
            ->multiply($tax->add(1)); // TVA incluse
    }
}

$price = FloatVO::from(99.99);
$quantity = FloatVO::from(3);
$tax = FloatVO::from(0.20); // 20%

$total = (new PriceCalculator())->calculateTotal($price, $quantity, $tax);
echo $total->format(2); // '359.96'
```

### Cas 2 : Calculs scientifiques

```php
use AndyDefer\PhpVo\ValueObjects\Types\FloatVO;

class PhysicsCalculator
{
    public function calculateEnergy(FloatVO $mass, FloatVO $speedOfLight): FloatVO
    {
        return $mass->multiply($speedOfLight->pow(2));
    }

    public function calculateAngle(FloatVO $opposite, FloatVO $adjacent): FloatVO
    {
        return $opposite->divide($adjacent)->atan();
    }
}

$mass = FloatVO::from(10.0);
$c = FloatVO::from(299792458.0);
$energy = (new PhysicsCalculator())->calculateEnergy($mass, $c);
echo $energy->format(0); // '8.987551787e+17'
```

### Cas 3 : Comparaisons avec tolérance

```php
use AndyDefer\PhpVo\ValueObjects\Types\FloatVO;

$result = FloatVO::from(0.1)->add(0.2); // 0.30000000000000004

// Sans tolérance
$isEqual = $result->equal(0.3); // false ❌

// Avec tolérance
$isEqual = $result->equalsWithTolerance(0.3, 1e-10); // true ✅
```

---

## Flux d'exécution

```
Création (new FloatVO() ou ::from())
    ↓
Opération arithmétique (add, subtract, multiply, divide...)
    ↓
Nouvelle instance FloatVO (immutable)
    ↓
Fonction mathématique (sqrt, log, sin, cos...)
    ↓
Nouvelle instance FloatVO (immutable)
    ↓
Comparaison ou conversion
    ↓
Résultat
```

**Immutable :** Chaque opération retourne une **nouvelle instance**.

```php
$original = FloatVO::from(10.5);
$result = $original->add(5.5);

$original->getValue(); // 10.5 - inchangé
$result->getValue();   // 16.0 - nouvelle instance
```

---

## Gestion des erreurs

| Situation | Exception | Message |
|-----------|-----------|---------|
| Division par zéro | `InvalidArgumentException` | `Division by zero is not allowed` |
| Modulo par zéro | `InvalidArgumentException` | `Modulo by zero is not allowed` |
| Racine carrée négative | `InvalidArgumentException` | `Square root of negative number is not defined` |
| Logarithme non-positif | `InvalidArgumentException` | `Logarithm of non-positive number is not defined` |
| Arcsinus hors intervalle | `InvalidArgumentException` | `Arcsin argument must be between -1 and 1` |
| Arccosinus hors intervalle | `InvalidArgumentException` | `Arccos argument must be between -1 and 1` |
| Min > Max dans clamp | `InvalidArgumentException` | `Min cannot be greater than max` |
| Logarithme base invalide | `InvalidArgumentException` | `Invalid logarithm parameters` |
| Conversion impossible via `::from()` | `InvalidArgumentException` | `Cannot convert value to float for parameter $value` |

---

## Performance

| Opération | Complexité | Notes |
|-----------|------------|-------|
| `getValue()` | O(1) | Retour direct |
| `add()`, `subtract()`, etc. | O(1) | Opération flottante simple |
| `sqrt()`, `log()`, etc. | O(1) | Fonctions C natives |
| `sin()`, `cos()`, etc. | O(1) | Fonctions C natives |
| `round()`, `ceil()`, `floor()` | O(1) | Fonctions C natives |
| `format()` | O(1) | Fonction C native |

---

## Compatibilité

| Version PHP | Support | Notes |
|-------------|---------|-------|
| PHP 8.1+ | ✅ Complet | Types union, mixed, etc. |
| PHP 8.0 | ✅ Complet | Supporté |
| PHP 7.4 | ❌ Non | Nécessite PHP 8.0+ |

---

## Exemple complet

```php
<?php

declare(strict_types=1);

use AndyDefer\PhpVo\ValueObjects\Types\FloatVO;

// Création
$float = FloatVO::from(10.5);

// Opérations arithmétiques
$result = $float
    ->add(5.5)          // 16.0
    ->multiply(2.0)     // 32.0
    ->subtract(10.0)    // 22.0
    ->divide(4.0);      // 5.5

// Fonctions mathématiques
$sqrt = $float->sqrt();            // 3.240...
$log = $float->log();              // 2.351...
$exp = $float->exp();              // 36315.5

// Trigonométrie
$sin = $float->sin();              // -0.876...
$cos = $float->cos();              // 0.471...
$tan = $float->tan();              // -1.858...

// Arrondi
$rounded = $float->round(1);       // 10.5
$ceil = $float->ceil();            // 11.0
$floor = $float->floor();          // 10.0

// Comparaisons
$isEqual = $float->equal(10.5);                // true
$isGreater = $float->greaterThan(5.0);         // true
$isLess = $float->lessThan(15.0);              // true
$clamped = $float->clamp(0.0, 5.0);            // 5.0

// Vérifications
$isPositive = $float->isPositive();            // true
$isInteger = $float->isInteger();              // false

// Comparaison avec tolérance
$result = FloatVO::from(0.1)->add(0.2);
$isEqualWithTolerance = $result->equalsWithTolerance(0.3, 1e-10); // true

// Conversion
$int = $float->toInt();                        // IntVO(10)
$intCeil = $float->toIntCeil();                // IntVO(11)
$intFloor = $float->toIntFloor();              // IntVO(10)
$intRound = $float->toIntRound();              // IntVO(11)

// Formatage
$formatted = $float->format(2, ',', ' ');      // '10,50'

echo $float; // '10.5'
```

---

## Voir aussi

- `IntVO` - Value Object pour les entiers
- `BoolVO` - Value Object pour les booléens
- `StringVO` - Value Object pour les chaînes
- `NumberInterface` - Interface commune pour les nombres
- `AbstractValueObject` - Classe de base pour les Value Objects