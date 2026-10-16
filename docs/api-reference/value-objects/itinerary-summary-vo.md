# ItinerarySummaryVO - Référence Technique

## Description

Value Object immuable agrégeant les métriques statistiques d'un itinéraire : nombre de segments, points, intermédiaires, distances totale, moyenne, plus longue et plus courte.

## Hiérarchie / Implémentations

```
AbstractValueObject
    └── ItinerarySummaryVO
```

## Rôle principal

Expose une vue agrégée d'un `ItineraryVO` sans recalcul côté consommateur. Centralise les indicateurs de longueur (total, moyenne, min, max) et les identifiants des segments concernés. Utilisé pour les tableaux de bord, rapports et validations de seuils.

## Installation

```bash
composer require andydefer/php-vo
```

## API / Méthodes publiques

### `__construct(int $legCount, int $pointCount, int $intermediateCount, FloatVO $totalDistance, FloatVO $averageLegDistance, FloatVO $longestLegDistance, int $longestLegIndex, FloatVO $shortestLegDistance, int $shortestLegIndex, DistanceUnit $unit)`

Construit un résumé d'itinéraire.

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$legCount` | `int` | Nombre total de segments |
| `$pointCount` | `int` | Nombre total de points (start + intermédiaires + end) |
| `$intermediateCount` | `int` | Nombre de points intermédiaires |
| `$totalDistance` | `FloatVO` | Distance cumulée |
| `$averageLegDistance` | `FloatVO` | Distance moyenne par segment |
| `$longestLegDistance` | `FloatVO` | Distance du segment le plus long |
| `$longestLegIndex` | `int` | Index du segment le plus long (`-1` si aucun) |
| `$shortestLegDistance` | `FloatVO` | Distance du segment le plus court |
| `$shortestLegIndex` | `int` | Index du segment le plus court (`-1` si aucun) |
| `$unit` | `DistanceUnit` | Unité commune à toutes les distances |

**Retourne :** `void`

**Exemple :**
```php
$summary = new ItinerarySummaryVO(
    legCount: 3,
    pointCount: 4,
    intermediateCount: 2,
    totalDistance: FloatVO::from(1000.0),
    averageLegDistance: FloatVO::from(333.33),
    longestLegDistance: FloatVO::from(500.0),
    longestLegIndex: 1,
    shortestLegDistance: FloatVO::from(100.0),
    shortestLegIndex: 2,
    unit: DistanceUnit::KILOMETRE,
);
```

---

### `getLegCount(): int`

Retourne le nombre total de segments.

**Retourne :** `int`

**Exemple :**
```php
$summary->getLegCount(); // 3
```

---

### `getPointCount(): int`

Retourne le nombre total de points.

**Retourne :** `int`

**Exemple :**
```php
$summary->getPointCount(); // 4
```

---

### `getIntermediateCount(): int`

Retourne le nombre de points intermédiaires.

**Retourne :** `int`

**Exemple :**
```php
$summary->getIntermediateCount(); // 2
```

---

### `getTotalDistance(): FloatVO`

Retourne la distance totale de l'itinéraire.

**Retourne :** `FloatVO`

**Exemple :**
```php
$summary->getTotalDistance()->getValue(); // 1000.0
```

---

### `getAverageLegDistance(): FloatVO`

Retourne la distance moyenne par segment.

**Retourne :** `FloatVO`

**Exemple :**
```php
$summary->getAverageLegDistance()->getValue(); // 333.33
```

---

### `getLongestLegDistance(): FloatVO`

Retourne la distance du segment le plus long.

**Retourne :** `FloatVO`

**Exemple :**
```php
$summary->getLongestLegDistance()->getValue(); // 500.0
```

---

### `getLongestLegIndex(): int`

Retourne l'index du segment le plus long.

**Retourne :** `int` — `-1` si l'itinéraire ne contient aucun segment

**Exemple :**
```php
$summary->getLongestLegIndex(); // 1
```

---

### `getShortestLegDistance(): FloatVO`

Retourne la distance du segment le plus court.

**Retourne :** `FloatVO`

**Exemple :**
```php
$summary->getShortestLegDistance()->getValue(); // 100.0
```

---

### `getShortestLegIndex(): int`

Retourne l'index du segment le plus court.

**Retourne :** `int` — `-1` si l'itinéraire ne contient aucun segment

**Exemple :**
```php
$summary->getShortestLegIndex(); // 2
```

---

### `getUnit(): DistanceUnit`

Retourne l'unité commune à toutes les distances.

**Retourne :** `DistanceUnit`

**Exemple :**
```php
$summary->getUnit(); // DistanceUnit::KILOMETRE
```

---

### `withUnit(DistanceUnit $unit): self`

Retourne une nouvelle instance avec toutes les distances converties dans une autre unité.

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$unit` | `DistanceUnit` | Unité cible |

**Retourne :** `self` — Nouvelle instance ; la même instance si l'unité est identique

**Exemple :**
```php
$inMetres = $summary->withUnit(DistanceUnit::METRE);
$inMetres->getTotalDistance()->getValue(); // 1000000.0
```

---

### `getValue(): StrictAssociative`

Retourne la représentation structurée du résumé.

**Retourne :** `StrictAssociative` — Clés : `legCount`, `pointCount`, `intermediateCount`, `totalDistance`, `averageLegDistance`, `longestLegDistance`, `longestLegIndex`, `shortestLegDistance`, `shortestLegIndex`, `unit`

**Exemple :**
```php
$value = $summary->getValue();
$value->legCount;         // 3
$value->totalDistance;    // FloatVO
$value->unit;             // DistanceUnit
```

---

### `equals(AbstractValueObject $other): bool`

Compare deux résumés par valeur.

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$other` | `AbstractValueObject` | Instance à comparer |

**Retourne :** `bool` — `true` si tous les compteurs, indices, unités et distances sont égaux

**Exemple :**
```php
$a->equals($b); // true|false
```

---

## Cas d'utilisation

### Cas 1 : Détecter un segment anormalement long

Un système de surveillance signale les itinéraires dont un segment dépasse 50 % du total.

```php
$summary = $itinerary->summary();

$ratio = $summary->getLongestLegDistance()->getValue()
    / $summary->getTotalDistance()->getValue();

if ($ratio > 0.5) {
    echo 'Segment dominant : #' . $summary->getLongestLegIndex();
}
```

### Cas 2 : Convertir le résumé pour affichage en mètres

Un frontend demande les métriques en mètres.

```php
$inMetres = $itinerary->summary()->withUnit(DistanceUnit::METRE);

printf(
    'Total : %d m | Moyenne : %d m | Max : %d m',
    $inMetres->getTotalDistance()->getValue(),
    $inMetres->getAverageLegDistance()->getValue(),
    $inMetres->getLongestLegDistance()->getValue(),
);
```

### Cas 3 : Vérifier un itinéraire vide

```php
$summary = $itinerary->summary();

if ($summary->getLegCount() === 0) {
    echo 'Aucun segment';
}
```

### Cas 4 : Auditer la répartition des distances

```php
$summary = $itinerary->summary();

printf(
    "Legs : %d\nTotal : %.2f %s\nMoyenne : %.2f\nMin : %.2f (#%d)\nMax : %.2f (#%d)\n",
    $summary->getLegCount(),
    $summary->getTotalDistance()->getValue(),
    $summary->getUnit()->value,
    $summary->getAverageLegDistance()->getValue(),
    $summary->getShortestLegDistance()->getValue(),
    $summary->getShortestLegIndex(),
    $summary->getLongestLegDistance()->getValue(),
    $summary->getLongestLegIndex(),
);
```

## Flux d'exécution

```
ItineraryVO::summary()
    ↓
ItineraryVO::legs()  → ItineraryLegVOCollection
    ↓
Calcul des compteurs (legCount, pointCount, intermediateCount)
    ↓
Boucle sur les legs :
    ├── total += distance
    ├── si distance > longest → maj longest
    └── si distance < shortest → maj shortest
    ↓
new ItinerarySummaryVO(...)
    ↓
withUnit($u) → conversion via DistanceUnit::convertTo()
    ↓
new ItinerarySummaryVO(...)
```

## Gestion des erreurs

Aucune exception n'est levée par cette classe. La conversion d'unité repose sur `DistanceUnit::convertTo()` et `FloatVO::round()`.

## Intégration

```
ItineraryVO
    ├── legs()    → ItineraryLegVOCollection
    └── summary() → ItinerarySummaryVO
                        │
                        ├── getTotalDistance()
                        ├── getAverageLegDistance()
                        ├── getLongestLegDistance()/Index()
                        ├── getShortestLegDistance()/Index()
                        ├── withUnit() → ItinerarySummaryVO
                        └── getValue() → StrictAssociative
```

`ItinerarySummaryVO` est produit exclusivement par `ItineraryVO::summary()`. Il agrège les informations issues de `ItineraryLegVOCollection` et de `CoordinatesVOCollection`.

## Performance

- Construction en O(n) où `n` est le nombre de segments.
- `withUnit()` : quatre conversions et un `round`, sans itération supplémentaire.
- `equals()` : comparaison scalaire de dix champs et quatre appels récursifs sur `FloatVO::equals`.
- `getValue()` : allocation d'un `StrictAssociative` de dix entrées.

## Compatibilité

| Version PHP | Support |
|-------------|---------|
| PHP 8.1+ | ✅ Complet |
| PHP 8.0 | ⚠️ Vérifier les propriétés `readonly` |

## Exemple complet

```php
<?php

declare(strict_types=1);

use AndyDefer\PhpVo\Collections\CoordinatesVOCollection;
use AndyDefer\PhpVo\Enums\DistanceUnit;
use AndyDefer\PhpVo\Enums\ItineraryAlgorithm;
use AndyDefer\PhpVo\ValueObjects\CoordinatesVO;
use AndyDefer\PhpVo\ValueObjects\ItineraryVO;

$start = CoordinatesVO::from(['latitude' => 48.8566, 'longitude' => 2.3522]);
$end = CoordinatesVO::from(['latitude' => 51.5074, 'longitude' => -0.1278]);

$stops = new CoordinatesVOCollection;
$stops->add(CoordinatesVO::from(['latitude' => 50.6292, 'longitude' => 3.0573]));
$stops->add(CoordinatesVO::from(['latitude' => 49.2583, 'longitude' => 4.0317]));

$itinerary = new ItineraryVO(
    $start,
    $stops,
    $end,
    DistanceUnit::KILOMETRE,
    ItineraryAlgorithm::HELD_KARP,
);

$summary = $itinerary->summary();

echo $summary->getLegCount();                                 // 3
echo $summary->getPointCount();                               // 4
echo $summary->getIntermediateCount();                        // 2
echo $summary->getTotalDistance()->getValue();                // km
echo $summary->getAverageLegDistance()->getValue();           // km
echo $summary->getLongestLegDistance()->getValue();           // km
echo $summary->getLongestLegIndex();                          // int
echo $summary->getShortestLegDistance()->getValue();          // km
echo $summary->getShortestLegIndex();                         // int
echo $summary->getUnit()->value;                              // "km"

$inMetres = $summary->withUnit(DistanceUnit::METRE);
echo $inMetres->getTotalDistance()->getValue();               // m

$value = $summary->getValue();
echo $value->legCount;                                        // 3
echo $value->unit->value;                                     // "km"
```