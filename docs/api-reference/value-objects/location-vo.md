# LocationVO - Référence Technique

## Description

Value Object immuable représentant une paire de coordonnées géographiques (`[longitude, latitude]`) au format attendu par LocationIQ. Valide l'arité (exactement 2 floats) et l'homogénéité numérique à la construction.

## Hiérarchie

```
AbstractValueObject
    └── LocationVO
```

## Rôle principal

Encapsule la représentation d'une localisation géographique telle qu'attendue par l'API LocationIQ : un tableau ordonné de deux floats `[longitude, latitude]`. Sert de frontière entre les données brutes (tableaux, chaînes numériques) et un concept métier typé. Ne contient aucune logique de calcul géographique — celle-ci appartient à `CoordinatesVO`.

## Installation

```bash
composer require andydefer/php-vo
```

## API / Méthodes publiques

### `__construct(FloatTypedCollection $coordinates)`

Construit une instance depuis une collection typée de floats.

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$coordinates` | `FloatTypedCollection` | Collection ordonnée `[longitude, latitude]` |

**Retourne :** `void`

**Exceptions :** `InvalidArgumentException` — si la collection ne contient pas exactement 2 éléments.

**Exemple :**
```php
$collection = new FloatTypedCollection;
$collection->add(15.3222);
$collection->add(-4.3250);

$location = new LocationVO($collection);
```

---

### `static fromArray(array $coordinates): self`

Crée une instance depuis un tableau de coordonnées numériques.

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$coordinates` | `array<int, float\|int\|string>` | Tableau ordonné `[longitude, latitude]` |

**Retourne :** `self` — Nouvelle instance de `LocationVO`

**Exceptions :**
- `InvalidArgumentException` — si une coordonnée n'est pas numérique.
- `InvalidArgumentException` — si l'arité est différente de 2.

**Exemple :**
```php
$location = LocationVO::fromArray([15.3222, -4.3250]);
```

---

### `toCoordinates(): CoordinatesVO`

Convertit ce `LocationVO` en `CoordinatesVO` (latitude/longitude séparées).

**Retourne :** `CoordinatesVO` — Instance équivalente

**Exemple :**
```php
$coords = $location->toCoordinates();
echo $coords->getLatitude()->getValue(); // 48.8566
```

---

### `withLongitude(float $longitude): self`

Retourne une nouvelle instance avec une longitude remplacée.

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$longitude` | `float` | Nouvelle longitude |

**Retourne :** `self` — Nouvelle instance

**Exemple :**
```php
$updated = $location->withLongitude(10.0);
```

---

### `withLatitude(float $latitude): self`

Retourne une nouvelle instance avec une latitude remplacée.

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$latitude` | `float` | Nouvelle latitude |

**Retourne :** `self` — Nouvelle instance

**Exemple :**
```php
$updated = $location->withLatitude(10.0);
```

---

### `getLongitude(): float`

Retourne la longitude (premier élément).

**Retourne :** `float`

**Exemple :**
```php
echo $location->getLongitude(); // 15.3222
```

---

### `getLatitude(): float`

Retourne la latitude (second élément).

**Retourne :** `float`

**Exemple :**
```php
echo $location->getLatitude(); // -4.325
```

---

### `getCoordinateCount(): int`

Retourne le nombre exact de coordonnées stockées.

**Retourne :** `int` — Toujours `2`

**Exemple :**
```php
echo $location->getCoordinateCount(); // 2
```

---

### `getValue(): FloatTypedCollection`

Retourne la collection interne de coordonnées.

**Retourne :** `FloatTypedCollection`

**Exemple :**
```php
$collection = $location->getValue();
$collection->toArray(); // [15.3222, -4.325]
```

---

### `toArray(): array`

Retourne les coordonnées sous forme de tableau scalaire brut.

**Retourne :** `array{0: float, 1: float}`

**Exemple :**
```php
$location->toArray(); // [15.3222, -4.325]
```

---

### `equals(AbstractValueObject $other): bool`

Compare deux instances par valeur.

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$other` | `AbstractValueObject` | Instance à comparer |

**Retourne :** `bool` — `true` si les coordonnées sont identiques

**Exemple :**
```php
$a = LocationVO::fromArray([15.3222, -4.3250]);
$b = LocationVO::fromArray([15.3222, -4.3250]);

$a->equals($b); // true
```

---

### `__toString(): string`

Retourne la représentation `longitude,latitude` avec 6 décimales.

**Retourne :** `string`

**Exemple :**
```php
echo (string) $location; // "15.322200,-4.325000"
```

---

## Cas d'utilisation

### Cas 1 : Préparer une requête vers l'API LocationIQ

LocationIQ attend un paramètre `coordinates` au format `longitude,latitude`.

```php
$location = LocationVO::fromArray([2.3522, 48.8566]);

$query = http_build_query([
    'coordinates' => (string) $location, // "2.352200,48.856600"
    'radius' => 500,
]);
```

### Cas 2 : Convertir une réponse API en objet métier

```php
$apiResponse = ['lon' => '15.3222', 'lat' => '-4.3250'];

$location = LocationVO::fromArray([
    $apiResponse['lon'],
    $apiResponse['lat'],
]);

$coords = $location->toCoordinates();
```

### Cas 3 : Mise à jour immuable

```php
$original = LocationVO::fromArray([2.3522, 48.8566]);
$moved = $original->withLatitude(48.9000);

echo $original->getLatitude(); // 48.8566
echo $moved->getLatitude();    // 48.9
```

## Flux d'exécution

```
fromArray($coords)
    ↓
is_numeric? ── non ──→ InvalidArgumentException
    ↓ oui
FloatTypedCollection::add((float) $coord)
    ↓ (pour chaque élément)
new LocationVO($collection)
    ↓
count === 2? ── non ──→ InvalidArgumentException
    ↓ oui
Instance validée
```

## Gestion des erreurs

| Situation | Exception | Message |
|-----------|-----------|---------|
| Coordonnée non numérique | `InvalidArgumentException` | `Location coordinate must be numeric, %s given.` (`%s` = `get_debug_type`) |
| Arité ≠ 2 à la construction | `InvalidArgumentException` | `Location must contain exactly 2 floats, %d given.` |
| Arité ≠ 2 via `fromArray` | `InvalidArgumentException` | `Location must contain exactly 2 floats, %d given.` |
| Tableau vide | `InvalidArgumentException` | `Location must contain exactly 2 floats, 0 given.` |

## Intégration

```
Données brutes (array, string, API)
        │
        ▼
   LocationVO::fromArray()
        │
        ├──► getLongitude() / getLatitude()  → usage direct
        ├──► toArray()                       → payload JSON / API
        ├──► __toString()                    → paramètre query LocationIQ
        └──► toCoordinates()                 → calculs géographiques
                    │
                    ▼
              CoordinatesVO
                    │
                    └──► distanceTo(), isSameLocation(), etc.
```

`LocationVO` est conçu pour rester la représentation brute côté LocationIQ. Toute logique de calcul, comparaison géographique ou formatage avancé se fait après conversion en `CoordinatesVO`.

## Performance

- Construction en O(1) : deux itérations maximum sur `fromArray` (2 éléments).
- Validation par `is_numeric()` et `count()` — opérations natives rapides.
- `equals()` compare deux tableaux de 2 floats — comparaison triviale.
- `__toString()` alloue une chaîne via `sprintf` — coût négligeable.
- Aucun cache nécessaire.

## Compatibilité

| Version PHP | Support |
|-------------|---------|
| PHP 8.1+ | ✅ Complet (readonly properties, enums) |
| PHP 8.0 | ⚠️ Nécessite adaptation si `readonly` non supporté |

## Exemple complet

```php
<?php

declare(strict_types=1);

use AndyDefer\PhpVo\ValueObjects\LocationVO;
use AndyDefer\PhpVo\ValueObjects\CoordinatesVO;

// 1. Créer depuis un tableau brut
$location = LocationVO::fromArray([2.3522, 48.8566]);

// 2. Accéder aux composantes
echo $location->getLongitude(); // 2.3522
echo $location->getLatitude();  // 48.8566

// 3. Utiliser dans une requête API
$url = sprintf(
    'https://us1.locationiq.com/v1/nearby?key=%s&coordinates=%s',
    $_ENV['LOCATIONIQ_KEY'],
    (string) $location
);

// 4. Mise à jour immuable
$moved = $location->withLatitude(48.9000);
assert($location->getLatitude() !== $moved->getLatitude());

// 5. Conversion pour calculs
$coords = $location->toCoordinates();
assert($coords instanceof CoordinatesVO);

// 6. Sérialisation JSON
$payload = json_encode([
    'coordinates' => $location->toArray(),
]); // {"coordinates":[2.3522,48.8566]}
```