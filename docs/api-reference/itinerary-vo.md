# ItineraryVO - Référence Technique

## Description

Value Object immuable représentant un itinéraire ordonné entre un point de départ et un point d'arrivée, traversant des points intermédiaires réordonnés par un algorithme de minimisation de distance Haversine.

## Hiérarchie / Implémentations

```
AbstractValueObject
    └── ItineraryVO
```

## Rôle principal

Calcule un itinéraire en sélectionnant l'algorithme approprié selon la taille du problème. Chaque algorithme possède une limite pratique au-delà de laquelle il refuse de s'exécuter, pour éviter les blocages CPU ou l'épuisement mémoire. Aucun appel à un service externe n'est nécessaire (LocationIQ, Google Maps, etc.).

## Installation

```bash
composer require andydefer/php-vo
```

## Constante publique

| Constante | Valeur | Description |
|-----------|--------|-------------|
| `MAX_INTERMEDIATES` | `30` | Nombre maximum absolu de points intermédiaires |

## Algorithmes disponibles

| Algorithme | Complexité | Optimalité | Limite pratique |
|------------|------------|------------|-----------------|
| `GREEDY` | O(n²) | Approché | 30 |
| `BRUTE_FORCE` | O(n!) | Exact | 8 |
| `HELD_KARP` | O(n² · 2ⁿ) | Exact | 15 |
| `TWO_OPT` | O(n²) / passe | Approché | 30 |

## API / Méthodes publiques

### `__construct(CoordinatesVO $start, CoordinatesVOCollection $intermediates, CoordinatesVO $end, DistanceUnit $unit = DistanceUnit::KILOMETRE, ItineraryAlgorithm $algorithm = ItineraryAlgorithm::GREEDY)`

Construit un itinéraire validé.

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$start` | `CoordinatesVO` | Point de départ |
| `$intermediates` | `CoordinatesVOCollection` | Points intermédiaires (ordre quelconque) |
| `$end` | `CoordinatesVO` | Point d'arrivée |
| `$unit` | `DistanceUnit` | Unité (`KILOMETRE` par défaut) |
| `$algorithm` | `ItineraryAlgorithm` | Algorithme de tri (`GREEDY` par défaut) |

**Retourne :** `void`

**Exceptions :**
- `InvalidArgumentException` — si le nombre d'intermédiaires dépasse `MAX_INTERMEDIATES`.
- `InvalidArgumentException` — si l'algorithme ne supporte pas le nombre d'intermédiaires.

**Exemple :**
```php
$itinerary = new ItineraryVO(
    start: CoordinatesVO::from(['latitude' => 48.8566, 'longitude' => 2.3522]),
    intermediates: $intermediates,
    end: CoordinatesVO::from(['latitude' => 51.5074, 'longitude' => -0.1278]),
);
```

---

### `withUnit(DistanceUnit $unit): self`

Retourne une copie avec une autre unité.

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$unit` | `DistanceUnit` | Unité cible |

**Retourne :** `self`

**Exemple :**
```php
$inMetres = $itinerary->withUnit(DistanceUnit::METRE);
```

---

### `withAlgorithm(ItineraryAlgorithm $algorithm): self`

Retourne une copie avec un algorithme différent.

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$algorithm` | `ItineraryAlgorithm` | Algorithme cible |

**Retourne :** `self`

**Exceptions :** `InvalidArgumentException` — si l'algorithme ne supporte pas le nombre d'intermédiaires actuel.

**Exemple :**
```php
$exact = $itinerary->withAlgorithm(ItineraryAlgorithm::HELD_KARP);
```

---

### `withBestAlgorithm(): self`

Retourne une copie avec l'algorithme recommandé pour sa taille.

**Retourne :** `self`

**Exemple :**
```php
$best = $itinerary->withBestAlgorithm();
```

**Sélection :**

| Intermédiaires | Algorithme |
|----------------|------------|
| 0 – 8 | `BRUTE_FORCE` |
| 9 – 15 | `HELD_KARP` |
| 16 – 30 | `TWO_OPT` |

---

### `getStart(): CoordinatesVO`

Retourne le point de départ.

**Retourne :** `CoordinatesVO`

---

### `getEnd(): CoordinatesVO`

Retourne le point d'arrivée.

**Retourne :** `CoordinatesVO`

---

### `getIntermediates(): CoordinatesVOCollection`

Retourne les points intermédiaires dans leur ordre d'origine.

**Retourne :** `CoordinatesVOCollection`

---

### `getUnit(): DistanceUnit`

Retourne l'unité de distance.

**Retourne :** `DistanceUnit`

---

### `getAlgorithm(): ItineraryAlgorithm`

Retourne l'algorithme courant.

**Retourne :** `ItineraryAlgorithm`

---

### `getOrderedPath(): CoordinatesVOCollection`

Retourne le chemin ordonné (`start → intermédiaires réordonnés → end`).

**Retourne :** `CoordinatesVOCollection`

**Exemple :**
```php
foreach ($itinerary->getOrderedPath() as $point) {
    echo $point->getName()?->getValue();
}
```

---

### `legs(): ItineraryLegVOCollection`

Retourne les segments du chemin ordonné.

**Retourne :** `ItineraryLegVOCollection`

**Exemple :**
```php
foreach ($itinerary->legs() as $leg) {
    echo $leg->getDistance()->getValue();
}
```

---

### `totalDistance(): FloatVO`

Retourne la distance totale cumulée, arrondie à 2 décimales.

**Retourne :** `FloatVO`

**Exemple :**
```php
$itinerary->totalDistance()->getValue(); // 767.77
```

---

### `summary(): ItinerarySummaryVO`

Retourne un résumé statistique.

**Retourne :** `ItinerarySummaryVO`

**Exemple :**
```php
$summary = $itinerary->summary();
$summary->getTotalDistance()->getValue();
```

---

### `distanceBetween(CoordinatesVO $from, CoordinatesVO $to): float`

Calcule la distance Haversine entre deux coordonnées dans l'unité de l'itinéraire.

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$from` | `CoordinatesVO` | Point source |
| `$to` | `CoordinatesVO` | Point cible |

**Retourne :** `float` — Distance arrondie à 2 décimales

**Exemple :**
```php
$itinerary->distanceBetween($paris, $london); // 343.56
```

---

### `getValue(): StrictAssociative`

Retourne la structure d'entrée stricte.

**Retourne :** `StrictAssociative` — Clés : `start`, `intermediates`, `end`, `unit`, `algorithm`

**Exemple :**
```php
$value = $itinerary->getValue();
$value->start;      // CoordinatesVO
$value->algorithm;  // ItineraryAlgorithm
```

---

### `equals(AbstractValueObject $other): bool`

Compare deux itinéraires par valeur (start, end, unité, algorithme, chemin ordonné).

**Retourne :** `bool`

**Exemple :**
```php
$a->equals($b); // true|false
```

---

## Cas d'utilisation

### Cas 1 : Itinéraire exact pour petite tournée

Un livreur visite 5 pharmacies entre son dépôt et un client.

```php
$itinerary = (new ItineraryVO($depot, $stops, $client))
    ->withBestAlgorithm(); // → BRUTE_FORCE (n=5)

echo $itinerary->totalDistance()->getValue(); // optimal garanti
```

### Cas 2 : Itinéraire pour grande tournée

20 points intermédiaires — la brute force est prohibitive.

```php
$itinerary = (new ItineraryVO($start, $stops, $end))
    ->withBestAlgorithm(); // → TWO_OPT

echo $itinerary->getAlgorithm()->value; // "two_opt"
```

### Cas 3 : Restitution en mètres

```php
$inMetres = $itinerary->withUnit(DistanceUnit::METRE);

printf(
    'Total: %.0f m | Legs: %d',
    $inMetres->totalDistance()->getValue(),
    $inMetres->legs()->count(),
);
```

### Cas 4 : Détection de segment dominant

```php
$summary = $itinerary->summary();

$ratio = $summary->getLongestLegDistance()->getValue()
    / $summary->getTotalDistance()->getValue();

if ($ratio > 0.5) {
    echo 'Segment critique : #' . $summary->getLongestLegIndex();
}
```

### Cas 5 : Gestion de dépassement de limite

```php
try {
    new ItineraryVO(
        $start,
        $tooManyIntermediates,   // 20 points
        $end,
        DistanceUnit::KILOMETRE,
        ItineraryAlgorithm::HELD_KARP,
    );
} catch (InvalidArgumentException $e) {
    // "Algorithm \"held_karp\" supports at most 15 intermediate points, 20 given."
    $itinerary = (new ItineraryVO($start, $tooManyIntermediates, $end))
        ->withBestAlgorithm();
}
```

## Flux d'exécution

```
new ItineraryVO(start, intermediates, end, unit, algorithm)
    ↓
Validation 1 : count ≤ MAX_INTERMEDIATES ?
    └─ non → InvalidArgumentException
    ↓
Validation 2 : algorithm->supports(count) ?
    └─ non → InvalidArgumentException
    ↓
Itinéraire valide

getOrderedPath()
    ↓
match(algorithm)
    ├── GREEDY       → orderByGreedy
    ├── BRUTE_FORCE  → orderByBruteForce (permutations)
    ├── HELD_KARP    → orderByHeldKarp (DP bitmask)
    └── TWO_OPT      → orderByGreedy + 2-opt local search
    ↓
CoordinatesVOCollection [start, ...ordered, end]
    ↓
legs() / totalDistance() / summary()
```

## Gestion des erreurs

| Situation | Exception | Message |
|-----------|-----------|---------|
| Intermédiaires > `MAX_INTERMEDIATES` | `InvalidArgumentException` | `Itinerary cannot contain more than 30 intermediate points, N given.` |
| Algorithme ne supporte pas la taille | `InvalidArgumentException` | `Algorithm "X" supports at most N intermediate points, M given. Use ItineraryAlgorithm::recommendedFor() or a lighter algorithm.` |
| `withAlgorithm()` cible trop restrictive | `InvalidArgumentException` | Même message que ci-dessus |

## Intégration

```
CoordinatesVOCollection
        │
        ▼
   ItineraryVO
        ├── getOrderedPath()  → CoordinatesVOCollection
        ├── legs()            → ItineraryLegVOCollection
        ├── totalDistance()   → FloatVO
        ├── summary()         → ItinerarySummaryVO
        ├── distanceBetween() → float
        └── getValue()        → StrictAssociative
```

`ItineraryVO` dépend de `CoordinatesVO`, `CoordinatesVOCollection`, `DistanceUnit`, `ItineraryAlgorithm`, `ItineraryLegVO`, `ItinerarySummaryVO`.

## Performance

| Algorithme | n=5 | n=8 | n=15 | n=30 |
|------------|-----|-----|------|------|
| `GREEDY` | ~0.1 ms | ~0.2 ms | ~0.35 ms | ~1 ms |
| `BRUTE_FORCE` | ~1 ms | ~500 ms | — | — |
| `HELD_KARP` | ~0.2 ms | ~1 ms | ~350 ms | — |
| `TWO_OPT` | ~0.15 ms | ~0.6 ms | ~1.5 ms | ~15 ms |

- `getOrderedPath()` recalcule le chemin à chaque appel (pas de cache interne).
- `legs()` et `totalDistance()` appellent `getOrderedPath()` : préférer stocker le résultat si appelé plusieurs fois.

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
$stops->add(CoordinatesVO::from(['latitude' => 49.4944, 'longitude' => 0.1079]));

// Itinéraire optimal automatique
$itinerary = (new ItineraryVO($start, $stops, $end))->withBestAlgorithm();

echo $itinerary->getAlgorithm()->value;            // "brute_force"
echo $itinerary->totalDistance()->getValue();      // distance optimale

foreach ($itinerary->legs() as $leg) {
    printf(
        "Leg #%d : %.2f %s\n",
        $leg->getIndex(),
        $leg->getDistance()->getValue(),
        $leg->getUnit()->value,
    );
}

// Changement d'unité
$inMetres = $itinerary->withUnit(DistanceUnit::METRE);
echo $inMetres->totalDistance()->getValue();       // distance en mètres

// Résumé
$summary = $itinerary->summary();
echo $summary->getLegCount();                      // 4
echo $summary->getLongestLegIndex();               // index du segment le plus long
```