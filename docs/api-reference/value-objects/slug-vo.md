# SlugVO - Référence Technique

## Description

Value Object immuable représentant un slug URL-friendly, convertissant automatiquement tout texte non formaté en un slug valide.

## Hiérarchie

```
AbstractValueObject (andydefer/domain-structures)
    └── SlugVO
```

## Rôle principal

Encapsuler un slug avec validation stricte (minuscules, alphanumériques, tirets), normaliser automatiquement les textes non formatés (accents, espaces, caractères spéciaux), et fournir des méthodes de transformation vers différents formats de casse.

## Installation

```bash
composer require andydefer/php-vo
```

## API / Méthodes publiques

### `getValue(): string`

| Paramètre | Type | Description |
|-----------|------|-------------|
| - | - | Aucun paramètre |

**Retourne :** `string` - Le slug brut

**Exemple :**
```php
$slug = SlugVO::from('my-article-title');
echo $slug->getValue(); // 'my-article-title'
```

---

### `toReadableString(): string`

| Paramètre | Type | Description |
|-----------|------|-------------|
| - | - | Aucun paramètre |

**Retourne :** `string` - Le slug converti en texte lisible (mots avec majuscules)

**Exemple :**
```php
$slug = SlugVO::from('my-awesome-article');
echo $slug->toReadableString(); // 'My Awesome Article'
```

---

### `toKebabCase(): string`

| Paramètre | Type | Description |
|-----------|------|-------------|
| - | - | Aucun paramètre |

**Retourne :** `string` - Le slug en kebab-case (identique à la valeur d'origine)

**Exemple :**
```php
$slug = SlugVO::from('my-article-title');
echo $slug->toKebabCase(); // 'my-article-title'
```

---

### `toSnakeCase(): string`

| Paramètre | Type | Description |
|-----------|------|-------------|
| - | - | Aucun paramètre |

**Retourne :** `string` - Le slug converti en snake_case

**Exemple :**
```php
$slug = SlugVO::from('my-article-title');
echo $slug->toSnakeCase(); // 'my_article_title'
```

---

### `toCamelCase(): string`

| Paramètre | Type | Description |
|-----------|------|-------------|
| - | - | Aucun paramètre |

**Retourne :** `string` - Le slug converti en camelCase

**Exemple :**
```php
$slug = SlugVO::from('my-article-title');
echo $slug->toCamelCase(); // 'myArticleTitle'
```

---

### `toPascalCase(): string`

| Paramètre | Type | Description |
|-----------|------|-------------|
| - | - | Aucun paramètre |

**Retourne :** `string` - Le slug converti en PascalCase

**Exemple :**
```php
$slug = SlugVO::from('my-article-title');
echo $slug->toPascalCase(); // 'MyArticleTitle'
```

---

### `isValid(): bool`

| Paramètre | Type | Description |
|-----------|------|-------------|
| - | - | Aucun paramètre |

**Retourne :** `bool` - True si le slug est valide (toujours vrai après construction)

**Exemple :**
```php
$slug = SlugVO::from('valid-slug');
echo $slug->isValid(); // true
```

---

### `__toString(): string`

| Paramètre | Type | Description |
|-----------|------|-------------|
| - | - | Aucun paramètre |

**Retourne :** `string` - Identique à `getValue()`

**Exemple :**
```php
$slug = SlugVO::from('my-article-title');
echo $slug; // 'my-article-title'
```

## Cas d'utilisation

### Cas 1 : Création de slug pour un article

```php
<?php

declare(strict_types=1);

use AndyDefer\PhpVo\ValueObjects\SlugVO;

$title = "Mon article sur les Value Objects";

$slug = SlugVO::from($title);
echo $slug->getValue(); // 'mon-article-sur-les-value-objects'

// Stocker en base de données
$article->slug = $slug->getValue();
```

### Cas 2 : Génération d'URL SEO-friendly

```php
<?php

declare(strict_types=1);

use AndyDefer\PhpVo\ValueObjects\SlugVO;

$category = "Café & Thé";
$product = "Tasse Premium 100€";

$categorySlug = SlugVO::from($category);
$productSlug = SlugVO::from($product);

$url = "/products/" . $categorySlug->getValue() . "/" . $productSlug->getValue();
// '/products/cafe-and-the/tasse-premium-100euro'
```

### Cas 3 : Transformation pour API

```php
<?php

declare(strict_types=1);

use AndyDefer\PhpVo\ValueObjects\SlugVO;

$slug = SlugVO::from('user-profile-settings');

// Pour une API JavaScript (camelCase)
$camelCase = $slug->toCamelCase(); // 'userProfileSettings'

// Pour une base de données (snake_case)
$snakeCase = $slug->toSnakeCase(); // 'user_profile_settings'

// Pour affichage utilisateur
$readable = $slug->toReadableString(); // 'User Profile Settings'
```

### Cas 4 : Normalisation automatique

```php
<?php

declare(strict_types=1);

use AndyDefer\PhpVo\ValueObjects\SlugVO;

$inputs = [
    "Mon Article!",           // → 'mon-article'
    "Café & thé",             // → 'cafe-and-the'
    "Hello @ World",          // → 'hello-at-world'
    "Prix: 100€",             // → 'prix-100euro'
    "2+2=4",                  // → '2plus2equals4'
    "  trimmed  ",            // → 'trimmed'
];

foreach ($inputs as $input) {
    $slug = SlugVO::from($input);
    echo $slug->getValue() . "\n";
}
```

## Flux d'exécution

```
Création via from($text)
    ↓
Conversion en minuscules
    ↓
Suppression des accents (é → e, à → a, etc.)
    ↓
Remplacement des caractères spéciaux (& → and, @ → at, € → euro)
    ↓
Remplacement des caractères non alphanumériques par des tirets
    ↓
Suppression des tirets au début et à la fin
    ↓
Validation du format (regex)
    ↓
Création de l'instance immuable
```

## Gestion des erreurs

| Situation | Exception | Message |
|-----------|-----------|---------|
| Chaîne vide | `InvalidArgumentException` | `Unable to create valid slug from: ""` |
| Caractères non latins (cyrillique, etc.) | `InvalidArgumentException` | `Unable to create valid slug from: "..."` |
| Après normalisation, chaîne invalide | `InvalidArgumentException` | `Unable to create valid slug from: "..."` |
| Source null | `InvalidArgumentException` | `Cannot convert value to string for parameter $value` |

## Normalisation des caractères spéciaux

| Caractère | Transformation |
|-----------|----------------|
| Accents (é, è, ê, ë) | → e |
| ç | → c |
| ñ | → n |
| æ | → ae |
| œ | → oe |
| ß | → ss |
| & | → and |
| @ | → at |
| € | → euro |
| + | → plus |
| = | → equals |
| Espaces, ponctuation, autres spéciaux | → - (tiret) |

## Intégration

| Composant | Relation |
|-----------|----------|
| `AbstractValueObject` | Héritage - Fournit `from()`, `fromJson()`, `equals()` |
| `Transformable` | Implémentation indirecte via AbstractValueObject |
| `Hydratable` | Hérité - Permet l'hydratation automatique |

## Performance

- **Création** : O(n) - traitement de la chaîne (normalisation, regex)
- **Transformations** : O(n) - manipulation de chaînes (explode, implode, str_replace)
- **Comparaison** : O(1) - via `equals()` hérité

## Compatibilité

| Version PHP | Support |
|-------------|---------|
| PHP 8.1+ | ✅ Complet (propriétés readonly) |
| PHP 8.0 | ✅ Complet |
| PHP 7.x | ❌ Non supporté |

## Exemple complet

```php
<?php

declare(strict_types=1);

use AndyDefer\PhpVo\ValueObjects\SlugVO;

// Création depuis texte non formaté
$slug = SlugVO::from("Mon Article d'import");
echo $slug->getValue(); // 'mon-article-d-import'

// Création depuis slug déjà valide
$existing = SlugVO::from('my-article-title');
echo $existing->getValue(); // 'my-article-title'

// Transformations
echo $slug->toReadableString(); // 'Mon Article D Import'
echo $slug->toSnakeCase();      // 'mon_article_d_import'
echo $slug->toCamelCase();      // 'monArticleDImport'
echo $slug->toPascalCase();     // 'MonArticleDImport'

// Normalisation automatique
$inputs = [
    "Café & thé"           // 'cafe-and-the'
    "Hello @ World!"       // 'hello-at-world'
    "Prix: 100€"           // 'prix-100euro'
    "2+2=4"                // '2plus2equals4'
    "  trimmed slug  "     // 'trimmed-slug'
];

// Comparaison
$slug1 = SlugVO::from('my-article');
$slug2 = SlugVO::from('My Article');
var_dump($slug1->equals($slug2)); // true

// Validation
$slug = SlugVO::from('valid-slug');
echo $slug->isValid(); // true

// String casting
echo $slug; // 'valid-slug'

// Gestion des erreurs
try {
    $invalid = SlugVO::from('');
} catch (InvalidArgumentException $e) {
    echo $e->getMessage(); // 'Unable to create valid slug from: ""'
}
```

## Notes additionnelles

- La normalisation est automatique : inutile de prétraiter le texte
- Les accents sont convertis en caractères ASCII simples
- Les caractères spéciaux courants sont remplacés par des mots (`&` → `and`)
- Les tirets multiples sont réduits à un seul tiret
- Les tirets en début et fin de chaîne sont supprimés
- La chaîne vide après normalisation lance une exception