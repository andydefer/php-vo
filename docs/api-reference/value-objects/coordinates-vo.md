# CoordinatesVO - Référence Technique

## Description

Value Object immuable représentant des coordonnées géographiques (latitude, longitude) avec validation des plages et calcul de distance.

## Hiérarchie

```
AbstractValueObject (andydefer/domain-structures)
    └── CoordinatesVO
```

## Rôle principal

Encapsuler des coordonnées géographiques avec validation des plages (latitude -90° à 90°, longitude -180° à 180°), fournir des méthodes de calcul de distance (formule de Haversine), et permettre des opérations métier comme la détection d'hémisphère ou la vérification de proximité.

## Installation

```bash
composer require andydefer/php-vo
```

## API / Méthodes publiques

### `getValue(): CoordinatesRecord`

| Paramètre | Type | Description |
|-----------|------|-------------|
| - | - | Aucun paramètre |

**Retourne :** `CoordinatesRecord` - Les coordonnées converties en Record (structure de données)

**Exemple :**
```php
$coords = CoordinatesVO::from(['latitude' => 48.8566, 'longitude' => 2.3522]);
$record = $coords->getValue();
// $record->latitude === 48.8566
// $record->longitude === 2.3522
```

### `distanceTo(self $other, SpaceTimeUnit $unit = SpaceTimeUnit::KILOMETRE): float`

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$other` | `CoordinatesVO` | Autre coordonnée cible |
| `$unit` | `SpaceTimeUnit` | Unité de mesure (KILOMETRE, METRE) |

**Retourne :** `float` - Distance entre les deux points dans l'unité spécifiée

**Exceptions :** Aucune

**Exemple :**
```php
$paris = CoordinatesVO::from(['latitude' => 48.8566, 'longitude' => 2.3522]);
$london = CoordinatesVO::from(['latitude' => 51.5074, 'longitude' => -0.1278]);
$distance = $paris->distanceTo($london, SpaceTimeUnit::KILOMETRE);
// $distance ≈ 344 km
```

### `isSameLocation(self $other, float $tolerance = 0.0001): bool`

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$other` | `CoordinatesVO` | Autre coordonnée à comparer |
| `$tolerance` | `float` | Tolérance en degrés (défaut: 0.0001 ≈ 11 mètres) |

**Retourne :** `bool` - True si les coordonnées sont dans la tolérance spécifiée

**Exemple :**
```php
$point1 = CoordinatesVO::from(['latitude' => 48.8566, 'longitude' => 2.3522]);
$point2 = CoordinatesVO::from(['latitude' => 48.8567, 'longitude' => 2.3523]);
$isSame = $point1->isSameLocation($point2, 0.0002); // true
```

### `format(int $decimals = 4): string`

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$decimals` | `int` | Nombre de décimales (défaut: 4) |

**Retourne :** `string` - Coordonnées formatées "latitude, longitude"

**Exemple :**
```php
echo $coords->format(2); // "48.86, 2.35"
echo $coords->format(6); // "48.856600, 2.352200"
```

### `isNorthernHemisphere(): bool`

| Paramètre | Type | Description |
|-----------|------|-------------|
| - | - | Aucun paramètre |

**Retourne :** `bool` - True si la latitude > 0 (hémisphère nord)

**Exemple :**
```php
if ($coords->isNorthernHemisphere()) {
    // Appliquer la livraison vers l'hémisphère nord
}
```

### `isSouthernHemisphere(): bool`

| Paramètre | Type | Description |
|-----------|------|-------------|
| - | - | Aucun paramètre |

**Retourne :** `bool` - True si la latitude < 0 (hémisphère sud)

### `isEasternHemisphere(): bool`

| Paramètre | Type | Description |
|-----------|------|-------------|
| - | - | Aucun paramètre |

**Retourne :** `bool` - True si la longitude > 0 (hémisphère est)

### `isWesternHemisphere(): bool`

| Paramètre | Type | Description |
|-----------|------|-------------|
| - | - | Aucun paramètre |

**Retourne :** `bool` - True si la longitude < 0 (hémisphère ouest)

### `__toString(): string`

| Paramètre | Type | Description |
|-----------|------|-------------|
| - | - | Aucun paramètre |

**Retourne :** `string` - Identique à `format()`

**Exemple :**
```php
echo $coords; // "48.8566, 2.3522"
```

## Cas d'utilisation

### Cas 1 : Calcul de distance entre deux lieux

```php
<?php

declare(strict_types=1);

use AndyDefer\PhpVo\Enums\SpaceTimeUnit;
use AndyDefer\PhpVo\ValueObjects\CoordinatesVO;

$paris = CoordinatesVO::from(['latitude' => 48.8566, 'longitude' => 2.3522]);
$london = CoordinatesVO::from(['latitude' => 51.5074, 'longitude' => -0.1278]);

$distanceKm = $paris->distanceTo($london, SpaceTimeUnit::KILOMETRE);
$distanceM = $paris->distanceTo($london, SpaceTimeUnit::METRE);

echo "Paris-Londres : {$distanceKm} km";
// "Paris-Londres : 344.0 km"
```

### Cas 2 : Filtrage géographique par hémisphère

```php
<?php

declare(strict_types=1);

use AndyDefer\PhpVo\ValueObjects\CoordinatesVO;

$locations = [
    CoordinatesVO::from(['latitude' => 48.8566, 'longitude' => 2.3522]),   // Paris (Nord)
    CoordinatesVO::from(['latitude' => -33.8688, 'longitude' => 151.2093]), // Sydney (Sud)
    CoordinatesVO::from(['latitude' => 40.7128, 'longitude' => -74.0060]),  // New York (Nord)
];

$northern = array_filter($locations, fn($loc) => $loc->isNorthernHemisphere());
$southern = array_filter($locations, fn($loc) => $loc->isSouthernHemisphere());

echo "Hémisphère Nord : " . count($northern); // 2
echo "Hémisphère Sud : " . count($southern); // 1
```

### Cas 3 : Recherche de points à proximité

```php
<?php

declare(strict_types=1);

use AndyDefer\PhpVo\Enums\SpaceTimeUnit;
use AndyDefer\PhpVo\ValueObjects\CoordinatesVO;

$center = CoordinatesVO::from(['latitude' => 48.8566, 'longitude' => 2.3522]);
$radiusKm = 10.0;

$points = [
    CoordinatesVO::from(['latitude' => 48.8600, 'longitude' => 2.3600]), // ~500m
    CoordinatesVO::from(['latitude' => 48.9000, 'longitude' => 2.4000]), // ~5km
    CoordinatesVO::from(['latitude' => 49.0000, 'longitude' => 2.5000]), // ~16km
];

$nearby = array_filter($points, function($point) use ($center, $radiusKm) {
    return $center->distanceTo($point, SpaceTimeUnit::KILOMETRE) <= $radiusKm;
});

echo "Points dans un rayon de 10km : " . count($nearby); // 2
```

## Gestion des erreurs

| Situation | Exception | Message |
|-----------|-----------|---------|
| Latitude < -90° | `InvalidArgumentException` | `Latitude must be between -90.0 and 90.0, got {value}` |
| Latitude > 90° | `InvalidArgumentException` | `Latitude must be between -90.0 and 90.0, got {value}` |
| Longitude < -180° | `InvalidArgumentException` | `Longitude must be between -180.0 and 180.0, got {value}` |
| Longitude > 180° | `InvalidArgumentException` | `Longitude must be between -180.0 and 180.0, got {value}` |
| Données source invalides | `InvalidArgumentException` | Levée par `AbstractValueObject::from()` |

## Intégration

| Composant | Relation |
|-----------|----------|
| `CoordinatesConfig` | Fournit les constantes (plages valides, rayon terrestre) |
| `SpaceTimeUnit` | Enum pour les unités de distance (KILOMETRE, METRE) |
| `CoordinatesRecord` | Conversion - `getValue()` retourne un Record pour sérialisation |
| `AbstractValueObject` | Héritage - Fournit `from()`, `fromJson()`, `equals()` |

## Exemple complet

```php
<?php

declare(strict_types=1);

use AndyDefer\PhpVo\Enums\SpaceTimeUnit;
use AndyDefer\PhpVo\ValueObjects\CoordinatesVO;

// Création depuis un tableau
$paris = CoordinatesVO::from([
    'latitude' => 48.8566,
    'longitude' => 2.3522
]);

// Création depuis JSON
$london = CoordinatesVO::fromJson('{"latitude":51.5074,"longitude":-0.1278}');

// Calcul de distance
$distance = $paris->distanceTo($london, SpaceTimeUnit::KILOMETRE);
echo "Distance Paris-Londres : {$distance} km\n";

// Vérification de proximité
if ($paris->isSameLocation($london, 5.0)) {
    echo "Paris et Londres sont à moins de 5 degrés l'un de l'autre\n";
} else {
    echo "Paris et Londres sont éloignés\n";
}

// Détection d'hémisphère
echo "Paris est dans l'hémisphère " . 
     ($paris->isNorthernHemisphere() ? "Nord" : "Sud") . "\n";

// Formatage
echo "Coordonnées : " . $paris->format(3) . "\n";
// "Coordonnées : 48.857, 2.352"

// Conversion en Record pour stockage
$record = $paris->getValue();

// Comparaison
$parisCopy = CoordinatesVO::from($paris);
var_dump($paris->equals($parisCopy)); // true

// Utilisation en chaîne (__toString)
echo $paris; // "48.8566, 2.3522"
```

## Notes additionnelles

- La validation des plages utilise `CoordinatesConfig` pour centraliser les constantes
- Le calcul de distance utilise la formule de Haversine (sphérique, non ellipsoïdale)
- La précision par défaut pour `isSameLocation()` est d'environ 11 mètres à l'équateur
- Les unités supportées sont limitées à `SpaceTimeUnit::KILOMETRE` et `SpaceTimeUnit::METRE`
- L'immutabilité est garantie par les propriétés `readonly` (PHP 8.1+)
- Les hémisphères sont déterminés par le signe (strict) : 0° n'appartient à aucun hémisphère