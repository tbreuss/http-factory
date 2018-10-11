# HTTP-Factory

:factory: HTTP-Factory is a PHP package that implements the [PSR-17 HTTP factories](https://www.php-fig.org/psr/psr-17/) interface.

The package includes auto-discovery support for the following PSR-7 packages:

1. zendframework/zend-diactoros
2. guzzlehttp/psr7  
3. slim/slim

It acts as a simple facade which allows easy access to the above packages.


## Auto-discovering PSR-7 packages 

The auto-discovery mechanism assumes that you are using one (and only one) of the above PSR-7 packages in your code.
The first detected PSR-17 package will be used for all interface factory methods.


## Installation

Composer is your friend.

As a prerequisite one of the following PSR-7 packages must be installed.

~~~
$ composer require zendframework/zend-diactoros
$ composer require guzzlehttp/psr7
$ composer require slim/slim
~~~

Install the HTTP-Factory package.

~~~
$ composer require tebe/http-factory:@dev
~~~

You can now use HTTP-Factory in your project.


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
