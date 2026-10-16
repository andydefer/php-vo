# CoordinatesVO - Référence Technique

## Description

Value Object immuable représentant des coordonnées géographiques (latitude/longitude). Valide les bornes à la construction, calcule des distances via la formule de Haversine, détecte les hémisphères et se convertit vers `LocationVO`. Supporte un UUID, un nom et une description optionnels pour labelliser un point.

## Hiérarchie / Implémentations

```
AbstractValueObject
    └── CoordinatesVO
```

## Rôle principal

Encapsule le concept métier de coordonnées géographiques sous forme de deux `FloatVO` (`latitude`, `longitude`). Fournit les opérations géographiques usuelles : distance, comparaison avec tolérance, détection d'hémisphère. Sert de passerelle vers `LocationVO` pour l'interopérabilité LocationIQ. Permet d'attacher une identité (UUID), un nom et une description à un point pour simplifier l'affichage et le suivi.

## Installation

```bash
composer require andydefer/php-vo
```

## API / Méthodes publiques

### `__construct(FloatVO $latitude, FloatVO $longitude, ?UuidVO $uuid = null, ?StringVO $name = null, ?StringVO $description = null)`

Construit une instance validée.

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$latitude` | `FloatVO` | Latitude entre `-90` et `90` |
| `$longitude` | `FloatVO` | Longitude entre `-180` et `180` |
| `$uuid` | `?UuidVO` | Identifiant optionnel |
| `$name` | `?StringVO` | Nom lisible optionnel |
| `$description` | `?StringVO` | Description optionnelle |

**Retourne :** `void`

**Exceptions :** `InvalidArgumentException` — si latitude ou longitude hors bornes.

**Exemple :**
```php
$coords = new CoordinatesVO(
    FloatVO::from(48.8566),
    FloatVO::from(2.3522),
    UuidVO::generate(),
    StringVO::from('Pharmacie centrale'),
    StringVO::from('Point de retrait principal'),
);
```

---

### `getValue(): Associative`

Retourne la représentation structurée des coordonnées et des labels.

**Retourne :** `Associative` — Clés : `latitude`, `longitude`, `uuid`, `name`, `description`

**Exemple :**
```php
$value = $coords->getValue();
$value->latitude;    // 48.8566
$value->longitude;   // 2.3522
$value->uuid;        // "xxxxxxxx-xxxx-..." ou null
$value->name;        // "Pharmacie centrale" ou null
```

---

### `toLocation(): LocationVO`

Convertit vers `LocationVO` (ordre `[longitude, latitude]`). Les labels ne sont **pas** propagés (voir `toCoordinates()` pour la préservation).

**Retourne :** `LocationVO`

**Exemple :**
```php
$location = $coords->toLocation();
```

---

### `toCoordinates(): self`

Retourne une nouvelle instance équivalente, labels préservés.

**Retourne :** `self`

**Exemple :**
```php
$copy = $coords->toCoordinates();
```

---

### `distanceTo(self $other, SpaceTimeUnit $unit = SpaceTimeUnit::KILOMETRE): FloatVO`

Calcule la distance à une autre coordonnée via la formule de Haversine.

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$other` | `self` | Coordonnées cibles |
| `$unit` | `SpaceTimeUnit` | Unité (`KILOMETRE` par défaut, ou `METRE`) |

**Retourne :** `FloatVO` — Distance arrondie à 2 décimales dans l'unité demandée

**Exemple :**
```php
$paris = CoordinatesVO::from(['latitude' => 48.8566, 'longitude' => 2.3522]);
$london = CoordinatesVO::from(['latitude' => 51.5074, 'longitude' => -0.1278]);

echo $paris->distanceTo($london)->getValue(); // ~343.56
```

---

### `isSameLocation(CoordinatesVO $other, ?float $tolerance = null): BoolVO`

Vérifie si deux coordonnées sont identiques dans une tolérance donnée.

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$other` | `CoordinatesVO` | Coordonnées à comparer |
| `$tolerance` | `?float` | Tolérance (défaut `0.0001`) |

**Retourne :** `BoolVO`

**Exemple :**
```php
$a = CoordinatesVO::from(['latitude' => 48.8566, 'longitude' => 2.3522]);
$b = CoordinatesVO::from(['latitude' => 48.8567, 'longitude' => 2.3523]);

$a->isSameLocation($b, 0.0002)->getValue();  // true
$a->isSameLocation($b, 0.00001)->getValue(); // false
```

---

### `format(int $decimals = 4): StringVO`

Formate les coordonnées en chaîne `latitude, longitude`.

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$decimals` | `int` | Nombre de décimales (défaut `4`) |

**Retourne :** `StringVO`

**Exemple :**
```php
$coords->format()->getValue();     // "48.8566, 2.3522"
$coords->format(2)->getValue();    // "48.86, 2.35"
```

---

### `isNorthernHemisphere(): BoolVO`

Retourne `true` si `latitude > 0`.

**Retourne :** `BoolVO`

---

### `isSouthernHemisphere(): BoolVO`

Retourne `true` si `latitude < 0`.

**Retourne :** `BoolVO`

---

### `isEasternHemisphere(): BoolVO`

Retourne `true` si `longitude > 0`.

**Retourne :** `BoolVO`

---

### `isWesternHemisphere(): BoolVO`

Retourne `true` si `longitude < 0`.

**Retourne :** `BoolVO`

---

### `getLatitude(): FloatVO`

Retourne la latitude.

**Retourne :** `FloatVO`

---

### `getLongitude(): FloatVO`

Retourne la longitude.

**Retourne :** `FloatVO`

---

### `getUuid(): ?UuidVO`

Retourne l'UUID optionnel.

**Retourne :** `?UuidVO`

**Exemple :**
```php
$coords->getUuid()?->getValue(); // "xxxxxxxx-xxxx-..."
```

---

### `getName(): ?StringVO`

Retourne le nom optionnel.

**Retourne :** `?StringVO`

**Exemple :**
```php
$coords->getName()?->getValue(); // "Pharmacie centrale"
```

---

### `getDescription(): ?StringVO`

Retourne la description optionnelle.

**Retourne :** `?StringVO`

**Exemple :**
```php
$coords->getDescription()?->getValue(); // "Point de retrait principal"
```

---

### `isNamed(): bool`

Retourne `true` si au moins un label est présent.

**Retourne :** `bool`

**Exemple :**
```php
$coords->isNamed(); // true|false
```

---

### `withUuid(?UuidVO $uuid): self`

Retourne une nouvelle instance avec un UUID remplacé.

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$uuid` | `?UuidVO` | Nouvel UUID ou `null` pour le retirer |

**Retourne :** `self`

**Exemple :**
```php
$identified = $coords->withUuid(UuidVO::generate());
$anonymous = $coords->withUuid(null);
```

---

### `withName(?StringVO $name): self`

Retourne une nouvelle instance avec un nom remplacé.

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$name` | `?StringVO` | Nouveau nom ou `null` pour le retirer |

**Retourne :** `self`

**Exemple :**
```php
$renamed = $coords->withName(StringVO::from('Pharmacie Nord'));
```

---

### `withDescription(?StringVO $description): self`

Retourne une nouvelle instance avec une description remplacée.

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$description` | `?StringVO` | Nouvelle description ou `null` pour la retirer |

**Retourne :** `self`

**Exemple :**
```php
$described = $coords->withDescription(StringVO::from('Ouvert 24/7'));
```

---

### `equals(AbstractValueObject $other): bool`

Compare deux instances par valeur (coordonnées et labels).

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$other` | `AbstractValueObject` | Instance à comparer |

**Retourne :** `bool`

**Exemple :**
```php
$a->equals($b); // true|false
```

---

### `__toString(): string`

Retourne le résultat de `format()`.

**Retourne :** `string`

**Exemple :**
```php
echo (string) $coords; // "48.8566, 2.3522"
```

---

## Cas d'utilisation

### Cas 1 : Calculer une distance entre deux villes

```php
$paris = CoordinatesVO::from(['latitude' => 48.8566, 'longitude' => 2.3522]);
$newYork = CoordinatesVO::from(['latitude' => 40.7128, 'longitude' => -74.0060]);

$km = $paris->distanceTo($newYork);
echo $km->getValue(); // ~5837.0

$m = $paris->distanceTo($newYork, SpaceTimeUnit::METRE);
echo $m->getValue(); // ~5837000.0
```

### Cas 2 : Vérifier un rayon de proximité

```php
$store = CoordinatesVO::from(['latitude' => 48.8566, 'longitude' => 2.3522]);
$user = CoordinatesVO::from(['latitude' => 48.8600, 'longitude' => 2.3600]);

$withinRadius = $store->distanceTo($user)->lessThan(5)->getValue();
```

### Cas 3 : Enrichir un point avec un label

```php
$point = CoordinatesVO::from(['latitude' => 48.8566, 'longitude' => 2.3522]);

$named = $point
    ->withUuid(UuidVO::generate())
    ->withName(StringVO::from('Pharmacie centrale'))
    ->withDescription(StringVO::from('Ouvert 24/7'));

echo $named->isNamed();               // true
echo $named->getName()?->getValue();  // "Pharmacie centrale"
```

### Cas 4 : Préparer un payload LocationIQ

```php
$coords = CoordinatesVO::from(['latitude' => 48.8566, 'longitude' => 2.3522]);

$payload = [
    'coordinates' => (string) $coords->toLocation(), // "2.352200,48.856600"
];
```

## Flux d'exécution

```
new CoordinatesVO(FloatVO, FloatVO, ?UuidVO, ?StringVO, ?StringVO)
    ↓
validate()
    ├── validateLatitude()  → hors bornes → InvalidArgumentException
    └── validateLongitude() → hors bornes → InvalidArgumentException
    ↓
Instance validée

distanceTo($other)
    ├── calculateCentralAngle()  → Haversine (radians → a → c)
    ├── getEarthRadius($unit)    → 6371 km ou ×1000 m
    └── multiply(radius)->round(2)
    ↓
FloatVO
```

## Gestion des erreurs

| Situation | Exception | Message |
|-----------|-----------|---------|
| Latitude hors bornes | `InvalidArgumentException` | `Latitude must be between -90.0 and 90.0, got X.XXXXXX` |
| Longitude hors bornes | `InvalidArgumentException` | `Longitude must be between -180.0 and 180.0, got X.XXXXXX` |

## Intégration

```
LocationVO ──toCoordinates()──► CoordinatesVO
                                     │
                                     ├──► distanceTo()
                                     ├──► isSameLocation()
                                     ├──► format()
                                     ├──► isXHemisphere()
                                     ├──► getUuid()/getName()/getDescription()
                                     ├──► withUuid()/withName()/withDescription()
                                     └──► toLocation() ──► LocationVO
```

Utilisé en aval de `LocationVO` pour toute opération de calcul géographique. `CoordinatesConfig` fournit les bornes de validation via `CoordinatesConfigInterface`.

## Performance

- Construction : validation en O(1) via comparaisons de `FloatVO`.
- `distanceTo()` : formule de Haversine, ~10 opérations arithmétiques sur `FloatVO`.
- `format()` : un seul `sprintf`.
- `withX()` : recrée une instance — pas de mutation.
- Aucune mise en cache nécessaire.

## Compatibilité

| Version PHP | Support |
|-------------|---------|
| PHP 8.1+ | ✅ Complet |
| PHP 8.0 | ⚠️ Vérifier `readonly` et `M_PI` |

## Exemple complet

```php
<?php

declare(strict_types=1);

use AndyDefer\PhpVo\Enums\SpaceTimeUnit;
use AndyDefer\PhpVo\ValueObjects\CoordinatesVO;
use AndyDefer\PhpVo\ValueObjects\Strings\UuidVO;
use AndyDefer\PhpVo\ValueObjects\Types\FloatVO;
use AndyDefer\PhpVo\ValueObjects\Types\StringVO;

// 1. Créer depuis un tableau
$paris = CoordinatesVO::from([
    'latitude' => 48.8566,
    'longitude' => 2.3522,
]);

// 2. Accéder aux composantes
echo $paris->getLatitude()->getValue();  // 48.8566
echo $paris->getLongitude()->getValue(); // 2.3522

// 3. Formater
echo (string) $paris; // "48.8566, 2.3522"

// 4. Hémisphères
assert($paris->isNorthernHemisphere()->getValue() === true);
assert($paris->isEasternHemisphere()->getValue() === true);

// 5. Distance
$london = CoordinatesVO::from([
    'latitude' => 51.5074,
    'longitude' => -0.1278,
]);

$km = $paris->distanceTo($london, SpaceTimeUnit::KILOMETRE);
echo $km->getValue(); // ~343.56

// 6. Enrichir avec un label
$named = $paris
    ->withUuid(UuidVO::generate())
    ->withName(StringVO::from('Siège Paris'))
    ->withDescription(StringVO::from('Bureaux principaux'));

echo $named->isNamed();               // true
echo $named->getName()?->getValue();  // "Siège Paris"

// 7. Conversion vers LocationVO
$location = $named->toLocation();
echo (string) $location; // "2.352200,48.856600"
```