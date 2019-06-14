Date Formatter
==============

A simple service to format a date in constant format(s) across a PHP project / API.

# Table of contents

- [How to install](#how-to-install)
- [How to use](#how-to-use)
  - [The parser](#the-parser)
  - [The formatter](#the-formatter)
  - [The Service Provider](#the-service-provider)

# How to install

PHP 7 is required. Install with composer:

```shell
composer require mducharme/date-formatter
``` 

# How to use

Ready-to-use, with a Pimple container:

```php
$container = new \Pimple\Container();
$container->register(new \Mducharme\DateFormatter\ServiceProvider());


$formatter = $container['date/formatter'];
$displayDate = $formatter->format($date);
```

With the services directly:

```php
$formatter = new \Mducharme\DateFormatter\Formatter(
    new \Mducharme\DateFormatter\Parser()
);
$displayDate = $formatter->format($date);
```

## The parser

In addition to the `Formatter`, a `Parser` service is also included. It has a single purpose, to ensure a mixed value, either a DateTime object or a parsable string, is parsed a DateTime object.

Any invalid string or parameter will throw an exception (`\InvalidArgumentException`) when parsing.
Except null value, which are allowed by default but may be disallowed with a parameter to the `parse()` method. In thoses cases, `null` will be returned by the `parse()` method instead of a `\DateTimeInterface` object if it is allowed as a parameter.
                                                                                      

```php
use \Mducharme\DateFormatter\Parser;

$parser = new Parser();

// Setting the strict mode to false allows a parser that will not throw exceptions.
$softParser = new Parser(false);

// With proper parameters, the parser returns (parsed) Datetime objects
$parser->parse('now');

// With invalid string or types of parameter, the strict parser throws an `\InvalidArgumentException`.
$parser->parse('-invalid-');
$parser->parse(new InvalidObject());
$oarser->parse(false);
```

Handling `null`:

```php
// By default, null is allowed. 
// Parsing returns null in this case, instead of the usual DateTimeInterface.
$parser->parse(null);

// If null is disallowed, attempting to parse a null value also throws an `\InvalidArgumentException`.
$parser->parse(null, false);

```

As a single-purpose service, the `Parser` is also invokable and may be called directly:

```php
$parsed = $parser($date);
```

## The Formatter


The formatter is the main Service provided by this library. It has a single purpose, to render a DateTime object into a formatted string (or multiple formats).


```php

```

As a single-purpose service, the `Formatter` is also invokable:

```php
echo $formatter($date, 'atom');
```


It comes with many formats by default. See the [Formatter](https://github.com/mducharme/date-formatter/tree/master/src/Formatter.php) source file for details on the default format. You may get an array of all available formats by using `Formatter::ALL` as the format parameter:

```php
$formatter = new Formatter($parser);
$allFormats = $formatter($date, Formatter::ALL);
var_dump($allFormats);
```

It is possible to add custom formats, or overwrite default ones, by passing an optional array of formats to the Formatter constructor.

```php
$customFormats = [
'is-leap-year' => function(\DateTimeInterface $date) {
        return ($date->format('L') ? 'Yes' : 'No';
    },
    'custom-string' => 'H:i:S (u) d-m-Y'
];
$formatter = new Formatter($parser, $customFormats);

// Outputs "Yes" because 2012 was a leap year
echo $formatter(new DateTime('2012-01-01'), 'is-leap-year');

// Outputs the custom format
echo $formatter(new DateTime('2012-01-01'), 'custom-string');
```

_Formats_ can either be a string, which will be formatted with `DateTimeInterface::format()` or a callback function with the following signature:

```php
/**
 * @param \DateTimeInterfae A date object.
 * @return string
 */
function callback(\DateTimeInterface $date);
```

If no `$format` is provided to the `format()` method (2nd parameter), then the default one will be used. Setting the default format is possible with the constructor. It is optional and will default to the 'atom' format.

```php
// This will use the "atom" format as no default format was specified to the constructor
$formatter1 = new Formatter($parser, null, null);
echo $formatter1($date);

// This will use the "rfc822" format, as it was set as default
$formatter2 = new Formatter($parser, null, 'rfc822');
echo $formatter2($date);
```

## The Service Provider

As a convenience, a Pimple Service Provider is also included for an already bootstrapped parser  (`date/parser`) and formatter (`date/formatter`).

```php
$container = new \Pimple\Container();
$container->register(new \Mducharme\DateFormatter\ServiceProvider());

$parser = $container['date/parser'];
$formatter = $container['date/formatter'];
```

To customize the options, the `date/custom-formats` and `date/default-format` container options can be extended:

