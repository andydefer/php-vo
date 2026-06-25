# BoolVO - Référence Technique

## Description

`BoolVO` est un Value Object représentant une valeur booléenne. Il encapsule un booléen PHP natif et fournit des opérations logiques, des comparaisons et des conversions sécurisées dans un contexte immuable et typé.

## Hiérarchie

```
AbstractValueObject
    └── BoolVO
```

**Interfaces implémentées :** `Transformable` (via `AbstractValueObject`)

## Rôle principal

`BoolVO` encapsule une valeur booléenne (`true` ou `false`) dans un objet immutable. Il fournit un ensemble d'opérateurs logiques (`and`, `or`, `xor`, `not`, `nand`, `nor`, `xnor`) ainsi que des méthodes de comparaison et de conversion vers d'autres types (`int`, `float`, `string`).

L'objectif est d'éviter les erreurs liées à l'utilisation de booléens natifs en PHP (notamment les comparaisons lâches) et de fournir une API expressive pour les opérations logiques.

---

## Installation

```bash
composer require andy-defer/php-vo
```

```php
use AndyDefer\PhpVo\ValueObjects\Types\BoolVO;
```

---

## API / Méthodes publiques

### `__construct(bool $value)`

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$value` | `bool` | La valeur booléenne (true ou false) |

**Retourne :** `void`

**Exemple :**
```php
$bool = new BoolVO(true);
```

---

### `getValue(): bool`

Retourne la valeur booléenne brute.

**Retourne :** `bool` - La valeur booléenne encapsulée

**Exemple :**
```php
$bool = BoolVO::from(true);
$value = $bool->getValue(); // true
```

---

### `and(mixed $other): self`

Opérateur logique ET (`&&`).

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$other` | `mixed` | Valeur à combiner (convertie via `::from()`) |

**Retourne :** `self` - Nouvelle instance avec le résultat de `$this->value && $other`

**Exceptions :** `InvalidArgumentException` - Si la conversion échoue

**Exemple :**
```php
$isValid = BoolVO::from($user->isActive())
    ->and($user->hasValidEmail());
```

---

### `or(mixed $other): self`

Opérateur logique OU (`||`).

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$other` | `mixed` | Valeur à combiner (convertie via `::from()`) |

**Retourne :** `self` - Nouvelle instance avec le résultat de `$this->value || $other`

**Exceptions :** `InvalidArgumentException` - Si la conversion échoue

**Exemple :**
```php
$canAccess = BoolVO::from($user->isAdmin())
    ->or($user->hasPermission('edit'));
```

---

### `xor(mixed $other): self`

Opérateur logique OU exclusif (`xor`).

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$other` | `mixed` | Valeur à combiner (convertie via `::from()`) |

**Retourne :** `self` - Nouvelle instance avec le résultat de `$this->value xor $other`

**Exceptions :** `InvalidArgumentException` - Si la conversion échoue

**Exemple :**
```php
$isDifferent = BoolVO::from($user->isActive())
    ->xor($user->isSuspended());
```

---

### `not(): self`

Opérateur logique NON (`!`).

**Retourne :** `self` - Nouvelle instance avec la valeur inversée

**Exemple :**
```php
$isInvalid = BoolVO::from($user->isActive())->not();
```

---

### `nand(mixed $other): self`

Opérateur logique NON-ET (`!(A && B)`).

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$other` | `mixed` | Valeur à combiner (convertie via `::from()`) |

**Retourne :** `self` - Nouvelle instance avec le résultat de `!($this->value && $other)`

**Exceptions :** `InvalidArgumentException` - Si la conversion échoue

**Exemple :**
```php
$notBothActive = BoolVO::from($user1->isActive())
    ->nand($user2->isActive());
```

---

### `nor(mixed $other): self`

Opérateur logique NON-OU (`!(A || B)`).

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$other` | `mixed` | Valeur à combiner (convertie via `::from()`) |

**Retourne :** `self` - Nouvelle instance avec le résultat de `!($this->value || $other)`

**Exceptions :** `InvalidArgumentException` - Si la conversion échoue

**Exemple :**
```php
$isInactive = BoolVO::from($user->isActive())
    ->nor($user->isPending());
```

---

### `xnor(mixed $other): self`

Opérateur logique NON-OU exclusif (égalité logique).

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$other` | `mixed` | Valeur à combiner (convertie via `::from()`) |

**Retourne :** `self` - Nouvelle instance avec le résultat de `$this->value === $other`

**Exceptions :** `InvalidArgumentException` - Si la conversion échoue

**Exemple :**
```php
$sameStatus = BoolVO::from($user1->isActive())
    ->xnor($user2->isActive());
```

---

### `equal(mixed $other): self`

Compare l'égalité stricte avec une autre valeur.

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$other` | `mixed` | Valeur à comparer (convertie via `::from()`) |

**Retourne :** `self` - Nouvelle instance avec le résultat de `$this->value === $other`

**Exceptions :** `InvalidArgumentException` - Si la conversion échoue

**Exemple :**
```php
$isSame = BoolVO::from($user->isActive())
    ->equal($user->isVerified());
```

---

### `notEquals(mixed $other): self`

Compare l'inégalité stricte avec une autre valeur.

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$other` | `mixed` | Valeur à comparer (convertie via `::from()`) |

**Retourne :** `self` - Nouvelle instance avec le résultat de `$this->value !== $other`

**Exceptions :** `InvalidArgumentException` - Si la conversion échoue

**Exemple :**
```php
$isDifferent = BoolVO::from($user->isActive())
    ->notEquals($user->isVerified());
```

---

### `isTrue(): self`

Vérifie si la valeur est strictement `true`.

**Retourne :** `self` - Nouvelle instance avec le résultat de `$this->value === true`

**Exemple :**
```php
$bool = BoolVO::from($user->isActive());
if ($bool->isTrue()->getValue()) {
    // Utilisateur actif
}
```

---

### `isFalse(): self`

Vérifie si la valeur est strictement `false`.

**Retourne :** `self` - Nouvelle instance avec le résultat de `$this->value === false`

**Exemple :**
```php
$bool = BoolVO::from($user->isActive());
if ($bool->isFalse()->getValue()) {
    // Utilisateur inactif
}
```

---

### `toInt(): int`

Convertit en entier : `true` → 1, `false` → 0.

**Retourne :** `int` - La valeur convertie (1 ou 0)

**Exemple :**
```php
$active = BoolVO::from($user->isActive())->toInt();
// 1 si actif, 0 sinon
```

---

### `toFloat(): FloatVO`

Convertit en `FloatVO` : `true` → 1.0, `false` → 0.0.

**Retourne :** `FloatVO` - La valeur convertie

**Exemple :**
```php
$active = BoolVO::from($user->isActive())->toFloat();
// FloatVO(1.0) si actif, FloatVO(0.0) sinon
```

---

### `toString(): StringVO`

Convertit en `StringVO` : `true` → 'true', `false` → 'false'.

**Retourne :** `StringVO` - La valeur convertie

**Exemple :**
```php
$active = BoolVO::from($user->isActive())->toString();
// StringVO('true') si actif, StringVO('false') sinon
```

---

### `ifTrue(callable $callback): self`

Exécute un callback si la valeur est `true`. Retourne l'instance pour le chaînage.

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$callback` | `callable` | Fonction à exécuter si la valeur est true |

**Retourne :** `self` - L'instance actuelle (pour chaînage)

**Exemple :**
```php
BoolVO::from($user->isActive())
    ->ifTrue(fn() => $this->logger->info('Utilisateur actif'));
```

---

### `ifFalse(callable $callback): self`

Exécute un callback si la valeur est `false`. Retourne l'instance pour le chaînage.

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$callback` | `callable` | Fonction à exécuter si la valeur est false |

**Retourne :** `self` - L'instance actuelle (pour chaînage)

**Exemple :**
```php
BoolVO::from($user->isActive())
    ->ifFalse(fn() => $this->logger->warning('Utilisateur inactif'));
```

---

### `ifElse(callable $ifTrue, callable $ifFalse): mixed`

Exécute un callback selon la valeur et retourne son résultat.

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$ifTrue` | `callable` | Fonction exécutée si true |
| `$ifFalse` | `callable` | Fonction exécutée si false |

**Retourne :** `mixed` - Le résultat du callback exécuté

**Exemple :**
```php
$message = BoolVO::from($user->isActive())->ifElse(
    fn() => 'Compte actif',
    fn() => 'Compte inactif'
);
```

---

### `andWith(callable $evaluation): self`

ET logique avec une évaluation différée (lazy evaluation).

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$evaluation` | `callable` | Fonction retournant un booléen |

**Retourne :** `self` - Nouvelle instance avec le résultat

**Exemple :**
```php
$result = BoolVO::from($user->isActive())
    ->andWith(fn() => $this->hasValidSubscription($user));
```

---

### `orWith(callable $evaluation): self`

OU logique avec une évaluation différée (lazy evaluation).

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$evaluation` | `callable` | Fonction retournant un booléen |

**Retourne :** `self` - Nouvelle instance avec le résultat

**Exemple :**
```php
$result = BoolVO::from($user->isActive())
    ->orWith(fn() => $this->hasTrialAccess($user));
```

---

### `__toString(): string`

Représentation textuelle de la valeur booléenne.

**Retourne :** `string` - 'true' ou 'false'

**Exemple :**
```php
echo BoolVO::from($user->isActive()); // 'true' ou 'false'
```

---

## Cas d'utilisation

### Cas 1 : Validation de conditions métier

```php
use AndyDefer\PhpVo\ValueObjects\Types\BoolVO;

class UserValidator
{
    public function validate(User $user): BoolVO
    {
        return BoolVO::from($user->isActive())
            ->and($user->hasValidEmail())
            ->and($user->isVerified());
    }
}

// Utilisation
$validator = new UserValidator();
$isValid = $validator->validate($user);

if ($isValid->getValue()) {
    echo 'Utilisateur valide';
}
```

---

### Cas 2 : Construction de conditions complexes

```php
$isEligible = BoolVO::from($user->getAge() >= 18)
    ->and($user->hasValidSubscription())
    ->or($user->isAdmin());

// Chaînage de comparaisons
$result = BoolVO::from($score > 10)
    ->and($score < 100)
    ->or($score === 0)
    ->not();
```

---

### Cas 3 : Logique métier avec méthodes de service

```php
class PaymentService
{
    public function canProcessPayment(User $user, Order $order): BoolVO
    {
        return BoolVO::from($user->hasValidPaymentMethod())
            ->and($order->isPaid()->not())
            ->and($order->getTotal()->lessThan($user->getCreditLimit()));
    }
}

// Utilisation
$canProcess = $paymentService->canProcessPayment($user, $order);
if ($canProcess->getValue()) {
    $paymentService->process($user, $order);
}
```

---

### Cas 4 : Gestion conditionnelle d'état

```php
class UserManager
{
    public function updateStatus(User $user): void
    {
        BoolVO::from($user->isActive())
            ->ifTrue(fn() => $this->activateFeatures($user))
            ->ifFalse(fn() => $this->deactivateFeatures($user));

        $status = BoolVO::from($user->isActive())->ifElse(
            fn() => 'active',
            fn() => 'inactive'
        );

        $this->logger->info("User status: {$status}");
    }
}
```

---

## Flux d'exécution

```
Création (new BoolVO() ou ::from())
    ↓
Opération logique (and, or, xor, not, nand, nor, xnor)
    ↓
Nouvelle instance BoolVO (immutable)
    ↓
Comparaison (equal, notEquals)
    ↓
Nouvelle instance BoolVO (immutable)
    ↓
Conversion (toInt, toFloat, toString)
    ↓
Résultat
```

**Immutable :** Chaque opération retourne une **nouvelle instance**.

```php
$original = BoolVO::from($user->isActive());
$result = $original->and($user->isVerified());

$original->getValue(); // valeur initiale - inchangée
$result->getValue();   // nouvelle valeur - nouvelle instance
```

---

## Gestion des erreurs

| Situation | Exception | Message |
|-----------|-----------|---------|
| Conversion impossible via `::from()` | `InvalidArgumentException` | `Cannot convert value to bool for parameter $value` |
| Source invalide dans `::from()` | `InvalidArgumentException` | `Cannot create BoolVO from X...` |

---

## Performance

| Opération | Complexité | Notes |
|-----------|------------|-------|
| `getValue()` | O(1) | Retour direct |
| `and()`, `or()`, etc. | O(1) | Opérations logiques simples |
| `toInt()`, `toFloat()`, `toString()` | O(1) | Cast simple |
| `ifTrue()`, `ifFalse()`, `ifElse()` | O(1) | Exécution conditionnelle |

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

use AndyDefer\PhpVo\ValueObjects\Types\BoolVO;

// Conditions métier
$user = Auth::user();

// Validation composée
$isValid = BoolVO::from($user->isActive())
    ->and($user->hasVerifiedEmail())
    ->and($user->hasValidSubscription());

// Gestion d'accès
$canAccess = BoolVO::from($user->isAdmin())
    ->or($user->hasPermission('view_dashboard'));

// Notification conditionnelle
BoolVO::from($user->isActive())
    ->ifTrue(fn() => $this->sendWelcomeEmail($user))
    ->ifFalse(fn() => $this->sendReactivationEmail($user));

// Conversion pour API
$response = [
    'active' => BoolVO::from($user->isActive())->toInt(),
    'status' => BoolVO::from($user->isActive())->toString()->getValue(),
];

// Logique métier complexe
$canCheckout = BoolVO::from($cart->hasItems())
    ->and($user->hasValidPaymentMethod())
    ->and($cart->getTotal()->lessThan($user->getCreditLimit()))
    ->or($user->isAdmin());

if ($canCheckout->getValue()) {
    $this->checkout($cart, $user);
}

echo BoolVO::from($user->isActive()); // 'true' ou 'false'
```

---

## Voir aussi

- `FloatVO` - Value Object pour les flottants
- `StringVO` - Value Object pour les chaînes
- `AbstractValueObject` - Classe de base pour les Value Objects