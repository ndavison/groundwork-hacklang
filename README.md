# groundwork-hacklang - A micro-framework for RESTful JSON API development 
![build](https://travis-ci.org/ndavison/groundwork-hacklang.svg?branch=master)

written in (mostly) strict Hack.

This is a rewrite of [groundwork](https://github.com/ndavison/groundwork), 
from PHP to mostly strict [Hack](http://hacklang.org) - "mostly" because 
there is currently no way to bootstrap Hack code in strict mode (since it 
doesn't allow top level code), and also due to some reliance on the $_SERVER 
super global.

groundwork is a lightweight RESTful-like framework that is designed to 
accept input and output in JSON. It is designed to quickly get a backend 
communicating with a backbone.js, or equivalent, frontend.

## groundwork-hacklang offers the following features:

- Assign routes to a closure.
- Routes support parameters you can label and retrieve by name.
- Stateless i.e. no $_SESSION, $_COOKIE references in the core.
- JSON input decoding and output encoding (just as backbone.js expects).
- Easily respond to requests using common HTTP status codes.
- Simple handling of GET, POST, PUT and DELETE HTTP methods on resources.
- A footprint of about 20KB at the core.

## Examples

In the following example, I'll setup a quick route to respond to the request 
`/articles/199`. In app/Config/Routes.php, add:

```php

$router->register('articles/:id', function($ioc) {
    $request = $ioc->get('request');
	$response = $ioc->get('response');
	if ($request instanceof Request && $response instanceof Response) {
		$response->send(200, $request->routeParams);
	} else {
		throw new ServerException('IoC failed to resolve.');
	}
});

```

This will respond with `{"id":"199"}` in the response body when handed the 
request `/articles/199`, and a HTTP status code of 200. This is not HTTP 
request method specific.

To make a route respond to specific HTTP request methods, you can replace 
register() with get(), post(), put() or delete(). E.g.:

```php

$router->post('articles/:id', function($ioc) {
    $request = $ioc->get('request');
	$response = $ioc->get('response');
	if ($request instanceof Request && $response instanceof Response) {
		$response->send(200, $request->routeParams);
	} else {
		throw new ServerException('IoC failed to resolve.');
	}
});

```

This would respond to `/articles/199` only when the request is a HTTP POST.

## Installation

- Requires [HHVM](http://hhvm.com). Tested on 3.0.1.
- Requires [Composer](http://getcomposer.org)

groundwork is designed to have the 'public' directory as the only directory in 
the package accessible externally. This means that if you're installing 
groundwork under a VirtualHost in Apache, the web root should point to the 
'public' directory (e.g. /var/www/groundwork/public, perhaps). If you're 
installing groundwork under a sub directory of web root and not as its own 
virtual host, then you can setup an Apache alias to the public directory to 
achieve a nicer directory on the web side.

The file app/Config/Config.php contains the `baseurl` property which you will 
need to change to reflect where groundwork exists relative to the web root - 
e.g. if it is installed into http://localhost/bar/, then '/bar/' would be your 
value for this. 

## Autoload

Class autoloading is provided by the Composer autoload.php file, which is why a 
`composer update` must be executed from the root of the groundwork directory before 
groundwork will function correctly.

## Tests

A basic function test is provided inside app/Tests for the example home resource class. 
There are also unit tests available in framework/Tests. To run the tests, I've found this 
command works (from the root groundwork dir):

`hhvm $(which phpunit)`


## License

groundwork-hacklang is open-sourced software licensed under the MIT License.
