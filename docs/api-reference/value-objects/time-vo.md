# TimeVO - Référence Technique

## Description

`TimeVO` est un Value Object immutable qui représente une heure de la journée (heures, minutes, secondes). Il fournit une wrapper type-safe autour des valeurs temporelles avec support de multiples formats d'entrée, comparaisons, arithmétique et conversion de fuseaux horaires.

## Hiérarchie

```
AbstractValueObject
    └── TimeVO
```

**Étend :** `AbstractValueObject`

## Rôle principal

`TimeVO` assure :

1. **Validation** automatique des heures (0-23), minutes (0-59) et secondes (0-59)
2. **Parsing** de multiples formats (H:i:s, H:i, ISO 8601, secondes depuis minuit, array)
3. **Arithmétique** immutable (addition/soustraction d'heures, minutes, secondes)
4. **Comparaison** type-safe (`isAfter()`, `isBefore()`, `isEqual()`, `isBetween()`)
5. **Formatage** personnalisé via `format()`
6. **Accès** aux composants (`getHours()`, `getMinutes()`, `getSeconds()`, `getTotalSeconds()`)

---

## API / Méthodes publiques

### `__construct(?string $value = null)`

Crée une nouvelle instance TimeVO.

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$value` | `string|null` | Heure (ex: '14:30:00') ou null pour l'heure actuelle |

**Exceptions :** `InvalidArgumentException` si la valeur est invalide

**Exemple :**
```php
$time = new TimeVO('14:30:00');
$now = new TimeVO(); // Heure actuelle
```

---

### `from(string|null $value): self`

Alias du constructeur. Crée une instance depuis une chaîne.

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$value` | `string|null` | Heure (ex: '14:30:00') ou null pour l'heure actuelle |

**Retourne :** `self` - Nouvelle instance

**Exceptions :** `InvalidArgumentException` si la valeur est invalide

**Exemple :**
```php
$time = TimeVO::from('14:30:00');
$time = TimeVO::from('14:30');   // 14:30:00
$time = TimeVO::from('52200');   // 14:30:00 (secondes depuis minuit)
$time = TimeVO::from('[14, 30]'); // 14:30:00
```

---

### `fromCarbon(CarbonInterface $carbon): self`

Crée une instance à partir d'un objet Carbon.

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$carbon` | `CarbonInterface` | Instance Carbon à convertir |

**Retourne :** `self` - Nouvelle instance

**Exemple :**
```php
$carbon = Carbon::create(2024, 1, 15, 14, 30, 0);
$time = TimeVO::fromCarbon($carbon);
```

---

### `now(): self`

Crée une instance avec l'heure actuelle.

**Retourne :** `self` - Nouvelle instance

**Exemple :**
```php
$now = TimeVO::now();
```

---

### `create(int $hours, int $minutes = 0, int $seconds = 0): self`

Crée une instance avec des valeurs spécifiques.

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$hours` | `int` | Heure (0-23) |
| `$minutes` | `int` | Minutes (0-59, défaut: 0) |
| `$seconds` | `int` | Secondes (0-59, défaut: 0) |

**Retourne :** `self` - Nouvelle instance

**Exceptions :** `InvalidArgumentException` si les valeurs sont invalides

**Exemple :**
```php
$time = TimeVO::create(14, 30, 0); // 14:30:00
$time = TimeVO::create(14, 30);    // 14:30:00
$time = TimeVO::create(14);        // 14:00:00
```

---

### `fromSeconds(int $seconds): self`

Crée une instance à partir de secondes depuis minuit.

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$seconds` | `int` | Secondes depuis minuit (0-86399) |

**Retourne :** `self` - Nouvelle instance

**Exceptions :** `InvalidArgumentException` si la valeur est invalide

**Exemple :**
```php
$time = TimeVO::fromSeconds(52200); // 14:30:00
```

---

### `getCarbon(): CarbonInterface`

Retourne l'instance Carbon sous-jacente.

**Retourne :** `CarbonInterface` - Instance Carbon interne

---

### `getValue(): string`

Retourne l'heure au format H:i:s.

**Retourne :** `string` - Heure au format H:i:s

**Exemple :**
```php
$time = TimeVO::from('14:30:00');
echo $time->getValue(); // '14:30:00'
```

---

### `toDateTime(): DateTime`

Convertit en instance native DateTime.

**Retourne :** `DateTime` - Instance DateTime

---

### `toTimeString(): string`

Retourne l'heure au format H:i:s.

**Retourne :** `string` - Heure au format H:i:s

---

### `format(string $format): string`

Formate l'heure avec un format PHP personnalisé.

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$format` | `string` | Format PHP (ex: 'H:i', 'g:i A') |

**Retourne :** `string` - Heure formatée

**Exemple :**
```php
$time = TimeVO::from('14:30:00');
echo $time->format('H:i');   // '14:30'
echo $time->format('h:i A'); // '02:30 PM'
```

---

### `getHours(): int`

Retourne le composant des heures (0-23).

**Retourne :** `int` - Heures

---

### `getMinutes(): int`

Retourne le composant des minutes (0-59).

**Retourne :** `int` - Minutes

---

### `getSeconds(): int`

Retourne le composant des secondes (0-59).

**Retourne :** `int` - Secondes

---

### `getTotalSeconds(): float`

Retourne le nombre total de secondes depuis minuit (0-86399).

**Retourne :** `float` - Secondes totales

---

### `isAfter(self $other): bool`

Vérifie si l'heure est après une autre heure.

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$other` | `self` | Heure à comparer |

**Retourne :** `bool` - True si après

---

### `isBefore(self $other): bool`

Vérifie si l'heure est avant une autre heure.

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$other` | `self` | Heure à comparer |

**Retourne :** `bool` - True si avant

---

### `isEqual(self $other): bool`

Vérifie si l'heure est égale à une autre heure.

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$other` | `self` | Heure à comparer |

**Retourne :** `bool` - True si égales

---

### `isBetween(self $start, self $end, bool $includeStart = true, bool $includeEnd = true): bool`

Vérifie si l'heure est entre deux heures (support du passage de minuit).

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$start` | `self` | Heure de début |
| `$end` | `self` | Heure de fin |
| `$includeStart` | `bool` | Inclure le début (défaut: true) |
| `$includeEnd` | `bool` | Inclure la fin (défaut: true) |

**Retourne :** `bool` - True si entre

**Exemple :**
```php
$start = TimeVO::from('22:00:00');
$end = TimeVO::from('06:00:00');
$night = TimeVO::from('02:00:00');

$night->isBetween($start, $end); // true (overnight)
```

---

### `addHours(int $hours): self`

Ajoute un nombre d'heures. Gère le dépassement de minuit.

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$hours` | `int` | Nombre d'heures à ajouter |

**Retourne :** `self` - Nouvelle instance

**Exemple :**
```php
$time = TimeVO::from('23:00:00');
$new = $time->addHours(2);
echo $new->getValue(); // '01:00:00'
```

---

### `subHours(int $hours): self`

Soustrait un nombre d'heures. Gère le dépassement de minuit.

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$hours` | `int` | Nombre d'heures à soustraire |

**Retourne :** `self` - Nouvelle instance

---

### `addMinutes(int $minutes): self`

Ajoute un nombre de minutes.

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$minutes` | `int` | Nombre de minutes à ajouter |

**Retourne :** `self` - Nouvelle instance

---

### `subMinutes(int $minutes): self`

Soustrait un nombre de minutes.

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$minutes` | `int` | Nombre de minutes à soustraire |

**Retourne :** `self` - Nouvelle instance

---

### `addSeconds(int $seconds): self`

Ajoute un nombre de secondes.

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$seconds` | `int` | Nombre de secondes à ajouter |

**Retourne :** `self` - Nouvelle instance

---

### `subSeconds(int $seconds): self`

Soustrait un nombre de secondes.

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$seconds` | `int` | Nombre de secondes à soustraire |

**Retourne :** `self` - Nouvelle instance

---

### `diffInSeconds(self $other): float`

Calcule la différence absolue en secondes.

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$other` | `self` | Heure à comparer |

**Retourne :** `float` - Différence en secondes (toujours positive)

---

### `diffInMinutes(self $other): int`

Calcule la différence absolue en minutes.

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$other` | `self` | Heure à comparer |

**Retourne :** `int` - Différence en minutes (toujours positive)

---

### `diffInHours(self $other): int`

Calcule la différence absolue en heures.

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$other` | `self` | Heure à comparer |

**Retourne :** `int` - Différence en heures (toujours positive)

---

### `isMidnight(): bool`

Vérifie si l'heure est minuit (00:00:00).

**Retourne :** `bool` - True si minuit

---

### `isNoon(): bool`

Vérifie si l'heure est midi (12:00:00).

**Retourne :** `bool` - True si midi

---

### `isMorning(): bool`

Vérifie si l'heure est dans la matinée (00:00:00 à 11:59:59).

**Retourne :** `bool` - True si matin

---

### `isAfternoon(): bool`

Vérifie si l'heure est dans l'après-midi (12:00:00 à 17:59:59).

**Retourne :** `bool` - True si après-midi

---

### `isEvening(): bool`

Vérifie si l'heure est dans la soirée (18:00:00 à 23:59:59).

**Retourne :** `bool` - True si soirée

---

### `compareTo(self $other): int`

Compare cette heure avec une autre.

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$other` | `self` | Heure à comparer |

**Retourne :** `int` - -1 si avant, 0 si égale, 1 si après

---

### `__toString(): string`

Retourne l'heure au format H:i:s.

**Retourne :** `string` - Heure au format H:i:s

---

## Cas d'utilisation

### Cas 1 : Planning de disponibilité

```php
$opening = TimeVO::from('09:00:00');
$closing = TimeVO::from('18:00:00');
$appointment = TimeVO::from('14:30:00');

if ($appointment->isBetween($opening, $closing)) {
    echo "Le rendez-vous est dans les horaires d'ouverture";
}
```

### Cas 2 : Gestion de créneaux horaires

```php
$shiftStart = TimeVO::from('22:00:00');
$shiftEnd = TimeVO::from('06:00:00');
$clockIn = TimeVO::from('02:30:00');

if ($clockIn->isBetween($shiftStart, $shiftEnd)) {
    echo "Pointage valide (horaire de nuit)";
}
```

### Cas 3 : Calcul de durée

```php
$start = TimeVO::from('09:00:00');
$end = TimeVO::from('17:30:00');

$hours = $end->diffInHours($start);      // 8
$minutes = $end->diffInMinutes($start);  // 510
$seconds = $end->diffInSeconds($start);  // 30600
```

---

## Flux d'exécution

```
new TimeVO('14:30:00')
    ↓
parseTime()
    ├── parseTimeFormat() → 14:30:00
    ├── parseIso8601Time() → 14:30:00
    ├── parseSecondsSinceMidnight() → 52200 → 14:30:00
    ├── parseArray() → [14,30,0] → 14:30:00
    └── parseGeneric() → Carbon::parse()
    ↓
Carbon::createFromTime(14, 30, 0)
    ↓
Instance TimeVO (immutable)
```

---

## Gestion des erreurs

| Situation | Exception | Message |
|-----------|-----------|---------|
| Heure invalide | `InvalidArgumentException` | `Invalid time value: X` |
| Heure hors limites (0-23) | `InvalidArgumentException` | `Invalid hour: X. Must be between 0 and 23.` |
| Minutes hors limites (0-59) | `InvalidArgumentException` | `Invalid minute: X. Must be between 0 and 59.` |
| Secondes hors limites (0-59) | `InvalidArgumentException` | `Invalid second: X. Must be between 0 and 59.` |
| Secondes depuis minuit hors limites | `InvalidArgumentException` | `Invalid seconds: X. Must be between 0 and 86399.` |

---

## Intégration

### Avec DateTimeVO

```php
$date = DateTimeVO::from('2024-01-15T14:30:00+01:00');
$time = TimeVO::fromCarbon($date->getCarbon());
echo $time->getValue(); // '14:30:00'
```

### Avec les collections

```php
use AndyDefer\PhpVo\Collections\TimeCollection;

$times = new TimeCollection();
$times->add(TimeVO::from('09:00:00'));
$times->add(TimeVO::from('12:00:00'));
$times->add(TimeVO::from('17:00:00'));
```

---

## Performance

- **Construction** : O(1) avec parsing
- **Comparaison** : O(1) (comparaison de flottants)
- **Arithmétique** : O(1) (opérations Carbon)
- **Taille** : 1 objet Carbon interne

---

## Compatibilité

| Version | Support |
|---------|---------|
| PHP 8.1+ | ✅ Complet |
| PHP 8.2+ | ✅ Complet |

---

## Exemple complet

```php
<?php

declare(strict_types=1);

use AndyDefer\PhpVo\ValueObjects\TimeVO;

// 1. Création
$time = TimeVO::from('14:30:00');
$time2 = TimeVO::create(14, 30);
$time3 = TimeVO::fromSeconds(52200);

// 2. Accès
echo "Heure: " . $time->getValue() . "\n";
echo "Heures: " . $time->getHours() . "\n";
echo "Minutes: " . $time->getMinutes() . "\n";
echo "Secondes: " . $time->getSeconds() . "\n";
echo "Total secondes: " . $time->getTotalSeconds() . "\n";

// 3. Comparaison
$morning = TimeVO::from('09:00:00');
$evening = TimeVO::from('18:00:00');

if ($time->isBetween($morning, $evening)) {
    echo "Dans les horaires de bureau\n";
}

// 4. Arithmétique
$newTime = $time
    ->addHours(2)
    ->addMinutes(30)
    ->subHours(1);

echo $newTime->getValue(); // '16:00:00'

// 5. Formatage
echo $time->format('H:i');    // '14:30'
echo $time->format('h:i A');  // '02:30 PM'

// 6. Différence
$start = TimeVO::from('09:00:00');
$end = TimeVO::from('17:30:00');
echo "Durée: " . $end->diffInHours($start) . " heures\n";
echo "Durée: " . $end->diffInMinutes($start) . " minutes\n";

// 7. États
$midnight = TimeVO::from('00:00:00');
echo $midnight->isMidnight() ? 'Minuit' : 'Pas minuit';

// 8. Overnight
$startNight = TimeVO::from('22:00:00');
$endNight = TimeVO::from('06:00:00');
$nightTime = TimeVO::from('02:00:00');

if ($nightTime->isBetween($startNight, $endNight)) {
    echo "Horaire de nuit\n";
}
```

---

## Voir aussi

- `DateTimeVO` - Value Object date/heure complet
- `AbstractValueObject` - Classe de base des Value Objects
- `TimeCollection` - Collection type-safe de TimeVO