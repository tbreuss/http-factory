# HTTP-Factory

:factory: HTTP-Factory is a PHP package that implements the PSR-17 HTTP factory interface.
Additionally it has built-in support for the following PSR-7 packages:

- zendframework/zend-diactoros
- slim/slim
- guzzlehttp/psr7  

The package acts as a simple facade which allows easy access to the above packages.


## Installation

Composer is your friend.

~~~
$ composer require tebe/http-factory
~~~


## Usage

### Using Constructor

~~~php
use Tebe\HttpFactory\HttpFactory;

$factory = new HttpFactory();
$response = $factory->createResponse(200);
echo $response->getStatusCode();
~~~

### Using static instance method

~~~php
use Tebe\HttpFactory\HttpFactory;

$response = HttpFactory::instance()->createResponse(200);
echo $response->getStatusCode();
~~~

### Using own strategies 

~~~php
use Tebe\HttpFactory\HttpFactory;

// Using own strategies
HttpFactory::setStrategies([
    DiactorosFactory::class,
    GuzzleFactory::class,
    SlimFactory::class
]);
$response = HttpFactory::instance()->createResponse(200);
echo $response->getStatusCode();
~~~

### Using own factory

~~~php
use Tebe\HttpFactory\HttpFactory;

class MyFactory implements \Tebe\HttpFactor\Factory\FactoryInterface
{
    // implement methods
}

$factory = new HttpFactory();
$factory->setFactory(new MyFactory());
$response = $factory->createResponse(200);
echo $response->getStatusCode();
~~~

## Suggestions

Any suggestions? Open an issue.
