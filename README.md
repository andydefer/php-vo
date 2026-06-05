# PHP Value Objects (PHP-VO)

Une collection de Value Objects et Enums réutilisables pour applications PHP 8.1+.

## Installation

```bash
composer require andydefer/php-vo
```

## Prérequis

- PHP 8.1 ou supérieur
- `andydefer/domain-structures` ^1.3

## Documentation

- [Value Objects](docs/api-reference/VALUE_OBJECTS.md)
- [Enums](docs/api-reference/ENUMS.md)

## Exemple rapide

```php
use AndyDefer\PhpVo\ValueObjects\MoneyVO;
use AndyDefer\PhpVo\ValueObjects\AmountVO;
use AndyDefer\PhpVo\Enums\Currency;

$money = new MoneyVO(AmountVO::from('99.99'), Currency::EUR);
echo $money->format(); // '99.99 €'
```


## License

MIT © [Andy Defer](https://github.com/andydefer)
