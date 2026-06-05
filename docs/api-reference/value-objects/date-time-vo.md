# DateTimeVO - Référence Technique

## Description

Value Object immuable représentant une date/heure au format ISO 8601 avec gestion des fuseaux horaires.

## Hiérarchie

```
AbstractValueObject (andydefer/domain-structures)
    └── DateTimeVO
```

## Rôle principal

Encapsuler une date/heure avec validation stricte, support multi-formats, comparaisons temporelles, opérations arithmétiques, conversion entre fuseaux horaires, et formatage personnalisé.

## Installation

```bash
composer require andydefer/php-vo
```

## API / Méthodes publiques

### `getValue(): string`

| Paramètre | Type | Description |
|-----------|------|-------------|
| - | - | Aucun paramètre |

**Retourne :** `string` - La date/heure au format ISO8601 avec son fuseau horaire

**Exemple :**
```php
$date = DateTimeVO::from('2024-01-15T14:30:00+01:00');
echo $date->getValue(); // '2024-01-15T14:30:00+01:00'
```

---

### `toDateTimeImmutable(): DateTimeImmutable`

| Paramètre | Type | Description |
|-----------|------|-------------|
| - | - | Aucun paramètre |

**Retourne :** `DateTimeImmutable` - Instance DateTimeImmutable avec le fuseau horaire original

**Exemple :**
```php
$date = DateTimeVO::from('2024-01-15T14:30:00+01:00');
$datetime = $date->toDateTimeImmutable();
echo $datetime->format('Y-m-d H:i:s'); // '2024-01-15 14:30:00'
```

---

### `toDateTime(): DateTime`

| Paramètre | Type | Description |
|-----------|------|-------------|
| - | - | Aucun paramètre |

**Retourne :** `DateTime` - Instance DateTime avec le fuseau horaire original

**Exemple :**
```php
$date = DateTimeVO::from('2024-01-15T14:30:00+01:00');
$datetime = $date->toDateTime();
```

---

### `toUtcDateTimeImmutable(): DateTimeImmutable`

| Paramètre | Type | Description |
|-----------|------|-------------|
| - | - | Aucun paramètre |

**Retourne :** `DateTimeImmutable` - Instance DateTimeImmutable convertie en UTC

**Exemple :**
```php
$date = DateTimeVO::from('2024-01-15T14:30:00+01:00');
$utc = $date->toUtcDateTimeImmutable();
echo $utc->format('Y-m-d H:i:s'); // '2024-01-15 13:30:00'
```

---

### `toDateTimeString(): string`

| Paramètre | Type | Description |
|-----------|------|-------------|
| - | - | Aucun paramètre |

**Retourne :** `string` - La date/heure au format base de données `Y-m-d H:i:s` en UTC

**Exemple :**
```php
$date = DateTimeVO::from('2024-01-15T14:30:00+01:00');
echo $date->toDateTimeString(); // '2024-01-15 13:30:00'
```

---

### `toDateString(): string`

| Paramètre | Type | Description |
|-----------|------|-------------|
| - | - | Aucun paramètre |

**Retourne :** `string` - La date seule au format `Y-m-d` (fuseau horaire original)

**Exemple :**
```php
$date = DateTimeVO::from('2024-01-15T14:30:00+01:00');
echo $date->toDateString(); // '2024-01-15'
```

---

### `toTimeString(): string`

| Paramètre | Type | Description |
|-----------|------|-------------|
| - | - | Aucun paramètre |

**Retourne :** `string` - L'heure seule au format `H:i:s` (fuseau horaire original)

**Exemple :**
```php
$date = DateTimeVO::from('2024-01-15T14:30:00+01:00');
echo $date->toTimeString(); // '14:30:00'
```

---

### `toTimestamp(): int`

| Paramètre | Type | Description |
|-----------|------|-------------|
| - | - | Aucun paramètre |

**Retourne :** `int` - Timestamp Unix (toujours en UTC)

**Exemple :**
```php
$date = DateTimeVO::from('2024-01-15T14:30:00+00:00');
echo $date->toTimestamp(); // 1705329000
```

---

### `format(string $format): string`

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$format` | `string` | Format PHP de date (ex: 'd/m/Y H:i:s') |

**Retourne :** `string` - Date/heure formatée selon le format spécifié (fuseau horaire original)

**Exemple :**
```php
$date = DateTimeVO::from('2024-01-15T14:30:00+01:00');
echo $date->format('d/m/Y'); // '15/01/2024'
echo $date->format('H:i');    // '14:30'
```

---

### `isAfter(self $other): bool`

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$other` | `self` | Autre instance DateTimeVO à comparer |

**Retourne :** `bool` - True si la date est postérieure à l'autre (comparaison UTC)

**Exemple :**
```php
$date1 = DateTimeVO::from('2024-01-15T14:30:00+00:00');
$date2 = DateTimeVO::from('2024-01-14T14:30:00+00:00');
echo $date1->isAfter($date2); // true
```

---

### `isBefore(self $other): bool`

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$other` | `self` | Autre instance DateTimeVO à comparer |

**Retourne :** `bool` - True si la date est antérieure à l'autre (comparaison UTC)

**Exemple :**
```php
$date1 = DateTimeVO::from('2024-01-14T14:30:00+00:00');
$date2 = DateTimeVO::from('2024-01-15T14:30:00+00:00');
echo $date1->isBefore($date2); // true
```

---

### `isEqual(self $other): bool`

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$other` | `self` | Autre instance DateTimeVO à comparer |

**Retourne :** `bool` - True si les deux dates représentent le même moment (comparaison UTC)

**Exemple :**
```php
$paris = DateTimeVO::from('2024-01-15T14:30:00+01:00');
$london = DateTimeVO::from('2024-01-15T13:30:00+00:00');
echo $paris->isEqual($london); // true
```

---

### `isPast(): bool`

| Paramètre | Type | Description |
|-----------|------|-------------|
| - | - | Aucun paramètre |

**Retourne :** `bool` - True si la date est dans le passé (comparaison UTC)

**Exemple :**
```php
$date = DateTimeVO::from('2020-01-01T00:00:00+00:00');
echo $date->isPast(); // true
```

---

### `isFuture(): bool`

| Paramètre | Type | Description |
|-----------|------|-------------|
| - | - | Aucun paramètre |

**Retourne :** `bool` - True si la date est dans le futur (comparaison UTC)

**Exemple :**
```php
$date = DateTimeVO::from('2100-01-01T00:00:00+00:00');
echo $date->isFuture(); // true
```

---

### `isToday(): bool`

| Paramètre | Type | Description |
|-----------|------|-------------|
| - | - | Aucun paramètre |

**Retourne :** `bool` - True si la date est aujourd'hui (dans le fuseau horaire original)

**Exemple :**
```php
$today = DateTimeVO::from('2024-06-05');
echo $today->isToday(); // true (si aujourd'hui est le 2024-06-05)
```

---

### `add(string $interval): self`

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$interval` | `string` | Intervalle à ajouter (ex: '1 day', '3 hours', '1 week') |

**Retourne :** `self` - Nouvelle instance DateTimeVO avec l'intervalle ajouté

**Exceptions :** `InvalidArgumentException` - Si l'intervalle est invalide

**Exemple :**
```php
$date = DateTimeVO::from('2024-01-15T00:00:00+00:00');
$tomorrow = $date->add('1 day');
echo $tomorrow->toDateString(); // '2024-01-16'

$in3Hours = $date->add('3 hours');
echo $in3Hours->toTimeString(); // '03:00:00'
```

---

### `sub(string $interval): self`

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$interval` | `string` | Intervalle à soustraire (ex: '1 day', '3 hours', '1 week') |

**Retourne :** `self` - Nouvelle instance DateTimeVO avec l'intervalle soustrait

**Exceptions :** `InvalidArgumentException` - Si l'intervalle est invalide

**Exemple :**
```php
$date = DateTimeVO::from('2024-01-15T00:00:00+00:00');
$yesterday = $date->sub('1 day');
echo $yesterday->toDateString(); // '2024-01-14'
```

---

### `__toString(): string`

| Paramètre | Type | Description |
|-----------|------|-------------|
| - | - | Aucun paramètre |

**Retourne :** `string` - Identique à `getValue()`

**Exemple :**
```php
$date = DateTimeVO::from('2024-01-15T14:30:00+01:00');
echo $date; // '2024-01-15T14:30:00+01:00'
```

## Cas d'utilisation

### Cas 1 : Stockage en base de données

```php
<?php

declare(strict_types=1);

use AndyDefer\PhpVo\ValueObjects\DateTimeVO;

// Créer une date depuis l'input utilisateur (date seule)
$userDate = DateTimeVO::from('2024-06-05');

// Stocker en base au format UTC
$dbValue = $userDate->toDateTimeString(); // '2024-06-05 00:00:00'

// Ou au format ISO8601
$isoValue = $userDate->getValue(); // '2024-06-05T00:00:00+00:00'
```

### Cas 2 : Affichage localisé

```php
<?php

declare(strict_types=1);

use AndyDefer\PhpVo\ValueObjects\DateTimeVO;

// Date avec fuseau horaire Europe/Paris
$date = DateTimeVO::from('2024-01-15T14:30:00+01:00');

// Affichage en français
echo $date->format('d/m/Y H:i:s'); // '15/01/2024 14:30:00'

// Affichage ISO pour API
echo $date->getValue(); // '2024-01-15T14:30:00+01:00'

// Affichage pour la base de données
echo $date->toDateTimeString(); // '2024-01-15 13:30:00' (UTC)
```

### Cas 3 : Calcul de dates

```php
<?php

declare(strict_types=1);

use AndyDefer\PhpVo\ValueObjects\DateTimeVO;

$startDate = DateTimeVO::from('2024-01-15');

// Ajouter 30 jours
$endDate = $startDate->add('30 days');

// Vérifier si la date de fin est dépassée
if ($endDate->isPast()) {
    echo "La période est terminée";
}

// Calculer l'age
$birthDate = DateTimeVO::from('1990-05-15');
$today = DateTimeVO::from(null); // maintenant
if ($birthDate->isBefore($today->add('18 years'))) {
    echo "La personne est majeure";
}
```

## Gestion des erreurs

| Situation | Exception | Message |
|-----------|-----------|---------|
| Format de date invalide | `InvalidArgumentException` | `Invalid datetime value` |
| Intervalle invalide (add/sub) | `InvalidArgumentException` | `Invalid interval: {$interval}` |

## Intégration

| Composant | Relation |
|-----------|----------|
| `AbstractValueObject` | Héritage - Fournit `from()`, `fromJson()`, `equals()` |
| `Transformable` | Implémentation indirecte via AbstractValueObject |
| `Hydratable` | Hérité - Permet l'hydratation automatique |

## Performance

- **Création** : O(1) - tentative de parsing limitée
- **Comparaison** : O(1) - conversion en UTC
- **Arithmétique** : O(1) - opération `modify()` native
- **Formatage** : O(1) - fonction native PHP

## Compatibilité

| Version PHP | Support |
|-------------|---------|
| PHP 8.1+ | ✅ Complet |
| PHP 8.0 | ⚠️ (`DateMalformedStringException` non disponible) |
| PHP 7.x | ❌ Non supporté |

## Exemple complet

```php
<?php

declare(strict_types=1);

use AndyDefer\PhpVo\ValueObjects\DateTimeVO;

// Création depuis différents formats
$fromIso = DateTimeVO::from('2024-01-15T14:30:00+01:00');
$fromDb = DateTimeVO::from('2024-01-15 14:30:00');
$fromDate = DateTimeVO::from('2024-01-15');
$now = DateTimeVO::from(null);

// Formatage
echo $fromIso->getValue();           // '2024-01-15T14:30:00+01:00'
echo $fromIso->toDateTimeString();   // '2024-01-15 13:30:00'
echo $fromIso->toDateString();       // '2024-01-15'
echo $fromIso->toTimeString();       // '14:30:00'
echo $fromIso->format('d/m/Y');      // '15/01/2024'

// Comparaisons
$future = DateTimeVO::from('2100-01-01');
echo $now->isBefore($future);        // true
echo $future->isAfter($now);         // true
echo $now->isPast();                  // false

// Opérations arithmétiques
$tomorrow = $now->add('1 day');
$yesterday = $now->sub('1 day');
$nextWeek = $now->add('1 week');
$in3Hours = $now->add('3 hours');

// Vérification timezone
$paris = DateTimeVO::from('2024-01-15T14:30:00+01:00');
$london = DateTimeVO::from('2024-01-15T13:30:00+00:00');
echo $paris->isEqual($london);       // true (même moment)

// Conversion vers DateTime natif
$datetime = $paris->toDateTime();
$datetimeUtc = $paris->toUtcDateTimeImmutable();

// Utilisation en string
echo $paris; // '2024-01-15T14:30:00+01:00'
```
---