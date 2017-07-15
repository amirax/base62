# Base62 encoder and decoder

## Install
The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run:
```
composer require amirax/base62
```

## Usage
This package use encoder based on pure PHP. Library can encode strings, integers or bytes.

```php
use Amirax\Base62;

$base62 = new Base62();
echo $encodedData = $base62->encode('Hello World!');    // T8dgcjRGkZ3aysdN
echo $base62->decode($encodedData);                     // Hello World!
```

Also you can use a salt:
```php
use Amirax\Base62;

echo (new Base62())->encode('Hello World!');                    // T8dgcjRGkZ3aysdN
echo (new Base62('my_secret_salt'))->encode('Hello World!');    // e4NKCYHiEbv8qjNx
```

Or you can set custom alphabet:
```php
use Amirax\Base62;

// Custom alphabet without salt. Output: 2678lx5gvmsv1dro9b5
echo (new Base62('', '0123456789abcdefghijklmnopqrstuvwxyz'))->encode('Hello World!');

// ... and with salt. Output: v79ljqkhx3bxnafi2mk
echo (new Base62('my_secret_salt', '0123456789abcdefghijklmnopqrstuvwxyz'))->encode('Hello World!');

```

## Testing
You can run tests either run:

```
composer test
```

## License
The MIT License (MIT). Please see [License File](LICENSE.md) for more information.