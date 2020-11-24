# Laravel Castable Data Transfer Object

[![Latest Version on Packagist](https://img.shields.io/packagist/v/tailflow/castable-dto.svg)](https://packagist.org/packages/tailflow/castable-dto)
[![Build Status on Travis CI](https://img.shields.io/github/workflow/status/tailflow/castable-dto/default)](https://github.com/tailflow/castable-dto/actions)

Have you ever wanted to cast your JSON columns to a value object?

This package gives you an extended version of Spatie's `DataTransferObject` class, called `CastableDataTransferObject`.

Under the hood it implements Laravel's [`Castable`](https://laravel.com/docs/8.x/eloquent-mutators#castables) interface with a Laravel [custom cast](https://laravel.com/docs/7.x/eloquent-mutators#custom-casts) that handles serializing between the `DataTransferObject` (or a compatible array) and your JSON database column.

## Installation

You can install the package via composer:

```bash
composer require tailflow/castable-dto
```

## Usage

### 1. Create your `CastableDataTransferObject`

Check out the readme for Spatie's [data transfer object](https://github.com/spatie/data-transfer-object) package to find out more about what their `DataTransferObject` class can do.

``` php
namespace App\DataTansferObjects;

use Tailflow\DataTransferObjects\CastableDataTransferObject;

class Address extends CastableDataTransferObject
{
    public string $country;
    public string $city;
    public string $street;
}
```

### 2. Configure your Eloquent attribute to cast to it:

Note that this should be a `jsonb` or `json` column in your database schema.

```php
namespace App\Models;

use App\DataTansferObjects\Address;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $casts = [
        'address' => Address::class,
    ];
}
```

And that's it! You can now pass either an instance of your `Address` class, or even just an array with a compatible structure. It will automatically be cast between your class and JSON for storage and the data will be validated on the way in and out.

```php
$user = User::create([
    // ...
    'address' => [
        'country' => 'Japan',
        'city' => 'Tokyo',
        'street' => '4-2-8 Shiba-koen',
    ],
]);

$residents = User::where('address->city', 'Tokyo')->get();
```

But the best part is that you can decorate your class with domain-specific methods to turn it into a powerful value object.

```php
$user->address->toMapUrl();

$user->address->getCoordinates();

$user->address->getPostageCost($sender);

$user->address->calculateDistance($otherUser->address);

echo (string) $user->address;
```

## Credits

- Original [package](https://github.com/jessarcher/laravel-castable-data-transfer-object) by [Jess Archer](https://github.com/jessarcher)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.