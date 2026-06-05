# EmailVO - Référence Technique

## Description

Value Object immuable représentant une adresse email validée avec extraction des composants (domaine, TLD, nom d'utilisateur).

## Hiérarchie

```
AbstractValueObject (andydefer/domain-structures)
    └── EmailVO
```

## Rôle principal

Encapsuler une adresse email avec validation stricte, normalisation (trim), et méthodes d'extraction des composants : domaine (sans TLD), TLD, domaine complet, et nom d'utilisateur.

## Installation

```bash
composer require andydefer/php-vo
```

## API / Méthodes publiques

### `getValue(): string`

| Paramètre | Type | Description |
|-----------|------|-------------|
| - | - | Aucun paramètre |

**Retourne :** `string` - L'adresse email brute

**Exemple :**
```php
$email = EmailVO::from('user@example.com');
echo $email->getValue(); // 'user@example.com'
```

---

### `getDomain(): string`

| Paramètre | Type | Description |
|-----------|------|-------------|
| - | - | Aucun paramètre |

**Retourne :** `string` - Le nom de domaine sans l'extension TLD

**Exemple :**
```php
$email = EmailVO::from('user@example.com');
echo $email->getDomain(); // 'example'

$email2 = EmailVO::from('user@orange.fr');
echo $email2->getDomain(); // 'orange'
```

---

### `getTld(): string`

| Paramètre | Type | Description |
|-----------|------|-------------|
| - | - | Aucun paramètre |

**Retourne :** `string` - L'extension de domaine (Top Level Domain)

**Exemple :**
```php
$email = EmailVO::from('user@example.com');
echo $email->getTld(); // 'com'

$email2 = EmailVO::from('user@orange.fr');
echo $email2->getTld(); // 'fr'
```

---

### `getFullDomain(): string`

| Paramètre | Type | Description |
|-----------|------|-------------|
| - | - | Aucun paramètre |

**Retourne :** `string` - Le nom de domaine complet avec son extension

**Exemple :**
```php
$email = EmailVO::from('user@example.com');
echo $email->getFullDomain(); // 'example.com'

$email2 = EmailVO::from('user@subdomain.example.co.uk');
echo $email2->getFullDomain(); // 'subdomain.example.co.uk'
```

---

### `getUsername(): string`

| Paramètre | Type | Description |
|-----------|------|-------------|
| - | - | Aucun paramètre |

**Retourne :** `string` - La partie locale de l'email (avant le @)

**Exemple :**
```php
$email = EmailVO::from('user@example.com');
echo $email->getUsername(); // 'user'

$email2 = EmailVO::from('john.doe@example.com');
echo $email2->getUsername(); // 'john.doe'

$email3 = EmailVO::from('john+filter@example.com');
echo $email3->getUsername(); // 'john+filter'
```

---

### `__toString(): string`

| Paramètre | Type | Description |
|-----------|------|-------------|
| - | - | Aucun paramètre |

**Retourne :** `string` - Identique à `getValue()`

**Exemple :**
```php
$email = EmailVO::from('user@example.com');
echo $email; // 'user@example.com'
```

## Cas d'utilisation

### Cas 1 : Validation d'email utilisateur

```php
<?php

declare(strict_types=1);

use AndyDefer\PhpVo\ValueObjects\EmailVO;

try {
    $email = EmailVO::from($_POST['email']);
    
    // Email valide
    $domain = $email->getDomain();
    $username = $email->getUsername();
    
    echo "Bienvenue {$username} !";
    
} catch (InvalidArgumentException $e) {
    // Email invalide
    echo "Adresse email invalide";
}
```

### Cas 2 : Filtrage par domaine

```php
<?php

declare(strict_types=1);

use AndyDefer\PhpVo\ValueObjects\EmailVO;

$emails = [
    'john@gmail.com',
    'jane@yahoo.fr',
    'bob@company.com'
];

$gmailUsers = [];

foreach ($emails as $rawEmail) {
    $email = EmailVO::from($rawEmail);
    
    if ($email->getDomain() === 'gmail') {
        $gmailUsers[] = $email->getUsername();
    }
}

// $gmailUsers = ['john']
```

### Cas 3 : Affichage personnalisé

```php
<?php

declare(strict_types=1);

use AndyDefer\PhpVo\ValueObjects\EmailVO;

$email = EmailVO::from('john.doe@example.com');

// Masquer partiellement l'email
$display = substr($email->getUsername(), 0, 2) . '***@' . $email->getFullDomain();
// 'jo***@example.com'

// Afficher le domaine
echo "Connecté avec le domaine : {$email->getFullDomain()}";
```

### Cas 4 : Détection de fournisseur email

```php
<?php

declare(strict_types=1);

use AndyDefer\PhpVo\ValueObjects\EmailVO;

function getEmailProvider(EmailVO $email): string
{
    $providers = [
        'gmail' => 'Google',
        'outlook' => 'Microsoft',
        'yahoo' => 'Yahoo',
        'protonmail' => 'ProtonMail'
    ];
    
    $domain = $email->getDomain();
    
    return $providers[$domain] ?? 'Autre';
}

$email = EmailVO::from('user@gmail.com');
echo getEmailProvider($email); // 'Google'
```
## Gestion des erreurs

| Situation | Exception | Message |
|-----------|-----------|---------|
| Source non string | `InvalidArgumentException` | `Email must be a string` |
| Format email invalide | `InvalidArgumentException` | `Invalid email format: "..."` |
| Source null | `InvalidArgumentException` | `Email must be a string` |
| Source array | `InvalidArgumentException` | `Email must be a string` |

## Intégration

| Composant | Relation |
|-----------|----------|
| `AbstractValueObject` | Héritage - Fournit `from()`, `fromJson()`, `equals()` |
| `Transformable` | Implémentation indirecte via AbstractValueObject |
| `Hydratable` | Hérité - Permet l'hydratation automatique |

## Performance

- **Création** : O(1) - validation par `filter_var()`
- **Extraction domaine/TLD** : O(n) - manipulation de chaînes (longueur de l'email)
- **Comparaison** : O(1) - via `equals()` hérité

## Compatibilité

| Version PHP | Support |
|-------------|---------|
| PHP 8.1+ | ✅ Complet |
| PHP 8.0 | ✅ Complet |
| PHP 7.x | ❌ Non supporté (propriétés readonly) |

## Exemple complet

```php
<?php

declare(strict_types=1);

use AndyDefer\PhpVo\ValueObjects\EmailVO;

// Création et validation
try {
    $email = EmailVO::from('  john.doe@gmail.com  ');
    
    // Valeur normalisée (trim)
    echo $email->getValue();        // 'john.doe@gmail.com'
    echo $email->getUsername();     // 'john.doe'
    echo $email->getDomain();       // 'gmail'
    echo $email->getTld();          // 'com'
    echo $email->getFullDomain();   // 'gmail.com'
    
    // Avec sous-domaine
    $email2 = EmailVO::from('user@subdomain.example.co.uk');
    echo $email2->getDomain();      // 'subdomain'
    echo $email2->getFullDomain();  // 'subdomain.example.co.uk'
    echo $email2->getTld();         // 'uk'
    
    // Comparaison
    $email3 = EmailVO::from('john.doe@gmail.com');
    var_dump($email->equals($email3)); // true
    
    // String casting
    echo $email; // 'john.doe@gmail.com'
    
} catch (InvalidArgumentException $e) {
    echo "Email invalide : " . $e->getMessage();
}
```
---