# PHP URL Shortener

This is a customisable URL shortener for PHP. You can install it to the root of your domain and you can feed it URLs and it'll shorten them based on your settings.

## Features

* SQLite Database for storage with PDO connections 
* You can define whitelists and blacklists to limit URLs that are allowed to be shortened
* Configurable URL prefix

## Requirements

* PHP 5
* Apache (for .htaccess, other servers you'll have to write the equivalent)
* An SQLite file to connect to (may be created automatically given file access)

## Usage

Please see shorten.php for detailed usage.

### Options

Here are the options you can set:

```php
    'urlprefix' => 'zz', //URL prefix as defined in .htaccess, ie the url will be like http://mysite/zzba etc
    'blacklist' => array(), //array of strings containing regex patterns
    'whitelist' => array('.*'), //allow all urls by default
    'db' => 'sqlite:urls.db', //custom database connection
    'dbuser' => null, //db username: used if you were to connect to something other than sqlite
    'dbpw' => null, //db password: as above
```