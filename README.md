# FreeDesktop.org os-release file reader for PHP

[![Latest Version](https://img.shields.io/github/release/compwright/php-os-release.svg?style=flat-square)](https://github.com/compwright/php-os-release/releases)
[![Total Downloads](https://img.shields.io/packagist/dt/compwright/php-os-release.svg?style=flat-square)](https://packagist.org/packages/compwright/php-os-release)

For more information about the os-release standard, see https://www.freedesktop.org/software/systemd/man/os-release.html

This library will attempt to read and parse the two standard os-release information files, in order of precedence:

1. /etc/os-release
2. /usr/lib/os-release

If no file exists, or if the file cannot be read, an OsReleaseException will be thrown.

## Installation

To install, use composer:

```
composer require compwright/php-os-release
```

## Usage

```php
use CompWright\PhpOsRelease\OsReleaseReader;

$reader = new OsReleaseReader();
$osRelease = $reader();

// Access via property or array access
$version = $osRelease->version;

// or:
$version = $osRelease['VERSION'];
```

## Testing

``` bash
$ make test
```

## Contributing

Please see [CONTRIBUTING](https://github.com/compwright/php-os-release/blob/master/CONTRIBUTING.md) for details.

## License

The MIT License (MIT). Please see [License File](https://github.com/compwright/php-os-release/blob/master/LICENSE) for more information.
