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

Please see shorten.php