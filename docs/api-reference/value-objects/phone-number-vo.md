# PhoneNumberVO - Référence Technique

## Description

Value Object immuable représentant un numéro de téléphone international validé selon la norme E.164 (ITU-T), avec indicatif pays et numéro d'abonné.

## Hiérarchie

```
AbstractValueObject (andydefer/domain-structures)
    └── PhoneNumberVO
```

## Rôle principal

Encapsuler un numéro de téléphone international avec validation stricte (chiffres uniquement, longueur totale ≤ 15 digits), fournir des méthodes de formatage standard (E.164 et format humain adaptable), et permettre l'extraction des composants (indicatif, numéro).

## Installation

```bash
composer require andydefer/php-vo
```

**Prérequis :** Extension PHP `bcmath` (optionnelle, pour les opérations précises)

## API / Méthodes publiques

### `getValue(): PhoneNumberRecord`

| Paramètre | Type | Description |
|-----------|------|-------------|
| - | - | Aucun paramètre |

**Retourne :** `PhoneNumberRecord` - Le numéro converti en Record (structure de données)

**Exemple :**
```php
$phone = PhoneNumberVO::from(['callingCode' => 'FR', 'number' => '612345678']);
$record = $phone->getValue();
// $record->callingCode = CallingCode::FR
// $record->number = '612345678'
```

---

### `getCallingCode(): CallingCode`

| Paramètre | Type | Description |
|-----------|------|-------------|
| - | - | Aucun paramètre |

**Retourne :** `CallingCode` - L'indicatif téléphonique sous forme d'enum

**Exemple :**
```php
$phone = PhoneNumberVO::from(['callingCode' => 'FR', 'number' => '612345678']);
echo $phone->getCallingCode()->value; // '33'
```

---

### `getNumber(): string`

| Paramètre | Type | Description |
|-----------|------|-------------|
| - | - | Aucun paramètre |

**Retourne :** `string` - Le numéro d'abonné (chiffres uniquement)

**Exemple :**
```php
$phone = PhoneNumberVO::from(['callingCode' => 'FR', 'number' => '612345678']);
echo $phone->getNumber(); // '612345678'
```

---

### `toE164(): string`

| Paramètre | Type | Description |
|-----------|------|-------------|
| - | - | Aucun paramètre |

**Retourne :** `string` - Le numéro au format E.164 (compact, avec +)

**Exemple :**
```php
$phone = PhoneNumberVO::from(['callingCode' => 'FR', 'number' => '612345678']);
echo $phone->toE164(); // '+33612345678'
```

---

### `formatHuman(?int $groupSize = null): string`

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$groupSize` | `int|null` | Nombre de chiffres par groupe (2, 3, ou null pour auto-détection) |

**Retourne :** `string` - Le numéro formaté lisiblement

**Exceptions :** `InvalidArgumentException` - Si `$groupSize` n'est pas 2, 3, ou null

**Règles d'auto-détection :**
- Indicatif à 2 chiffres (FR, BE, DE) → groupes de 2 : `+33 6 12 34 56 78`
- Indicatif à 3 chiffres (GF, RE, CD) → groupes de 3 : `+243 812 345 678`
- Indicatif à 1 chiffre (US, CA) → groupes de 2 : `+1 20 25 55 01 23`

**Exemple :**
```php
$phone = PhoneNumberVO::from(['callingCode' => 'FR', 'number' => '612345678']);
echo $phone->formatHuman();    // '+33 6 12 34 56 78' (auto)
echo $phone->formatHuman(3);   // '+33 612 345 678' (forcé groupes de 3)
```

---

### `getTotalDigits(): int`

| Paramètre | Type | Description |
|-----------|------|-------------|
| - | - | Aucun paramètre |

**Retourne :** `int` - Nombre total de chiffres (indicatif + numéro)

**Exemple :**
```php
$phone = PhoneNumberVO::from(['callingCode' => 'FR', 'number' => '612345678']);
echo $phone->getTotalDigits(); // 11 (2 + 9)
```

---

### `__toString(): string`

| Paramètre | Type | Description |
|-----------|------|-------------|
| - | - | Aucun paramètre |

**Retourne :** `string` - Identique à `formatHuman()` (auto-détection)

**Exemple :**
```php
$phone = PhoneNumberVO::from(['callingCode' => 'FR', 'number' => '612345678']);
echo $phone; // '+33 6 12 34 56 78'
```

## Cas d'utilisation

### Cas 1 : Validation et stockage en base de données

```php
<?php

declare(strict_types=1);

use AndyDefer\PhpVo\ValueObjects\PhoneNumberVO;

try {
    $phone = PhoneNumberVO::from([
        'callingCode' => 'FR',
        'number' => $_POST['phone_number']
    ]);
    
    // Stocker au format E.164 (compact)
    $e164 = $phone->toE164(); // '+33612345678'
    
    // Ou stocker le Record directement
    $record = $phone->getValue();
    $db->save('users', [
        'calling_code' => $record->callingCode->value,
        'number' => $record->number
    ]);
    
} catch (InvalidArgumentException $e) {
    echo "Numéro invalide : " . $e->getMessage();
}
```

### Cas 2 : Affichage formaté selon le pays

```php
<?php

declare(strict_types=1);

use AndyDefer\PhpVo\ValueObjects\PhoneNumberVO;

$numbers = [
    ['callingCode' => 'FR', 'number' => '612345678'],   // France
    ['callingCode' => 'CD', 'number' => '812345678'],   // Congo
    ['callingCode' => 'US', 'number' => '2025550123']    // USA
];

foreach ($numbers as $data) {
    $phone = PhoneNumberVO::from($data);
    
    // Auto-détection du format selon l'indicatif
    echo $phone->formatHuman() . "\n";
    // +33 6 12 34 56 78
    // +243 812 345 678
    // +1 20 25 55 01 23
}
```

### Cas 3 : Validation de longueur (limite E.164)

```php
<?php

declare(strict_types=1);

use AndyDefer\PhpVo\ValueObjects\PhoneNumberVO;

$phone = PhoneNumberVO::from([
    'callingCode' => 'DE',  // Allemagne: 2 digits
    'number' => '3012345678901' // 13 digits (total 15, max autorisé)
]);

echo $phone->getTotalDigits(); // 15
echo $phone->formatHuman();    // '+49 30 12 34 56 78 90 1'

// Ce numéro est invalide (trop long)
try {
    $invalid = PhoneNumberVO::from([
        'callingCode' => 'FR',
        'number' => '61234567890123' // 14 digits + 2 = 16 > 15
    ]);
} catch (InvalidArgumentException $e) {
    echo $e->getMessage(); // 'Total phone number length (16 digits) exceeds maximum allowed (15 digits)'
}
```
## Gestion des erreurs

| Situation | Exception | Message |
|-----------|-----------|---------|
| Numéro contient des lettres | `InvalidArgumentException` | `Phone number must contain only digits, got: ...` |
| Numéro contient des caractères spéciaux | `InvalidArgumentException` | `Phone number must contain only digits, got: ...` |
| Numéro vide | `InvalidArgumentException` | `Phone number cannot be empty` |
| Longueur totale > 15 digits | `InvalidArgumentException` | `Total phone number length (%d digits) exceeds maximum allowed (15 digits)` |
| `$groupSize` invalide | `InvalidArgumentException` | `Group size must be 2, 3, or null (auto), got: %d` |

## Intégration

| Composant | Relation |
|-----------|----------|
| `AbstractValueObject` | Héritage - Fournit `from()`, `fromJson()`, `equals()` |
| `CallingCode` | Composition - Enum des indicatifs téléphoniques (ISO 3166-1 alpha-2) |
| `PhoneNumberRecord` | Conversion - `getValue()` retourne un Record pour stockage |
| `Transformable` | Implémentation indirecte via AbstractValueObject |

## Performance

- **Création** : O(n) - validation regex sur la longueur du numéro
- **Formatage** : O(n) - `str_split()` et `implode()`
- **Comparaison** : O(1) - via `equals()` hérité

## Compatibilité

| Version PHP | Support |
|-------------|---------|
| PHP 8.1+ | ✅ Complet (enums, propriétés readonly) |
| PHP 8.0 | ⚠️ Partiel (enums non supportés) |
| PHP 7.x | ❌ Non supporté |

## Exemple complet

```php
<?php

declare(strict_types=1);

use AndyDefer\PhpVo\ValueObjects\PhoneNumberVO;

// Création depuis un tableau associatif
$phone = PhoneNumberVO::from([
    'callingCode' => 'FR',
    'number' => '612345678'
]);

// Récupération des composants
echo $phone->getCallingCode()->value; // '33'
echo $phone->getNumber();             // '612345678'

// Formatage E.164 (compact)
echo $phone->toE164();                // '+33612345678'

// Formatage humain (auto-détection)
echo $phone->formatHuman();           // '+33 6 12 34 56 78'

// Formatage humain forcé (groupes de 3)
echo $phone->formatHuman(3);          // '+33 612 345 678'

// Longueur totale
echo $phone->getTotalDigits();        // 11

// Comparaison
$samePhone = PhoneNumberVO::from(['callingCode' => 'FR', 'number' => '612345678']);
var_dump($phone->equals($samePhone)); // true

// String casting
echo $phone; // '+33 6 12 34 56 78'

// Conversion en Record pour stockage
$record = $phone->getValue();
echo $record->callingCode->value;     // '33'
echo $record->number;                 // '612345678'

// Gestion des erreurs
try {
    $invalid = PhoneNumberVO::from([
        'callingCode' => 'FR',
        'number' => '6123ABCD78'      // Lettres interdites
    ]);
} catch (InvalidArgumentException $e) {
    echo $e->getMessage(); // 'Phone number must contain only digits, got: 6123ABCD78'
}

// Cas Congo (indicatif 3 digits)
$congoPhone = PhoneNumberVO::from([
    'callingCode' => 'CD',
    'number' => '812345678'
]);
echo $congoPhone->formatHuman();      // '+243 812 345 678' (auto: groupes de 3)

// Cas USA (indicatif 1 digit)
$usPhone = PhoneNumberVO::from([
    'callingCode' => 'US',
    'number' => '2025550123'
]);
echo $usPhone->formatHuman();         // '+1 20 25 55 01 23' (auto: groupes de 2)
```

## Notes additionnelles

- La norme **E.164** limite les numéros de téléphone à 15 digits maximum (indicatif inclus)
- Les espaces, tirets et autres séparateurs ne sont pas stockés, uniquement les chiffres
- L'indicatif est géré via l'enum `CallingCode` qui associe le code ISO 3166-1 alpha-2 à l'indicatif numérique
- Le formatage humain s'adapte automatiquement à la longueur de l'indicatif (2 → groupes de 2, 3 → groupes de 3)
- La méthode `formatHuman()` permet de forcer le nombre de chiffres par groupe (2 ou 3) si nécessaire