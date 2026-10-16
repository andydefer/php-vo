# LocationVO - Référence Technique

## Description

Value Object immuable représentant une paire de coordonnées géographiques (`[longitude, latitude]`) au format LocationIQ, avec support optionnel d'un UUID, d'un nom et d'une description.

## Hiérarchie / Implémentations

```
AbstractValueObject
    └── LocationVO
```

## Rôle principal

Encapsule la représentation d'une localisation géographique telle qu'attendue par l'API LocationIQ : un tableau ordonné de deux floats `[longitude, latitude]`. Sert de frontière entre les données brutes (tableaux, chaînes numériques) et un concept métier typé. Ne contient aucune logique de calcul géographique — celle-ci appartient à `CoordinatesVO`.

Permet également d'attacher une identité (UUID), un nom lisible et une description à un point, ce qui simplifie l'affichage côté utilisateur.

## Installation

```bash
composer require andydefer/php-vo
```

## API / Méthodes publiques

### `__construct(FloatTypedCollection $coordinates, ?UuidVO $uuid = null, ?StringVO $name = null, ?StringVO $description = null)`

Construit une instance depuis une collection typée de floats et des labels optionnels.

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$coordinates` | `FloatTypedCollection` | Collection ordonnée `[longitude, latitude]` |
| `$uuid` | `?UuidVO` | Identifiant optionnel |
| `$name` | `?StringVO` | Nom lisible optionnel |
| `$description` | `?StringVO` | Description optionnelle |

**Retourne :** `void`

**Exceptions :** `InvalidArgumentException` — si la collection ne contient pas exactement 2 éléments.

**Exemple :**
```php
$collection = new FloatTypedCollection;
$collection->add(15.3222);
$collection->add(-4.3250);

$location = new LocationVO(
    $collection,
    UuidVO::generate(),
    StringVO::from('Pharmacie Kinshasa'),
    StringVO::from('Point de retrait principal'),
);
```

---

### `static fromArray(array $coordinates, ?UuidVO $uuid = null, ?StringVO $name = null, ?StringVO $description = null): self`

Crée une instance depuis un tableau de coordonnées numériques.

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$coordinates` | `array<int, float\|int\|string>` | Tableau ordonné `[longitude, latitude]` |
| `$uuid` | `?UuidVO` | Identifiant optionnel |
| `$name` | `?StringVO` | Nom lisible optionnel |
| `$description` | `?StringVO` | Description optionnelle |

**Retourne :** `self`

**Exceptions :**
- `InvalidArgumentException` — si une coordonnée n'est pas numérique.
- `InvalidArgumentException` — si l'arité est différente de 2.

**Exemple :**
```php
$location = LocationVO::fromArray([15.3222, -4.3250]);
```

---

### `toCoordinates(): CoordinatesVO`

Convertit ce `LocationVO` en `CoordinatesVO`. Les labels (`uuid`, `name`, `description`) sont préservés.

**Retourne :** `CoordinatesVO`

**Exemple :**
```php
$coords = $location->toCoordinates();
echo $coords->getLatitude()->getValue(); // 48.8566
```

---

### `withLongitude(float $longitude): self`

Retourne une nouvelle instance avec une longitude remplacée. Les labels sont préservés.

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

Retourne une nouvelle instance avec une latitude remplacée. Les labels sont préservés.

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$latitude` | `float` | Nouvelle latitude |

**Retourne :** `self` — Nouvelle instance

**Exemple :**
```php
$updated = $location->withLatitude(10.0);
```

---

### `withUuid(?UuidVO $uuid): self`

Retourne une nouvelle instance avec un UUID remplacé.

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$uuid` | `?UuidVO` | Nouvel UUID, ou `null` pour le retirer |

**Retourne :** `self`

**Exemple :**
```php
$identified = $location->withUuid(UuidVO::generate());
$anonymous = $location->withUuid(null);
```

---

### `withName(?StringVO $name): self`

Retourne une nouvelle instance avec un nom remplacé.

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$name` | `?StringVO` | Nouveau nom, ou `null` pour le retirer |

**Retourne :** `self`

**Exemple :**
```php
$renamed = $location->withName(StringVO::from('Pharmacie Nord'));
```

---

### `withDescription(?StringVO $description): self`

Retourne une nouvelle instance avec une description remplacée.

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$description` | `?StringVO` | Nouvelle description, ou `null` pour la retirer |

**Retourne :** `self`

**Exemple :**
```php
$described = $location->withDescription(StringVO::from('Ouvert 24/7'));
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

### `getUuid(): ?UuidVO`

Retourne l'UUID optionnel.

**Retourne :** `?UuidVO`

**Exemple :**
```php
$location->getUuid()?->getValue(); // "xxxxxxxx-xxxx-..."
```

---

### `getName(): ?StringVO`

Retourne le nom optionnel.

**Retourne :** `?StringVO`

**Exemple :**
```php
$location->getName()?->getValue(); // "Pharmacie Kinshasa"
```

---

### `getDescription(): ?StringVO`

Retourne la description optionnelle.

**Retourne :** `?StringVO`

**Exemple :**
```php
$location->getDescription()?->getValue(); // "Point de retrait principal"
```

---

### `isNamed(): bool`

Retourne `true` si au moins un label (`uuid`, `name` ou `description`) est présent.

**Retourne :** `bool`

**Exemple :**
```php
$location->isNamed(); // true|false
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

Compare deux instances par valeur (coordonnées et labels).

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$other` | `AbstractValueObject` | Instance à comparer |

**Retourne :** `bool` — `true` si coordonnées et labels sont identiques

**Exemple :**
```php
$a->equals($b); // true|false
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

```php
$location = LocationVO::fromArray([2.3522, 48.8566]);

$query = http_build_query([
    'coordinates' => (string) $location, // "2.352200,48.856600"
    'radius' => 500,
]);
```

### Cas 2 : Convertir une réponse API en objet métier nommé

```php
$apiResponse = [
    'lon' => '15.3222',
    'lat' => '-4.3250',
    'name' => 'Pharmacie Kinshasa',
];

$location = LocationVO::fromArray(
    [$apiResponse['lon'], $apiResponse['lat']],
    UuidVO::generate(),
    StringVO::from($apiResponse['name']),
);

$coords = $location->toCoordinates();
```

### Cas 3 : Mise à jour immuable des coordonnées

```php
$original = LocationVO::fromArray([2.3522, 48.8566]);
$moved = $original->withLatitude(48.9000);

echo $original->getLatitude(); // 48.8566
echo $moved->getLatitude();    // 48.9
```

### Cas 4 : Enrichir un point existant avec un label

```php
$point = LocationVO::fromArray([15.3222, -4.3250]);

$named = $point
    ->withName(StringVO::from('Entrepôt Central'))
    ->withDescription(StringVO::from('Livraisons du lundi au samedi'));

echo $named->isNamed();       // true
echo $named->getName()?->getValue(); // "Entrepôt Central"
```

## Flux d'exécution

```
fromArray($coords, $uuid, $name, $description)
    ↓
Boucle sur $coords :
    ├── is_numeric ? ── non ──→ InvalidArgumentException
    └── oui → FloatTypedCollection::add((float) $coord)
    ↓
new LocationVO($collection, $uuid, $name, $description)
    ↓
count === 2 ? ── non ──→ InvalidArgumentException
    ↓ oui
Instance validée

withX($value)
    ↓
new LocationVO(...) (labels préservés)
```

## Gestion des erreurs

| Situation | Exception | Message |
|-----------|-----------|---------|
| Coordonnée non numérique | `InvalidArgumentException` | `Location coordinate must be numeric, %s given.` |
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
        ├──► getLongitude() / getLatitude()   → usage direct
        ├──► toArray()                        → payload JSON / API
        ├──► __toString()                     → paramètre query LocationIQ
        ├──► getUuid() / getName() / getDescription()  → affichage
        └──► toCoordinates()                  → calculs géographiques
                    │
                    ▼
              CoordinatesVO
                    │
                    └──► distanceTo(), isSameLocation(), etc.
```

`LocationVO` reste la représentation brute côté LocationIQ. Toute logique de calcul se fait après conversion en `CoordinatesVO`. Les labels sont propagés lors de la conversion.

## Performance

- Construction en O(1) : deux itérations maximum sur `fromArray` (2 éléments).
- Validation par `is_numeric()` et `count()` — opérations natives rapides.
- `equals()` compare coordonnées + labels : coût constant.
- `withX()` recrée une instance — pas de mutation.
- `__toString()` alloue une chaîne via `sprintf` — coût négligeable.

## Compatibilité

| Version PHP | Support |
|-------------|---------|
| PHP 8.1+ | ✅ Complet (readonly, enums) |
| PHP 8.0 | ⚠️ Vérifier le support des propriétés `readonly` |

## Exemple complet

```php
<?php

declare(strict_types=1);

use AndyDefer\PhpVo\ValueObjects\LocationVO;
use AndyDefer\PhpVo\ValueObjects\Strings\UuidVO;
use AndyDefer\PhpVo\ValueObjects\Types\StringVO;

// 1. Créer depuis un tableau brut
$location = LocationVO::fromArray([2.3522, 48.8566]);

// 2. Accéder aux composantes
echo $location->getLongitude(); // 2.3522
echo $location->getLatitude();  // 48.8566

// 3. Ajouter un label
$named = $location
    ->withUuid(UuidVO::generate())
    ->withName(StringVO::from('Siège Paris'))
    ->withDescription(StringVO::from('Bureaux principaux'));

echo $named->isNamed();               // true
echo $named->getName()?->getValue();  // "Siège Paris"

// 4. Utiliser dans une requête API
$url = sprintf(
    'https://us1.locationiq.com/v1/nearby?key=%s&coordinates=%s',
    $_ENV['LOCATIONIQ_KEY'],
    (string) $named,
);

// 5. Mise à jour immuable
$moved = $named->withLatitude(48.9000);
assert($named->getLatitude() !== $moved->getLatitude());

// 6. Conversion vers CoordinatesVO (labels préservés)
$coords = $named->toCoordinates();
echo $coords->getName()?->getValue(); // "Siège Paris"

// 7. Sérialisation JSON
$payload = json_encode([
    'coordinates' => $named->toArray(),
    'name' => $named->getName()?->getValue(),
]); // {"coordinates":[2.3522,48.8566],"name":"Siège Paris"}
```