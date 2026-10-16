# ItineraryLegVO - Référence Technique

## Description

Value Object immuable représentant un segment d'itinéraire entre deux coordonnées, avec sa distance Haversine exprimée dans une unité donnée.

## Hiérarchie / Implémentations

```
AbstractValueObject
    └── ItineraryLegVO
```

## Rôle principal

Modélise un tronçon élémentaire d'un itinéraire : point de départ, point d'arrivée, distance, unité et index dans la séquence. Utilisé par `ItineraryVO` pour exposer chaque étape d'un chemin ordonné.

## Installation

```bash
composer require andydefer/php-vo
```

## API / Méthodes publiques

### `__construct(int $index, CoordinatesVO $from, CoordinatesVO $to, FloatVO $distance, DistanceUnit $unit)`

Construit un segment d'itinéraire.

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$index` | `int` | Position du segment dans la séquence (0-based) |
| `$from` | `CoordinatesVO` | Point de départ du segment |
| `$to` | `CoordinatesVO` | Point d'arrivée du segment |
| `$distance` | `FloatVO` | Distance entre `from` et `to` |
| `$unit` | `DistanceUnit` | Unité de la distance |

**Retourne :** `void`

**Exemple :**
```php
$leg = new ItineraryLegVO(
    index: 0,
    from: CoordinatesVO::from(['latitude' => 48.8566, 'longitude' => 2.3522]),
    to: CoordinatesVO::from(['latitude' => 51.5074, 'longitude' => -0.1278]),
    distance: FloatVO::from(343.56),
    unit: DistanceUnit::KILOMETRE,
);
```

---

### `getIndex(): int`

Retourne la position du segment dans l'itinéraire.

**Retourne :** `int` — Index 0-based

**Exemple :**
```php
$leg->getIndex(); // 0
```

---

### `getFrom(): CoordinatesVO`

Retourne le point de départ du segment.

**Retourne :** `CoordinatesVO`

**Exemple :**
```php
$leg->getFrom()->getLatitude()->getValue(); // 48.8566
```

---

### `getTo(): CoordinatesVO`

Retourne le point d'arrivée du segment.

**Retourne :** `CoordinatesVO`

**Exemple :**
```php
$leg->getTo()->getLongitude()->getValue(); // -0.1278
```

---

### `getDistance(): FloatVO`

Retourne la distance du segment.

**Retourne :** `FloatVO`

**Exemple :**
```php
$leg->getDistance()->getValue(); // 343.56
```

---

### `getUnit(): DistanceUnit`

Retourne l'unité de la distance.

**Retourne :** `DistanceUnit`

**Exemple :**
```php
$leg->getUnit(); // DistanceUnit::KILOMETRE
```

---

### `withUnit(DistanceUnit $unit): self`

Retourne une nouvelle instance avec la distance convertie dans une autre unité.

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$unit` | `DistanceUnit` | Unité cible |

**Retourne :** `self` — Nouvelle instance ; la **même** instance si l'unité est identique

**Exemple :**
```php
$inMetres = $leg->withUnit(DistanceUnit::METRE);
$inMetres->getDistance()->getValue(); // 343560.0
```

---

### `getValue(): StrictAssociative`

Retourne la représentation structurée du segment.

**Retourne :** `StrictAssociative` — Clés : `index`, `from`, `to`, `distance`, `unit`

**Exemple :**
```php
$value = $leg->getValue();
$value->index;    // 0
$value->from;     // CoordinatesVO
$value->distance; // FloatVO
```

---

### `equals(AbstractValueObject $other): bool`

Compare deux segments par valeur.

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$other` | `AbstractValueObject` | Instance à comparer |

**Retourne :** `bool` — `true` si index, unité, distance, from et to sont égaux

**Exemple :**
```php
$legA->equals($legB); // true|false
```

---

## Cas d'utilisation

### Cas 1 : Convertir un itinéraire en mètres

Un client demande les distances en mètres plutôt qu'en kilomètres.

```php
foreach ($itinerary->legs() as $leg) {
    $inMetres = $leg->withUnit(DistanceUnit::METRE);
    printf(
        "Leg #%d : %.0f m\n",
        $inMetres->getIndex(),
        $inMetres->getDistance()->getValue(),
    );
}
```

### Cas 2 : Identifier le segment le plus long

```php
$longest = $itinerary->legs()
    ->sortBy(
        fn (ItineraryLegVO $leg): float => $leg->getDistance()->getValue(),
        descending: true,
    )
    ->first();

printf(
    "Segment le plus long : #%d (%.2f %s)\n",
    $longest->getIndex(),
    $longest->getDistance()->getValue(),
    $longest->getUnit()->value,
);
```

### Cas 3 : Filtrer les segments courts

```php
$shortLegs = $itinerary->legs()->filter(
    fn (ItineraryLegVO $leg): bool => $leg->getDistance()->getValue() < 50.0,
);

echo $shortLegs->count() . ' segments courts.';
```

## Flux d'exécution

```
withUnit($target)
    ↓
$target === $this->unit ?
    ├── oui → return $this (même instance)
    └── non
        ↓
DistanceUnit::convertTo($value, $target)
    ↓
FloatVO::round(2)
    ↓
new ItineraryLegVO($index, $from, $to, $converted, $target)
```

## Gestion des erreurs

Aucune exception n'est levée par cette classe. Les validations dépendent des Value Objects injectés (`CoordinatesVO`, `FloatVO`) et de l'énumération `DistanceUnit`.

## Intégration

```
ItineraryVO
    │
    ├── getOrderedPath()  → CoordinatesVOCollection
    ├── legs()            → ItineraryLegVOCollection
    │       └── ItineraryLegVO
    └── summary()         → ItinerarySummaryVO
```

`ItineraryLegVO` est produit exclusivement par `ItineraryVO::legs()`. Il encapsule une paire `CoordinatesVO` + un `FloatVO` (distance) + une `DistanceUnit`.

## Performance

- Construction en O(1).
- `withUnit()` : une conversion et un `round`, sans itération.
- `equals()` : comparaison scalaire et deux appels récursifs sur `CoordinatesVO::equals` — coût constant.
- `getValue()` : allocation d'un `StrictAssociative` de cinq entrées.

## Compatibilité

| Version PHP | Support |
|-------------|---------|
| PHP 8.1+ | ✅ Complet |
| PHP 8.0 | ⚠️ Vérifier les propriétés `readonly` |

## Exemple complet

```php
<?php

declare(strict_types=1);

use AndyDefer\PhpVo\Enums\DistanceUnit;
use AndyDefer\PhpVo\ValueObjects\CoordinatesVO;
use AndyDefer\PhpVo\ValueObjects\ItineraryLegVO;
use AndyDefer\PhpVo\ValueObjects\Types\FloatVO;

$paris = CoordinatesVO::from(['latitude' => 48.8566, 'longitude' => 2.3522]);
$london = CoordinatesVO::from(['latitude' => 51.5074, 'longitude' => -0.1278]);

$leg = new ItineraryLegVO(
    index: 0,
    from: $paris,
    to: $london,
    distance: FloatVO::from(343.56),
    unit: DistanceUnit::KILOMETRE,
);

echo $leg->getIndex();                             // 0
echo $leg->getFrom()->getLatitude()->getValue();   // 48.8566
echo $leg->getTo()->getLongitude()->getValue();    // -0.1278
echo $leg->getDistance()->getValue();              // 343.56

$inMetres = $leg->withUnit(DistanceUnit::METRE);
echo $inMetres->getDistance()->getValue();         // 343560.0
echo $inMetres->getUnit()->value;                  // "m"

$value = $leg->getValue();
echo $value->index;                                // 0
echo $value->unit->value;                          // "km"
```