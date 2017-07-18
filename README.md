reddish
===

[![TravisCI](https://travis-ci.org/KazuakiM/reddish.svg?branch=master)](https://travis-ci.org/KazuakiM/reddish)
[![Coveralls](https://img.shields.io/coveralls/KazuakiM/reddish.svg?style=flat-square)](https://coveralls.io/github/KazuakiM/reddish?branch=master)
[![Scrutinizer](https://img.shields.io/scrutinizer/g/KazuakiM/reddish.svg?style=flat-square)](https://scrutinizer-ci.com/g/KazuakiM/reddish/)
[![GitHub issues](https://img.shields.io/github/issues/KazuakiM/reddish.svg?style=flat-square)](https://github.com/KazuakiM/reddish/issues)
[![Packagist](https://img.shields.io/packagist/dt/kazuakim/reddish.svg?style=flat-square)](https://packagist.org/packages/kazuakim/reddish)
[![Document](https://img.shields.io/badge/document-gh--pages-brightgreen.svg?style=flat-square)](https://kazuakim.github.io/reddish/)
[![license](https://img.shields.io/github/license/KazuakiM/reddish.svg?style=flat-square)](https://raw.githubusercontent.com/KazuakiM/reddish/master/LICENSE)

## Usage

This Redis client is very simple, only connection supprot!
```php
try {
    $clients = new \Kazuakim\Reddish\Clients([
        'host' => '127.0.0.1',
        'port' => 6379
    ]);

    $clients->set('key', 1); // normal phpredis functions.
    $clients->get('key');
} catch (\RedisException $e) {
    ...
}
```

## Author

[KazuakiM](https://github.com/KazuakiM/)

## License

This software is released under the MIT License, see LICENSE.
