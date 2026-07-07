# DateTimeVO - Référence Technique

## Description

Value Object immutable représentant une date/heure au format ISO 8601, avec support complet des fuseaux horaires, des conversions et des opérations arithmétiques.

## Hiérarchie / Implémentations

```
AbstractValueObject
    └── DateTimeVO
```

**Interfaces :** Aucune interface explicite, hérite de `AbstractValueObject`.

## Rôle principal

`DateTimeVO` est un **Value Object immuable** qui encapsule une date/heure avec son fuseau horaire. Il sert de **wrapper type-safe** autour de Carbon/CarbonInterface, offrant :

- Une API unifiée pour la manipulation des dates
- L'immutabilité garantie (toute opération retourne une nouvelle instance)
- Le support des tests avec `Carbon::setTestNow()`
- La validation stricte des formats d'entrée

## Installation

```bash
composer require andydefer/php-vo
```

## API / Méthodes publiques

### `__construct(?string $value = null)`

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$value` | `string\|null` | Chaîne ISO 8601, ou null pour la date/heure courante |

**Exceptions :** `InvalidArgumentException` si la chaîne est invalide.

**Exemple :**
```php
$date = new DateTimeVO('2024-01-15T14:30:00+01:00');
$now = new DateTimeVO(null); // Date/heure courante
```

---

### `from(string|self|null $value): self`

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$value` | `string\|self\|null` | Valeur à convertir en DateTimeVO |

**Retourne :** `self` - Nouvelle instance

**Exceptions :** `InvalidArgumentException` si la chaîne est invalide

**Exemple :**
```php
$date = DateTimeVO::from('2024-01-15T14:30:00+01:00');
$copy = DateTimeVO::from($date); // Copie
$now = DateTimeVO::from(null); // Date/heure courante
```

---

### `fromCarbon(CarbonInterface $carbon): self`

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$carbon` | `CarbonInterface` | Instance Carbon à convertir |

**Retourne :** `self` - Nouvelle instance

**Exemple :**
```php
$carbon = Carbon::now();
$date = DateTimeVO::fromCarbon($carbon);
```

---

### `now(): self`

**Retourne :** `self` - Nouvelle instance avec la date/heure courante

**Note :** Respecte `Carbon::setTestNow()` pour les tests.

**Exemple :**
```php
$now = DateTimeVO::now();
```

---

### `today(): self`

**Retourne :** `self` - Nouvelle instance avec la date d'aujourd'hui à 00:00:00

**Exemple :**
```php
$today = DateTimeVO::today();
// 2024-01-15T00:00:00+00:00
```

---

### `create(int $year, int $month, int $day, int $hour = 0, int $minute = 0, int $second = 0, ?string $timezone = null): self`

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$year` | `int` | Année (ex: 2024) |
| `$month` | `int` | Mois (1-12) |
| `$day` | `int` | Jour (1-31) |
| `$hour` | `int` | Heure (0-23, défaut: 0) |
| `$minute` | `int` | Minute (0-59, défaut: 0) |
| `$second` | `int` | Seconde (0-59, défaut: 0) |
| `$timezone` | `string\|null` | Fuseau horaire (ex: 'Europe/Paris'), null pour UTC |

**Retourne :** `self` - Nouvelle instance

**Exemple :**
```php
$date = DateTimeVO::create(2024, 1, 15, 14, 30, 0, 'Europe/Paris');
// 2024-01-15T14:30:00+01:00
```

---

### `getValue(): string`

**Retourne :** `string` - La date/heure au format ISO 8601

**Exemple :**
```php
$date = DateTimeVO::from('2024-01-15T14:30:00+01:00');
echo $date->getValue(); // '2024-01-15T14:30:00+01:00'
```

---

### `getCarbon(): CarbonInterface`

**Retourne :** `CarbonInterface` - L'instance Carbon interne

**Note :** Méthode pour les cas avancés où un accès direct à Carbon est nécessaire.

**Exemple :**
```php
$date = DateTimeVO::now();
$carbon = $date->getCarbon();
```

---

### `toDateTime(): DateTime`

**Retourne :** `DateTime` - Instance native DateTime

**Exemple :**
```php
$date = DateTimeVO::from('2024-01-15T14:30:00+01:00');
$dateTime = $date->toDateTime();
```

---

### `toDateTimeImmutable(): DateTimeImmutable`

**Retourne :** `DateTimeImmutable` - Instance native DateTimeImmutable

**Exemple :**
```php
$date = DateTimeVO::from('2024-01-15T14:30:00+01:00');
$dateTimeImmutable = $date->toDateTimeImmutable();
```

---

### `toUtcDateTimeImmutable(): DateTimeImmutable`

**Retourne :** `DateTimeImmutable` - Instance DateTimeImmutable en UTC

**Exemple :**
```php
$date = DateTimeVO::from('2024-01-15T14:30:00+01:00');
$utc = $date->toUtcDateTimeImmutable();
// 2024-01-15 13:30:00 UTC
```

---

### `toDateTimeString(): string`

**Retourne :** `string` - Format base de données `Y-m-d H:i:s` en UTC

**Exemple :**
```php
$date = DateTimeVO::from('2024-01-15T14:30:00+01:00');
echo $date->toDateTimeString(); // '2024-01-15 13:30:00'
```

---

### `toDateString(): string`

**Retourne :** `string` - Date uniquement `Y-m-d`

**Exemple :**
```php
$date = DateTimeVO::from('2024-01-15T14:30:00+01:00');
echo $date->toDateString(); // '2024-01-15'
```

---

### `toTimeString(): string`

**Retourne :** `string` - Heure uniquement `H:i:s`

**Exemple :**
```php
$date = DateTimeVO::from('2024-01-15T14:30:00+01:00');
echo $date->toTimeString(); // '14:30:00'
```

---

### `toTimestamp(): int`

**Retourne :** `int` - Timestamp Unix

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

**Retourne :** `string` - Date formatée

**Exemple :**
```php
$date = DateTimeVO::from('2024-01-15T14:30:00+00:00');
echo $date->format('d/m/Y'); // '15/01/2024'
```

---

### `isAfter(self $other): bool`

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$other` | `self` | Date à comparer |

**Retourne :** `bool` - True si la date est après `$other`

**Exemple :**
```php
$date1 = DateTimeVO::from('2024-01-15T14:30:00+00:00');
$date2 = DateTimeVO::from('2024-01-14T14:30:00+00:00');
$date1->isAfter($date2); // true
```

---

### `isBefore(self $other): bool`

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$other` | `self` | Date à comparer |

**Retourne :** `bool` - True si la date est avant `$other`

**Exemple :**
```php
$date1 = DateTimeVO::from('2024-01-14T14:30:00+00:00');
$date2 = DateTimeVO::from('2024-01-15T14:30:00+00:00');
$date1->isBefore($date2); // true
```

---

### `isEqual(self $other): bool`

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$other` | `self` | Date à comparer |

**Retourne :** `bool` - True si les dates sont égales (même moment, timezone ignoré)

**Exemple :**
```php
$paris = DateTimeVO::from('2024-01-15T14:30:00+01:00');
$london = DateTimeVO::from('2024-01-15T13:30:00+00:00');
$paris->isEqual($london); // true
```

---

### `isPast(): bool`

**Retourne :** `bool` - True si la date est dans le passé

**Exemple :**
```php
$date = DateTimeVO::from('2024-01-01T00:00:00+00:00');
$date->isPast(); // true
```

---

### `isFuture(): bool`

**Retourne :** `bool` - True si la date est dans le futur

**Exemple :**
```php
$date = DateTimeVO::from('2024-02-01T00:00:00+00:00');
$date->isFuture(); // true
```

---

### `isToday(): bool`

**Retourne :** `bool` - True si la date est aujourd'hui

**Exemple :**
```php
$date = DateTimeVO::from('2024-01-15T00:00:00+00:00');
$date->isToday(); // true
```

---

### `isTomorrow(): bool`

**Retourne :** `bool` - True si la date est demain

**Exemple :**
```php
$date = DateTimeVO::from('2024-01-16T00:00:00+00:00');
$date->isTomorrow(); // true
```

---

### `isYesterday(): bool`

**Retourne :** `bool` - True si la date est hier

**Exemple :**
```php
$date = DateTimeVO::from('2024-01-14T00:00:00+00:00');
$date->isYesterday(); // true
```

---

### `add(string $interval): self`

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$interval` | `string` | Intervalle (ex: '1 day', '3 hours', '1 week') |

**Retourne :** `self` - Nouvelle instance avec l'intervalle ajouté

**Exceptions :** `InvalidArgumentException` si l'intervalle est invalide

**Exemple :**
```php
$date = DateTimeVO::from('2024-01-15T00:00:00+00:00');
$result = $date->add('1 week');
// 2024-01-22T00:00:00+00:00
```

---

### `sub(string $interval): self`

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$interval` | `string` | Intervalle (ex: '1 day', '3 hours', '1 week') |

**Retourne :** `self` - Nouvelle instance avec l'intervalle soustrait

**Exceptions :** `InvalidArgumentException` si l'intervalle est invalide

**Exemple :**
```php
$date = DateTimeVO::from('2024-01-22T00:00:00+00:00');
$result = $date->sub('1 week');
// 2024-01-15T00:00:00+00:00
```

---

### `addDays(int $days): self`

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$days` | `int` | Nombre de jours à ajouter |

**Retourne :** `self` - Nouvelle instance

**Exemple :**
```php
$date = DateTimeVO::from('2024-01-15T00:00:00+00:00');
$result = $date->addDays(1);
// 2024-01-16T00:00:00+00:00
```

---

### `subDays(int $days): self`

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$days` | `int` | Nombre de jours à soustraire |

**Retourne :** `self` - Nouvelle instance

**Exemple :**
```php
$date = DateTimeVO::from('2024-01-15T00:00:00+00:00');
$result = $date->subDays(1);
// 2024-01-14T00:00:00+00:00
```

---

### `addHours(int $hours): self`

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$hours` | `int` | Nombre d'heures à ajouter |

**Retourne :** `self` - Nouvelle instance

---

### `subHours(int $hours): self`

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$hours` | `int` | Nombre d'heures à soustraire |

**Retourne :** `self` - Nouvelle instance

---

### `addMinutes(int $minutes): self`

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$minutes` | `int` | Nombre de minutes à ajouter |

**Retourne :** `self` - Nouvelle instance

---

### `subMinutes(int $minutes): self`

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$minutes` | `int` | Nombre de minutes à soustraire |

**Retourne :** `self` - Nouvelle instance

---

### `addMonths(int $months): self`

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$months` | `int` | Nombre de mois à ajouter |

**Retourne :** `self` - Nouvelle instance

---

### `subMonths(int $months): self`

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$months` | `int` | Nombre de mois à soustraire |

**Retourne :** `self` - Nouvelle instance

---

### `addYears(int $years): self`

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$years` | `int` | Nombre d'années à ajouter |

**Retourne :** `self` - Nouvelle instance

---

### `subYears(int $years): self`

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$years` | `int` | Nombre d'années à soustraire |

**Retourne :** `self` - Nouvelle instance

---

### `diffInSeconds(self $other): float`

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$other` | `self` | Date à comparer |

**Retourne :** `float` - Différence en secondes (toujours positive)

**Exemple :**
```php
$date1 = DateTimeVO::from('2024-01-15T00:00:00+00:00');
$date2 = DateTimeVO::from('2024-01-15T00:01:30+00:00');
$date1->diffInSeconds($date2); // 90.0
```

---

### `diffInMinutes(self $other): float`

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$other` | `self` | Date à comparer |

**Retourne :** `float` - Différence en minutes (toujours positive)

---

### `diffInHours(self $other): float`

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$other` | `self` | Date à comparer |

**Retourne :** `float` - Différence en heures (toujours positive)

---

### `diffInDays(self $other): float`

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$other` | `self` | Date à comparer |

**Retourne :** `float` - Différence en jours (toujours positive)

---

### `diffInMonths(self $other): float`

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$other` | `self` | Date à comparer |

**Retourne :** `float` - Différence en mois (toujours positive)

---

### `diffInYears(self $other): float`

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$other` | `self` | Date à comparer |

**Retourne :** `float` - Différence en années (toujours positive)

---

### `toUtc(): self`

**Retourne :** `self` - Nouvelle instance convertie en UTC

**Exemple :**
```php
$date = DateTimeVO::from('2024-01-15T14:30:00+01:00');
$utc = $date->toUtc();
// 2024-01-15T13:30:00+00:00
```

---

### `toTimezone(string $timezone): self`

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$timezone` | `string` | Fuseau horaire (ex: 'Europe/Paris', 'America/New_York') |

**Retourne :** `self` - Nouvelle instance convertie

**Exemple :**
```php
$date = DateTimeVO::from('2024-01-15T14:30:00+00:00');
$paris = $date->toTimezone('Europe/Paris');
// 2024-01-15T15:30:00+01:00
```

---

### `getTimezone(): DateTimeZone`

**Retourne :** `DateTimeZone` - Le fuseau horaire de la date

**Exemple :**
```php
$date = DateTimeVO::from('2024-01-15T14:30:00+01:00');
$timezone = $date->getTimezone(); // DateTimeZone('+01:00')
```

---

### `hasTimezone(): bool`

**Retourne :** `bool` - True si un fuseau horaire est défini

**Exemple :**
```php
$date = DateTimeVO::from('2024-01-15T14:30:00+01:00');
$date->hasTimezone(); // true
```

---

### `getYear(): int`

**Retourne :** `int` - L'année (ex: 2024)

---

### `getMonth(): int`

**Retourne :** `int` - Le mois (1-12)

---

### `getDay(): int`

**Retourne :** `int` - Le jour (1-31)

---

### `getHour(): int`

**Retourne :** `int` - L'heure (0-23)

---

### `getMinute(): int`

**Retourne :** `int` - La minute (0-59)

---

### `getSecond(): int`

**Retourne :** `int` - La seconde (0-59)

---

### `getDayOfWeek(): int`

**Retourne :** `int` - Jour de semaine ISO-8601 (1 = Lundi, 7 = Dimanche)

---

### `getWeekOfYear(): int`

**Retourne :** `int` - Numéro de semaine ISO-8601

---

### `startOfDay(): self`

**Retourne :** `self` - Nouvelle instance avec l'heure à 00:00:00

**Exemple :**
```php
$date = DateTimeVO::from('2024-01-15T14:30:00+00:00');
$result = $date->startOfDay();
// 2024-01-15T00:00:00+00:00
```

---

### `endOfDay(): self`

**Retourne :** `self` - Nouvelle instance avec l'heure à 23:59:59

**Exemple :**
```php
$date = DateTimeVO::from('2024-01-15T14:30:00+00:00');
$result = $date->endOfDay();
// 2024-01-15T23:59:59+00:00
```

---

### `startOfMonth(): self`

**Retourne :** `self` - Nouvelle instance au premier jour du mois à 00:00:00

**Exemple :**
```php
$date = DateTimeVO::from('2024-01-15T14:30:00+00:00');
$result = $date->startOfMonth();
// 2024-01-01T00:00:00+00:00
```

---

### `endOfMonth(): self`

**Retourne :** `self` - Nouvelle instance au dernier jour du mois à 23:59:59

**Exemple :**
```php
$date = DateTimeVO::from('2024-01-15T14:30:00+00:00');
$result = $date->endOfMonth();
// 2024-01-31T23:59:59+00:00
```

---

### `startOfYear(): self`

**Retourne :** `self` - Nouvelle instance au premier jour de l'année à 00:00:00

**Exemple :**
```php
$date = DateTimeVO::from('2024-01-15T14:30:00+00:00');
$result = $date->startOfYear();
// 2024-01-01T00:00:00+00:00
```

---

### `endOfYear(): self`

**Retourne :** `self` - Nouvelle instance au dernier jour de l'année à 23:59:59

**Exemple :**
```php
$date = DateTimeVO::from('2024-01-15T14:30:00+00:00');
$result = $date->endOfYear();
// 2024-12-31T23:59:59+00:00
```

---

### `__toString(): string`

**Retourne :** `string` - La date au format ISO 8601

**Exemple :**
```php
$date = DateTimeVO::from('2024-01-15T14:30:00+01:00');
echo $date; // '2024-01-15T14:30:00+01:00'
```

---

## Cas d'utilisation

### Cas 1 : Validation de date d'expiration

**Problème :** Vérifier qu'un token n'est pas expiré.

```php
$expiresAt = DateTimeVO::from('2024-02-01T00:00:00+00:00');
$now = DateTimeVO::now();

if ($expiresAt->isPast()) {
    throw new ExpiredTokenException('Token has expired');
}
```

---

### Cas 2 : Calcul de délai entre deux événements

**Problème :** Calculer le temps écoulé depuis une action.

```php
$lastLogin = DateTimeVO::from($user->last_login_at);
$now = DateTimeVO::now();

$hoursSinceLastLogin = $lastLogin->diffInHours($now);

if ($hoursSinceLastLogin > 24) {
    // Nécessite une nouvelle connexion
}
```

---

### Cas 3 : Conversion de fuseau horaire pour affichage

**Problème :** Stocker en UTC, afficher dans le fuseau de l'utilisateur.

```php
// Stocké en base en UTC
$createdAt = DateTimeVO::from($order->created_at);

// Affichage dans le fuseau de l'utilisateur
$userTimezone = 'Europe/Paris';
$displayDate = $createdAt->toTimezone($userTimezone);

echo $displayDate->format('d/m/Y H:i');
// 15/01/2024 15:30
```

---

### Cas 4 : Génération de plages horaires

**Problème :** Créer une plage de dates pour un rapport.

```php
$start = DateTimeVO::from('2024-01-01T00:00:00+00:00');
$end = DateTimeVO::from('2024-01-07T00:00:00+00:00');

$current = $start;
while ($current->isBefore($end)) {
    echo $current->toDateString() . PHP_EOL;
    $current = $current->addDays(1);
}
// 2024-01-01
// 2024-01-02
// ...
// 2024-01-06
```

---

## Flux d'exécution

```
Création → parseDateTime()
    ├── null → Carbon::now()
    ├── ISO 8601 → parseIso8601()
    ├── Zulu → parseZulu()
    ├── Database → parseDatabase()
    ├── Date only → parseDateOnly()
    └── Generic → parseGeneric()

Opérations → copy() → modification → new instance()

Comparaison → Carbon::gt()/lt()/eq()

Formatage → Carbon::format()
```

---

## Gestion des erreurs

| Situation | Exception | Message |
|-----------|-----------|---------|
| Chaîne invalide | `InvalidArgumentException` | `Invalid datetime value: {value}` |
| Intervalle invalide (add) | `InvalidArgumentException` | `Invalid interval: {interval}` |
| Intervalle invalide (sub) | `InvalidArgumentException` | `Invalid interval: {interval}` |

---

## Intégration

### Avec Carbon

`DateTimeVO` s'appuie sur Carbon pour toutes les opérations. Vous pouvez passer d'un objet à l'autre :

```php
// Carbon → DateTimeVO
$date = DateTimeVO::fromCarbon($carbon);

// DateTimeVO → Carbon
$carbon = $date->getCarbon();
```

### Avec les bases de données

Stockez en base au format UTC :

```php
// Sauvegarde
$model->created_at = $date->toDateTimeString(); // '2024-01-15 13:30:00'

// Lecture
$date = DateTimeVO::from($model->created_at);
```

### Avec les APIs

Utilisez le format ISO 8601 pour l'échange :

```php
// Envoi
$response->date = $date->getValue(); // '2024-01-15T14:30:00+01:00'

// Réception
$date = DateTimeVO::from($request->date);
```

---

## Performance

| Opération | Complexité | Notes |
|-----------|------------|-------|
| Parsing | O(1) | Utilise `Carbon::createFromFormat()` |
| Arithmétique | O(1) | `copy()` puis modification |
| Comparaison | O(1) | `gt()`/`lt()`/`eq()` |
| Formatage | O(1) | `format()` |

**Optimisations :**
- Pas de cache interne (les objets sont immutables)
- Pas de boucles ou d'allocations lourdes
- Les opérations de base sont en O(1)

---

## Compatibilité

| Version PHP | Support |
|-------------|---------|
| PHP 8.1+ | ✅ Complet |
| PHP 8.0 | ✅ Complet |

**Dépendances :**
- `nesbot/carbon: ^2.0`

---

## Exemple complet

```php
<?php

declare(strict_types=1);

use AndyDefer\PhpVo\ValueObjects\DateTimeVO;
use AndyDefer\Records\AbstractRecord;

final class UserSession extends AbstractRecord
{
    public readonly DateTimeVO $createdAt;
    public readonly DateTimeVO $expiresAt;

    public function __construct(
        public readonly int $userId,
        public readonly string $token,
        ?DateTimeVO $createdAt = null,
        ?DateTimeVO $expiresAt = null,
    ) {
        $this->createdAt = $createdAt ?? DateTimeVO::now();
        $this->expiresAt = $expiresAt ?? $this->createdAt->addHours(24);
    }

    public function isExpired(): bool
    {
        return $this->expiresAt->isPast();
    }

    public function getRemainingTime(): float
    {
        if ($this->isExpired()) {
            return 0.0;
        }

        return DateTimeVO::now()->diffInSeconds($this->expiresAt);
    }

    public function extend(int $hours = 24): self
    {
        return new self(
            userId: $this->userId,
            token: $this->token,
            createdAt: $this->createdAt,
            expiresAt: $this->expiresAt->addHours($hours),
        );
    }
}

// Utilisation
$session = new UserSession(
    userId: 123,
    token: 'abc123xyz',
);

// Vérification
if ($session->isExpired()) {
    // Rafraîchir
    $session = $session->extend(24);
}

// Affichage
echo 'Créé le : ' . $session->createdAt->format('d/m/Y à H:i') . PHP_EOL;
echo 'Expire le : ' . $session->expiresAt->format('d/m/Y à H:i') . PHP_EOL;
echo 'Temps restant : ' . $session->getRemainingTime() . ' secondes' . PHP_EOL;

// Stockage en base
$model = new SessionModel([
    'created_at' => $session->createdAt->toDateTimeString(),
    'expires_at' => $session->expiresAt->toDateTimeString(),
]);
```