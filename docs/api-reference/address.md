# Address - Value Object Documentation

## Table des matières

1. [Description](#1-description)
2. [Installation](#2-installation)
3. [Création d'une adresse](#3-création-dune-adresse)
4. [Méthodes principales](#4-méthodes-principales)
5. [Validation](#5-validation)
6. [Formats supportés](#6-formats-supportés)
7. [Exemples complets](#7-exemples-complets)
8. [Intégration avec Records](#8-intégration-avec-records)
9. [Bonnes pratiques](#9-bonnes-pratiques)
10. [Résumé des méthodes](#10-résumé-des-méthodes)

---

## 1. Description

`Address` est un Value Object qui représente une adresse postale complète. Il est **immutable** et contient les composants standards d'une adresse : rue, ville, code postal et pays.

### Caractéristiques

| Caractéristique | Description |
|-----------------|-------------|
| **Immutabilité** | Toute modification crée une nouvelle instance |
| **Composants** | street, city, postalCode, country |
| **Hydratation** | Supporte array, JSON string, object |
| **Normalisation** | Retourne un `AddressRecord` via `getValue()` |
| **Accès direct** | Propriétés publiques `readonly` |

---

## 2. Installation

```bash
composer require andydefer/php-vo
```

**Prérequis :**
- PHP 8.1 ou supérieur
- Extension JSON activée

---

## 3. Création d'une adresse

### 3.1. Depuis un tableau

```php
use AndyDefer\PhpVo\ValueObjects\Address;

$address = Address::from([
    'street' => '123 Main Street',
    'city' => 'Paris',
    'postal_code' => '75001',
    'country' => 'France'
]);

echo $address->street;      // '123 Main Street'
echo $address->city;        // 'Paris'
echo $address->postalCode;  // '75001'
echo $address->country;     // 'France'
```

### 3.2. Depuis une chaîne JSON

```php
$json = '{
    "street": "456 Oak Avenue",
    "city": "Lyon",
    "postal_code": "69001",
    "country": "France"
}';

$address = Address::from($json);

echo $address->city; // 'Lyon'
```

### 3.3. Depuis un objet

```php
$data = (object)[
    'street' => '789 Pine Road',
    'city' => 'Marseille',
    'postal_code' => '13001',
    'country' => 'France'
];

$address = Address::from($data);
```

### 3.4. Valeurs par défaut

Les champs manquants sont remplacés par une chaîne vide :

```php
$address = Address::from([
    'street' => '123 Main St'
    // city, postal_code, country manquants
]);

echo $address->city;        // ''
echo $address->postalCode;  // ''
echo $address->country;     // ''
```

### 3.5. Format invalide

```php
try {
    $address = Address::from(12345);
} catch (InvalidArgumentException $e) {
    echo $e->getMessage(); // 'Invalid address data'
}
```

---

## 4. Méthodes principales

### 4.1. `getValue(): AddressRecord`

Retourne un `AddressRecord` pour la persistance ou la communication interne :

```php
$address = Address::from([
    'street' => '123 Main St',
    'city' => 'Paris',
    'postal_code' => '75001',
    'country' => 'France'
]);

$record = $address->getValue();
// Instance de AddressRecord
```

### 4.2. Accès direct aux propriétés

Toutes les propriétés sont publiques et `readonly` :

```php
$address = Address::from([...]);

echo $address->street;      // Accès direct
echo $address->city;        // Accès direct
echo $address->postalCode;  // Accès direct
echo $address->country;     // Accès direct
```

### 4.3. `equals(self $other): bool` (hérité)

Compare deux adresses :

```php
$address1 = Address::from([
    'street' => '123 Main St',
    'city' => 'Paris',
    'postal_code' => '75001',
    'country' => 'France'
]);

$address2 = Address::from([
    'street' => '123 Main St',
    'city' => 'Paris',
    'postal_code' => '75001',
    'country' => 'France'
]);

$address1->equals($address2); // true
```

### 4.4. `__toString(): string` (hérité)

Convertit automatiquement en JSON :

```php
$address = Address::from([
    'street' => '123 Main St',
    'city' => 'Paris',
    'postal_code' => '75001',
    'country' => 'France'
]);

echo $address;
// {"street":"123 Main St","city":"Paris","postal_code":"75001","country":"France"}
```

---

## 5. Validation

⚠️ **Note :** `Address` n'effectue pas de validation automatique. Les valeurs sont acceptées telles quelles.

### 5.1. Validation personnalisée

Si vous avez besoin de validation, créez une classe dédiée :

```php
final class ValidatedAddress extends AbstractValueObject
{
    public function __construct(
        public readonly string $street,
        public readonly string $city,
        public readonly string $postalCode,
        public readonly string $country
    ) {
        $this->validate();
    }
    
    private function validate(): void
    {
        if (empty($this->street)) {
            throw new InvalidArgumentException('Street is required');
        }
        
        if (!preg_match('/^[0-9]{5}$/', $this->postalCode)) {
            throw new InvalidArgumentException('Invalid postal code');
        }
        
        // Autres validations...
    }
}
```

---

## 6. Formats supportés

### 6.1. Tableau associatif

```php
$address = Address::from([
    'street' => '123 Main St',
    'city' => 'Paris',
    'postal_code' => '75001',
    'country' => 'France'
]);
```

### 6.2. JSON string

```php
$address = Address::from('{"street":"123 Main St","city":"Paris","postal_code":"75001","country":"France"}');
```

### 6.3. Objet

```php
$obj = new stdClass();
$obj->street = '123 Main St';
$obj->city = 'Paris';
$obj->postal_code = '75001';
$obj->country = 'France';

$address = Address::from($obj);
```

### 6.4. Address existant

```php
$original = Address::from([...]);
$copy = Address::from($original);
// $copy === $original (même instance)
```

---

## 7. Exemples complets

### 7.1. Formatage d'adresse

```php
class AddressFormatter
{
    public static function format(Address $address): string
    {
        return implode("\n", array_filter([
            $address->street,
            "{$address->postalCode} {$address->city}",
            $address->country
        ]));
    }
    
    public static function formatInline(Address $address): string
    {
        return implode(', ', array_filter([
            $address->street,
            "{$address->postalCode} {$address->city}",
            $address->country
        ]));
    }
}

// Utilisation
$address = Address::from([
    'street' => '123 Main St',
    'city' => 'Paris',
    'postal_code' => '75001',
    'country' => 'France'
]);

echo AddressFormatter::format($address);
// 123 Main St
// 75001 Paris
// France

echo AddressFormatter::formatInline($address);
// 123 Main St, 75001 Paris, France
```

### 7.2. Vérification de pays

```php
$address = Address::from([
    'street' => '123 Main St',
    'city' => 'New York',
    'postal_code' => '10001',
    'country' => 'USA'
]);

if ($address->country === 'France') {
    echo 'Livraison en France métropolitaine';
} elseif ($address->country === 'USA') {
    echo 'International shipping applies';
}
```

### 7.3. Normalisation des données

```php
class AddressNormalizer
{
    public static function normalize(Address $address): Address
    {
        return Address::from([
            'street' => ucwords(strtolower($address->street)),
            'city' => ucwords(strtolower($address->city)),
            'postal_code' => strtoupper($address->postalCode),
            'country' => ucfirst(strtolower($address->country))
        ]);
    }
}

$address = Address::from([
    'street' => '123 MAIN STREET',
    'city' => 'PARIS',
    'postal_code' => '75001',
    'country' => 'FRANCE'
]);

$normalized = AddressNormalizer::normalize($address);
echo $normalized->street; // '123 Main Street'
echo $normalized->city;   // 'Paris'
echo $normalized->country; // 'France'
```

### 7.4. Comparaison d'adresses

```php
$home = Address::from([
    'street' => '123 Main St',
    'city' => 'Paris',
    'postal_code' => '75001',
    'country' => 'France'
]);

$work = Address::from([
    'street' => '456 Work Ave',
    'city' => 'Paris',
    'postal_code' => '75002',
    'country' => 'France'
]);

$sameCity = $home->city === $work->city; // true
$sameCountry = $home->country === $work->country; // true
```

---

## 8. Intégration avec Records

### 8.1. Conversion Address → AddressRecord

```php
$address = Address::from([
    'street' => '123 Main St',
    'city' => 'Paris',
    'postal_code' => '75001',
    'country' => 'France'
]);

$record = $address->getValue();
// Instance de AddressRecord
```

### 8.2. Utilisation avec Eloquent (Laravel)

```php
// Dans votre modèle
use AndyDefer\PhpVo\ValueObjects\Address;

class User extends Model
{
    protected $casts = [
        'shipping_address' => 'json'
    ];
    
    protected function shippingAddress(): Attribute
    {
        return Attribute::make(
            get: fn(?array $value): ?Address => $value ? Address::from($value) : null,
            set: fn(?Address $value): ?array => $value?->getValue()->toArray()
        );
    }
}

// Utilisation
$user = User::find(1);
$address = $user->shipping_address;
echo $address->city;
```

### 8.3. Stockage en base de données

```php
// MySQL - colonne JSON
$address = Address::from([...]);
$db->insert('users', [
    'address' => json_encode($address->getValue()->toArray())
]);

// PostgreSQL - colonne JSONB
$db->insert('users', [
    'address' => $address->getValue()->toArray()
]);
```

---

## 9. Bonnes pratiques

### 9.1. Validation externe

```php
// ✅ Bon - validation métier dans le service
class OrderService
{
    public function validateAddress(Address $address): void
    {
        if (empty($address->street)) {
            throw new ValidationException('Street is required');
        }
        
        if (!in_array($address->country, self::ALLOWED_COUNTRIES)) {
            throw new ValidationException('Country not supported');
        }
    }
}

// ❌ Mauvais - pas de validation
$address = Address::from([...]); // Accepte n'importe quoi
```

### 9.2. Normalisation avant stockage

```php
// ✅ Bon - normaliser avant persistance
$address = Address::from($userInput);
$normalized = self::normalizeAddress($address);
$db->save($normalized->getValue());

// ❌ Mauvais - stocker tel quel
$db->save($address->getValue()); // Peut contenir des incohérences
```

### 9.3. Utilisation des valeurs par défaut

```php
// ✅ Bon - valeurs par défaut explicites
$address = Address::from([
    'street' => $data['street'] ?? '',
    'city' => $data['city'] ?? '',
    'postal_code' => $data['postal_code'] ?? '',
    'country' => $data['country'] ?? 'FR'
]);

// ❌ Mauvais - peut causer des erreurs
$address = Address::from($data); // Si des champs manquent → chaînes vides
```

### 9.4. API Response

```php
// ✅ Bon - getValue() retourne AddressRecord
return response()->json([
    'shipping_address' => $address->getValue()->toArray()
]);

// ✅ Alternative - direct via __toString()
return response()->json([
    'shipping_address' => json_decode((string)$address, true)
]);
```

---

## 10. Résumé des méthodes

| Méthode | Retour | Description |
|---------|--------|-------------|
| `from(mixed)` | `static` | Crée une instance depuis array, JSON, object |
| `getValue()` | `AddressRecord` | Retourne un Record pour persistance |
| `equals(self)` | `bool` | Compare deux adresses |
| `__toString()` | `string` | Représentation JSON |
| `toArray()` | `array` | Convertit en tableau (via Record) |

---

## 11. Propriétés

| Propriété | Type | Description |
|-----------|------|-------------|
| `street` | `string` | Numéro et nom de rue |
| `city` | `string` | Ville |
| `postalCode` | `string` | Code postal |
| `country` | `string` | Pays |

---

## 12. Exemples d'utilisation

### 12.1. Formulaire d'adresse

```php
class AddressForm
{
    public function handle(array $data): Address
    {
        return Address::from([
            'street' => trim($data['street']),
            'city' => trim($data['city']),
            'postal_code' => trim($data['postal_code']),
            'country' => trim($data['country'])
        ]);
    }
}
```

### 12.2. Affichage d'une facture

```php
$billingAddress = Address::from([
    'street' => '123 Main St',
    'city' => 'Paris',
    'postal_code' => '75001',
    'country' => 'France'
]);

$html = "
    <div class='address'>
        <p>{$billingAddress->street}</p>
        <p>{$billingAddress->postalCode} {$billingAddress->city}</p>
        <p>{$billingAddress->country}</p>
    </div>
";
```

---

## Support

- **Package** : `andydefer/php-vo`
- **Documentation** : [VALUE_OBJECTS.md](./VALUE_OBJECTS.md)
- **Issues** : [GitHub Issues](https://github.com/andydefer/php-vo/issues)
