# HTTP-Factory

[![Travis](https://img.shields.io/travis/tbreuss/http-factory.svg)](https://travis-ci.org/tbreuss/http-factory)
[![Scrutinizer](https://img.shields.io/scrutinizer/g/tbreuss/http-factory.svg)](https://scrutinizer-ci.com/g/tbreuss/http-factory/)
[![Packagist](https://img.shields.io/packagist/dt/tebe/http-factory.svg)](https://packagist.org/packages/tebe/http-factory)
[![GitHub (pre-)release](https://img.shields.io/github/release/tbreuss/http-factory/all.svg)](https://github.com/tbreuss/http-factory/releases)
[![License](https://img.shields.io/github/license/tbreuss/http-factory.svg)](https://github.com/tbreuss/http-factory/blob/master/LICENSE)
[![PHP from Packagist](https://img.shields.io/packagist/php-v/tebe/http-factory.svg)](https://packagist.org/packages/tebe/http-factory)


:factory: HTTP-Factory is a PHP package that implements the [PSR-17 HTTP factories](https://www.php-fig.org/psr/psr-17/) interface.

The package includes auto-discovery support for the following PSR-7 packages:

1. zendframework/zend-diactoros
2. guzzlehttp/psr7  
3. slim/slim
4. nyholm/psr7

It acts as a simple facade which allows easy access to the above packages.


## Auto-discovering PSR-7 packages 

The auto-discovery mechanism assumes that you are using one (and only one) of the above PSR-7 packages in your project.
The first detected PSR-17 package will then be used for all interface factory methods.


## Installation

When starting a new project one of the following PSR-7 packages must be installed.

~~~
$ composer require zendframework/zend-diactoros
$ composer require guzzlehttp/psr7
$ composer require slim/slim
$ composer require nyholm/psr7
~~~

Then install the HTTP-Factory package.

~~~
$ composer require tebe/http-factory
~~~

You can now use HTTP-Factory in the codebase of your project.


## Usage

### Using constructor

~~~php
$factory = new \Tebe\HttpFactory\HttpFactory();
$response = $factory->createResponse(200);
echo $response->getStatusCode();
~~~

### Using static instance method

~~~php
$response = \Tebe\HttpFactory\HttpFactory::instance()->createResponse(200);
echo $response->getStatusCode();
~~~

### Using own strategies 

~~~php
// Using own strategies
\Tebe\HttpFactory\HttpFactory::setStrategies([
    DiactorosFactory::class,
    GuzzleFactory::class,
    SlimFactory::class
]);
$response = \Tebe\HttpFactory\HttpFactory::instance()->createResponse(200);
echo $response->getStatusCode();
~~~

### Using own factory

~~~php
class MyFactory implements \Tebe\HttpFactor\Factory\FactoryInterface
{
    // implement interface methods
}

$factory = new \Tebe\HttpFactory\HttpFactory();
$factory->setFactory(new MyFactory());
$response = $factory->createResponse(200);
echo $response->getStatusCode();
~~~

## Suggestions

Any suggestions? Open an issue.
