# Types Common

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]
[![Software License][ico-license]](LICENSE)

Value Objects are small and **immutable** classes representing typed values usually implemented using PHP primitive types. However, objects can embed validation to ensure that your data is **always valid** without adding any check elsewhere in your code. That's why you should ALWAYS use Value Objects rather than primitive types!

This package provides most common types you can use in any project.

## Installation
This package requires **PHP 7.4+**

Add it as Composer dependency:
```sh
$ composer require mediagone/types-common
```


## Available value-objects

All value-objects implement a common `ValueObject` interface and `JsonSerializable`. 

### Business
- `Bic`
- `Iban`

### Crypto
- `Hash` (abstract class)
- `HashBcrypt`
- `HashArgon2id`
- `RandomToken`
- `Sha512`

_Note: all Hash* types are based on `Hash` base class, so they are perfectly interoperable._

### Geo
- `Address`
- `City`
- `Country`
- `Latitude`
- `Longitude`

### Graphics
- `Color`

### System
- `Age`
- `Base64`
- `Binary`
- `Count`
- `Date`
- `DateTimeUTC`
- `DayOfMonth`
- `Duration`
- `Hex`
- `Quantity`

### Text
- `Name`
- `NameDigit`
- `NameSpecial`
- `Slug`
- `SlugSnake`
- `Text`
- `TextMedium`
- `Title`

### Web
- `EmailAddress`
- `Url`
- `UrlHost`
- `UrlPath`


## License

_Types Common_ is licensed under MIT license. See LICENSE file.



[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg
[ico-version]: https://img.shields.io/packagist/v/mediagone/types-common.svg
[ico-downloads]: https://img.shields.io/packagist/dt/mediagone/types-common.svg

[link-packagist]: https://packagist.org/packages/mediagone/types-common
[link-downloads]: https://packagist.org/packages/mediagone/types-common
