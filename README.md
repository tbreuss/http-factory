# HTTP-Factory

:factory: HTTP-Factory is a PHP package that implements the PSR-17 HTTP factory interface.
Additionally it has built-in autodiscovery support for the following PSR-7 packages:

- zendframework/zend-diactoros
- slim/slim
- guzzlehttp/psr7  

The package acts as a simple facade which allows easy access to the above packages.


## Installation

Composer is your friend.

~~~
$ composer require tebe/http-factory:@dev
~~~


## Usage

### Using Constructor

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
