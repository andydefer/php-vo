# StringVO - Référence Technique

## Description

`StringVO` est un Value Object représentant une chaîne de caractères. Il encapsule une `string` PHP native et fournit un large éventail d'opérations de manipulation, de recherche, de transformation et de conversion dans un contexte immuable et typé.

## Hiérarchie

```
AbstractValueObject
    └── StringVO
```

**Interfaces implémentées :** `Transformable` (via `AbstractValueObject`)

## Rôle principal

`StringVO` encapsule une chaîne de caractères dans un objet immutable. Il fournit des opérations de :
- **Manipulation** : `trim()`, `substring()`, `replace()`, `concat()`
- **Cas** : `toUpper()`, `toLower()`, `toSnakeCase()`, `toCamelCase()`
- **Recherche** : `contains()`, `startsWith()`, `endsWith()`, `position()`
- **Split** : `split()`, `chars()`, `words()`
- **Comparaison** : `equal()`, `equalsIgnoreCase()`, `compare()`
- **Conversion** : `toInt()`, `toFloat()`, `toBool()`, `toArray()`

Toutes les opérations sont immutables : chaque méthode retourne une nouvelle instance.

---

## Installation

```bash
composer require andy-defer/php-vo
```

```php
use AndyDefer\PhpVo\ValueObjects\Types\StringVO;
```

---

## API / Méthodes publiques

### `__construct(string $value = '')`

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$value` | `string` | Chaîne initiale (vide par défaut) |

**Retourne :** `void`

**Exemple :**
```php
$string = new StringVO('Hello World');
```

---

### `getValue(): string`

Retourne la chaîne brute.

**Retourne :** `string` - La chaîne encapsulée

**Exemple :**
```php
$string = StringVO::from('Hello');
$value = $string->getValue(); // 'Hello'
```

---

### `length(): int`

Retourne la longueur de la chaîne.

**Retourne :** `int` - Nombre de caractères

**Exemple :**
```php
$string = StringVO::from('Hello');
$length = $string->length(); // 5
```

---

### `isEmpty(): BoolVO`

Vérifie si la chaîne est vide.

**Retourne :** `BoolVO` - `true` si vide, `false` sinon

**Exemple :**
```php
$string = StringVO::from('');
$isEmpty = $string->isEmpty(); // BoolVO(true)
```

---

### `isNotEmpty(): BoolVO`

Vérifie si la chaîne n'est pas vide.

**Retourne :** `BoolVO` - `true` si non vide, `false` sinon

**Exemple :**
```php
$string = StringVO::from('Hello');
$isNotEmpty = $string->isNotEmpty(); // BoolVO(true)
```

---

### `trim(): self`

Supprime les espaces en début et fin de chaîne.

**Retourne :** `self` - Nouvelle instance avec la chaîne trimée

**Exemple :**
```php
$string = StringVO::from('  Hello  ');
$trimmed = $string->trim(); // 'Hello'
```

---

### `ltrim(): self`

Supprime les espaces en début de chaîne.

**Retourne :** `self` - Nouvelle instance avec la chaîne trimée à gauche

**Exemple :**
```php
$string = StringVO::from('  Hello  ');
$trimmed = $string->ltrim(); // 'Hello  '
```

---

### `rtrim(): self`

Supprime les espaces en fin de chaîne.

**Retourne :** `self` - Nouvelle instance avec la chaîne trimée à droite

**Exemple :**
```php
$string = StringVO::from('  Hello  ');
$trimmed = $string->rtrim(); // '  Hello'
```

---

### `concat(mixed $other): self`

Concatène une autre valeur à la fin de la chaîne.

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$other` | `mixed` | Valeur à concaténer (convertie via `::from()`) |

**Retourne :** `self` - Nouvelle instance avec la chaîne concaténée

**Exceptions :** `InvalidArgumentException` - Si la conversion échoue

**Exemple :**
```php
$string = StringVO::from('Hello');
$result = $string->concat(' World'); // 'Hello World'
$result = $string->concat(StringVO::from(' World')); // 'Hello World'
```

---

### `repeat(mixed $times): self`

Répète la chaîne un certain nombre de fois.

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$times` | `mixed` | Nombre de répétitions (converti via `FloatVO`) |

**Retourne :** `self` - Nouvelle instance avec la chaîne répétée

**Exceptions :** `InvalidArgumentException` - Si la conversion échoue

**Exemple :**
```php
$string = StringVO::from('Ha');
$result = $string->repeat(3); // 'HaHaHa'
```

---

### `substring(mixed $start, mixed $length = null): self`

Extrait une sous-chaîne.

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$start` | `mixed` | Position de début (converti via `FloatVO`) |
| `$length` | `mixed` | Longueur (converti via `FloatVO`) |

**Retourne :** `self` - Nouvelle instance avec la sous-chaîne

**Exceptions :** `InvalidArgumentException` - Si la conversion échoue

**Exemple :**
```php
$string = StringVO::from('Hello World');
$result = $string->substring(0, 5); // 'Hello'
$result = $string->substring(6); // 'World'
```

---

### `replace(mixed $search, mixed $replace): self`

Remplace toutes les occurrences d'une chaîne par une autre.

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$search` | `mixed` | Chaîne à rechercher (convertie via `::from()`) |
| `$replace` | `mixed` | Chaîne de remplacement (convertie via `::from()`) |

**Retourne :** `self` - Nouvelle instance avec les remplacements effectués

**Exceptions :** `InvalidArgumentException` - Si la conversion échoue

**Exemple :**
```php
$string = StringVO::from('Hello World');
$result = $string->replace('World', 'PHP'); // 'Hello PHP'
```

---

### `replaceAll(MapCollection $replacements): self`

Remplace plusieurs paires clé → valeur.

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$replacements` | `MapCollection` | Paires de remplacement `[search => replace]` |

**Retourne :** `self` - Nouvelle instance avec les remplacements effectués

**Exemple :**
```php
$string = StringVO::from('Hello World');
$result = $string->replaceAll(MapCollection::from([
    'Hello' => 'Hi',
    'World' => 'PHP'
])); // 'Hi PHP'
```

---

### `stripTags(): self`

Supprime les balises HTML et PHP.

**Retourne :** `self` - Nouvelle instance sans balises

**Exemple :**
```php
$string = StringVO::from('<p>Hello <b>World</b></p>');
$result = $string->stripTags(); // 'Hello World'
```

---

### `stripTagsWithAllowed(string $allowedTags): self`

Supprime les balises HTML et PHP, mais conserve celles autorisées.

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$allowedTags` | `string` | Balises autorisées (ex: `<p><b>`) |

**Retourne :** `self` - Nouvelle instance avec les balises autorisées conservées

**Exemple :**
```php
$string = StringVO::from('<p>Hello <b>World</b></p>');
$result = $string->stripTagsWithAllowed('<b>'); // 'Hello <b>World</b>'
```

---

### `toUpper(): self`

Convertit en majuscules.

**Retourne :** `self` - Nouvelle instance en majuscules

**Exemple :**
```php
$string = StringVO::from('hello');
$result = $string->toUpper(); // 'HELLO'
```

---

### `toLower(): self`

Convertit en minuscules.

**Retourne :** `self` - Nouvelle instance en minuscules

**Exemple :**
```php
$string = StringVO::from('HELLO');
$result = $string->toLower(); // 'hello'
```

---

### `toUpperFirst(): self`

Met la première lettre en majuscule.

**Retourne :** `self` - Nouvelle instance avec la première lettre en majuscule

**Exemple :**
```php
$string = StringVO::from('hello');
$result = $string->toUpperFirst(); // 'Hello'
```

---

### `toLowerFirst(): self`

Met la première lettre en minuscule.

**Retourne :** `self` - Nouvelle instance avec la première lettre en minuscule

**Exemple :**
```php
$string = StringVO::from('Hello');
$result = $string->toLowerFirst(); // 'hello'
```

---

### `toUpperWords(): self`

Met en majuscule la première lettre de chaque mot.

**Retourne :** `self` - Nouvelle instance avec les mots en majuscule

**Exemple :**
```php
$string = StringVO::from('hello world');
$result = $string->toUpperWords(); // 'Hello World'
```

---

### `toSnakeCase(): self`

Convertit en snake_case (ex: `HelloWorld` → `hello_world`).

**Retourne :** `self` - Nouvelle instance en snake_case

**Exemple :**
```php
$string = StringVO::from('HelloWorld');
$result = $string->toSnakeCase(); // 'hello_world'
```

---

### `toCamelCase(): self`

Convertit en camelCase (ex: `hello_world` → `helloWorld`).

**Retourne :** `self` - Nouvelle instance en camelCase

**Exemple :**
```php
$string = StringVO::from('hello_world');
$result = $string->toCamelCase(); // 'helloWorld'
```

---

### `contains(mixed $needle): BoolVO`

Vérifie si la chaîne contient une sous-chaîne.

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$needle` | `mixed` | Sous-chaîne à rechercher (convertie via `::from()`) |

**Retourne :** `BoolVO` - `true` si trouvé, `false` sinon

**Exceptions :** `InvalidArgumentException` - Si la conversion échoue

**Exemple :**
```php
$string = StringVO::from('Hello World');
$result = $string->contains('World'); // BoolVO(true)
$result = $string->contains('PHP'); // BoolVO(false)
```

---

### `startsWith(mixed $needle): BoolVO`

Vérifie si la chaîne commence par une sous-chaîne.

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$needle` | `mixed` | Sous-chaîne à rechercher (convertie via `::from()`) |

**Retourne :** `BoolVO` - `true` si trouvé, `false` sinon

**Exceptions :** `InvalidArgumentException` - Si la conversion échoue

**Exemple :**
```php
$string = StringVO::from('Hello World');
$result = $string->startsWith('Hello'); // BoolVO(true)
$result = $string->startsWith('World'); // BoolVO(false)
```

---

### `endsWith(mixed $needle): BoolVO`

Vérifie si la chaîne se termine par une sous-chaîne.

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$needle` | `mixed` | Sous-chaîne à rechercher (convertie via `::from()`) |

**Retourne :** `BoolVO` - `true` si trouvé, `false` sinon

**Exceptions :** `InvalidArgumentException` - Si la conversion échoue

**Exemple :**
```php
$string = StringVO::from('Hello World');
$result = $string->endsWith('World'); // BoolVO(true)
$result = $string->endsWith('Hello'); // BoolVO(false)
```

---

### `position(mixed $needle): int`

Retourne la position de la première occurrence d'une sous-chaîne.

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$needle` | `mixed` | Sous-chaîne à rechercher (convertie via `::from()`) |

**Retourne :** `int` - Position de la première occurrence, -1 si non trouvé

**Exceptions :** `InvalidArgumentException` - Si la conversion échoue

**Exemple :**
```php
$string = StringVO::from('Hello World');
$pos = $string->position('World'); // 6
$pos = $string->position('PHP'); // -1
```

---

### `lastPosition(mixed $needle): int`

Retourne la position de la dernière occurrence d'une sous-chaîne.

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$needle` | `mixed` | Sous-chaîne à rechercher (convertie via `::from()`) |

**Retourne :** `int` - Position de la dernière occurrence, -1 si non trouvé

**Exceptions :** `InvalidArgumentException` - Si la conversion échoue

**Exemple :**
```php
$string = StringVO::from('Hello World World');
$pos = $string->lastPosition('World'); // 12
```

---

### `countOccurrences(mixed $needle): int`

Compte le nombre d'occurrences d'une sous-chaîne.

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$needle` | `mixed` | Sous-chaîne à compter (convertie via `::from()`) |

**Retourne :** `int` - Nombre d'occurrences

**Exceptions :** `InvalidArgumentException` - Si la conversion échoue

**Exemple :**
```php
$string = StringVO::from('Hello World World');
$count = $string->countOccurrences('World'); // 2
```

---

### `split(mixed $separator): ListCollection`

Divise la chaîne en un tableau de sous-chaînes.

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$separator` | `mixed` | Séparateur (converti via `::from()`) |

**Retourne :** `ListCollection` - Collection de `StringVO`

**Exceptions :** `InvalidArgumentException` - Si la conversion échoue

**Exemple :**
```php
$string = StringVO::from('Hello,World,PHP');
$result = $string->split(','); 
// ListCollection(['Hello', 'World', 'PHP'])
```

---

### `splitByLength(mixed $length): ListCollection`

Divise la chaîne en fragments de longueur fixe.

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$length` | `mixed` | Longueur des fragments (converti via `FloatVO`) |

**Retourne :** `ListCollection` - Collection de `StringVO`

**Exceptions :** `InvalidArgumentException` - Si la conversion échoue

**Exemple :**
```php
$string = StringVO::from('HelloWorld');
$result = $string->splitByLength(5); 
// ListCollection(['Hello', 'World'])
```

---

### `chars(): ListCollection`

Divise la chaîne en caractères individuels.

**Retourne :** `ListCollection` - Collection de `StringVO` (chaque caractère)

**Exemple :**
```php
$string = StringVO::from('Hello');
$result = $string->chars(); 
// ListCollection(['H', 'e', 'l', 'l', 'o'])
```

---

### `words(): ListCollection`

Divise la chaîne en mots.

**Retourne :** `ListCollection` - Collection de `StringVO` (chaque mot)

**Exemple :**
```php
$string = StringVO::from('Hello World PHP');
$result = $string->words(); 
// ListCollection(['Hello', 'World', 'PHP'])
```

---

### `equal(mixed $other): BoolVO`

Compare l'égalité stricte avec une autre valeur.

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$other` | `mixed` | Valeur à comparer (convertie via `::from()`) |

**Retourne :** `BoolVO` - `true` si égales, `false` sinon

**Exceptions :** `InvalidArgumentException` - Si la conversion échoue

**Exemple :**
```php
$string1 = StringVO::from('Hello');
$string2 = StringVO::from('Hello');
$result = $string1->equal($string2); // BoolVO(true)
```

---

### `equalsIgnoreCase(mixed $other): BoolVO`

Compare l'égalité en ignorant la casse.

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$other` | `mixed` | Valeur à comparer (convertie via `::from()`) |

**Retourne :** `BoolVO` - `true` si égales (sans tenir compte de la casse), `false` sinon

**Exceptions :** `InvalidArgumentException` - Si la conversion échoue

**Exemple :**
```php
$string1 = StringVO::from('Hello');
$string2 = StringVO::from('hello');
$result = $string1->equalsIgnoreCase($string2); // BoolVO(true)
```

---

### `compare(mixed $other): int`

Compare lexicographiquement deux chaînes.

| Paramètre | Type | Description |
|-----------|------|-------------|
| `$other` | `mixed` | Valeur à comparer (convertie via `::from()`) |

**Retourne :** `int` - < 0 si inférieure, 0 si égale, > 0 si supérieure

**Exceptions :** `InvalidArgumentException` - Si la conversion échoue

**Exemple :**
```php
$string = StringVO::from('Hello');
$result = $string->compare('World'); // < 0
$result = $string->compare('Hello'); // 0
$result = $string->compare('Apple'); // > 0
```

---

### `toInt(): int`

Convertit en entier.

**Retourne :** `int` - Valeur entière

**Exceptions :** `InvalidArgumentException` - Si la chaîne n'est pas numérique

**Exemple :**
```php
$string = StringVO::from('123');
$int = $string->toInt(); // 123
```

---

### `toFloat(): FloatVO`

Convertit en `FloatVO`.

**Retourne :** `FloatVO` - Valeur flottante

**Exceptions :** `InvalidArgumentException` - Si la chaîne n'est pas numérique

**Exemple :**
```php
$string = StringVO::from('3.14');
$float = $string->toFloat(); // FloatVO(3.14)
```

---

### `toBool(): BoolVO`

Convertit en `BoolVO` (utilise `filter_var` avec `FILTER_VALIDATE_BOOLEAN`).

**Retourne :** `BoolVO` - Valeur booléenne

**Exemple :**
```php
$string = StringVO::from('true');
$bool = $string->toBool(); // BoolVO(true)
$string = StringVO::from('false');
$bool = $string->toBool(); // BoolVO(false)
```

---

### `toArray(): ListCollection`

Divise la chaîne par virgule.

**Retourne :** `ListCollection` - Collection de `StringVO`

**Exemple :**
```php
$string = StringVO::from('Hello,World,PHP');
$result = $string->toArray(); 
// ListCollection(['Hello', 'World', 'PHP'])
```

---

### `__toString(): string`

Représentation textuelle de la chaîne.

**Retourne :** `string` - La chaîne brute

**Exemple :**
```php
$string = StringVO::from('Hello');
echo $string; // 'Hello'
```

---

## Cas d'utilisation

### Cas 1 : Validation et nettoyage de données

```php
use AndyDefer\PhpVo\ValueObjects\Types\StringVO;

class UserInput
{
    public function sanitize(string $input): StringVO
    {
        return StringVO::from($input)
            ->trim()
            ->stripTags()
            ->replace('  ', ' ')
            ->toLower();
    }
    
    public function isValidEmail(string $email): BoolVO
    {
        $emailVO = StringVO::from($email);
        return $emailVO->contains('@')
            ->and($emailVO->contains('.'));
    }
}
```

### Cas 2 : Formatage de données

```php
class DataFormatter
{
    public function formatName(string $firstName, string $lastName): StringVO
    {
        return StringVO::from($firstName)
            ->toUpperFirst()
            ->concat(' ')
            ->concat($lastName->toUpper());
    }
    
    public function slugify(string $text): StringVO
    {
        return StringVO::from($text)
            ->toLower()
            ->replace(' ', '-')
            ->replace(['é', 'è', 'ê'], 'e')
            ->replace(['à', 'â'], 'a');
    }
}
```

### Cas 3 : Analyse de texte

```php
class TextAnalyzer
{
    public function analyze(string $text): array
    {
        $textVO = StringVO::from($text);
        
        return [
            'length' => $textVO->length(),
            'words' => $textVO->words(),
            'chars' => $textVO->chars(),
            'word_count' => $textVO->words()->count(),
            'contains_php' => $textVO->contains('PHP')->getValue(),
        ];
    }
}
```

### Cas 4 : Conversion de cas

```php
class CaseConverter
{
    public function toDisplayName(string $value): StringVO
    {
        return StringVO::from($value)
            ->toSnakeCase()
            ->replace('_', ' ')
            ->toUpperWords();
    }
    
    public function toVariableName(string $value): StringVO
    {
        return StringVO::from($value)
            ->toCamelCase()
            ->toLowerFirst();
    }
}
```

---

## Flux d'exécution

```
Création (new StringVO() ou ::from())
    ↓
Opération (trim, toUpper, replace, concat, etc.)
    ↓
Nouvelle instance (immutable)
    ↓
Opération suivante (chaînage)
    ↓
Nouvelle instance (immutable)
    ↓
Conversion (toInt, toFloat, toBool, toArray)
    ↓
Résultat
```

**Immutable :** Chaque opération retourne une **nouvelle instance**.

```php
$original = StringVO::from('Hello');
$result = $original->toUpper()->concat(' World');

$original->getValue(); // 'Hello' - inchangé
$result->getValue();   // 'HELLO World' - nouvelle instance
```

---

## Gestion des erreurs

| Situation | Exception | Message |
|-----------|-----------|---------|
| Conversion int avec non numérique | `InvalidArgumentException` | `Cannot convert 'X' to integer` |
| Conversion float avec non numérique | `InvalidArgumentException` | `Cannot convert 'X' to float` |
| Source invalide via `::from()` | `InvalidArgumentException` | `Cannot convert value to string for parameter $value` |
| Remplacements invalides dans `replaceAll()` | `InvalidArgumentException` | Messages de MapCollection |

---

## Performance

| Opération | Complexité | Notes |
|-----------|------------|-------|
| `getValue()` | O(1) | Retour direct |
| `length()` | O(1) | Fonction native |
| `trim()`, `ltrim()`, `rtrim()` | O(n) | Fonction native |
| `concat()` | O(n) | Concaténation |
| `substring()` | O(n) | Fonction native |
| `replace()` | O(n) | Fonction native |
| `toUpper()`, `toLower()` | O(n) | Fonction native |
| `contains()`, `startsWith()`, `endsWith()` | O(n) | Fonction native |
| `split()`, `chars()`, `words()` | O(n) | Fonction native |

---

## Compatibilité

| Version PHP | Support | Notes |
|-------------|---------|-------|
| PHP 8.1+ | ✅ Complet | Types union, mixed, etc. |
| PHP 8.0 | ✅ Complet | Supporté |
| PHP 7.4 | ❌ Non | Nécessite PHP 8.0+ |

---

## Exemple complet

```php
<?php

declare(strict_types=1);

use AndyDefer\PhpVo\ValueObjects\Types\StringVO;
use AndyDefer\DomainStructures\Utils\MapCollection;

// Création
$text = StringVO::from('  Hello World  ');

// Chaînage d'opérations
$result = $text
    ->trim()                // 'Hello World'
    ->toUpper()             // 'HELLO WORLD'
    ->replace('WORLD', 'PHP') // 'HELLO PHP'
    ->toLower()             // 'hello php'
    ->toUpperFirst();       // 'Hello php'

// Vérifications
$contains = $result->contains('php'); // BoolVO(true)
$starts = $result->startsWith('Hello'); // BoolVO(true)
$length = $result->length(); // 10

// Recherche
$position = $result->position('php'); // 6

// Split
$words = $result->words(); // ListCollection(['Hello', 'php'])
$chars = $result->chars(); // ListCollection(['H', 'e', 'l', 'l', 'o', ' ', 'p', 'h', 'p'])

// Remplacements multiples
$formatted = $result->replaceAll(MapCollection::from([
    'Hello' => 'Bonjour',
    'php' => 'PHP'
])); // 'Bonjour PHP'

// Comparaison
$isEqual = $result->equal('Hello php'); // BoolVO(true)
$compare = $result->compare('Hello'); // > 0

// Conversion
$int = StringVO::from('42')->toInt(); // 42
$float = StringVO::from('3.14')->toFloat(); // FloatVO(3.14)
$bool = StringVO::from('true')->toBool(); // BoolVO(true)

// Cas
$snake = StringVO::from('HelloWorld')->toSnakeCase(); // 'hello_world'
$camel = StringVO::from('hello_world')->toCamelCase(); // 'helloWorld'

echo $result; // 'Hello php'
```

---

## Voir aussi

- `FloatVO` - Value Object pour les nombres flottants
- `BoolVO` - Value Object pour les booléens
- `ListCollection` - Collection pour les résultats de méthodes
- `MapCollection` - Collection pour les remplacements
- `StyledTextVO` - Value Object pour le texte stylisé
- `AbstractValueObject` - Classe de base pour les Value Objects