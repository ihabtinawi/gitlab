<?php

use Symfony\Component\HttpFoundation\Request;

require __DIR__.'/../vendor/autoload.php';

$env = getenv('SYMFONY_ENV') ?: 'prod';
$debug = getenv('SYMFONY_DEBUG') !== '0' && $env !== 'prod';

if (PHP_VERSION_ID < 70000 && $env == 'prod') {
    include_once __DIR__.'/../var/bootstrap.php.cache';
}

if ($debug) {
    \Symfony\Component\Debug\Debug::enable();
}

$kernel = new AppKernel($env, $debug);
if (PHP_VERSION_ID < 70000 && $env == 'prod') {
    $kernel->loadClassCache();
}
//$kernel = new AppCache($kernel);

// When using the HttpCache, you need to call the method in your front controller instead of relying on the
// configuration parameter
//Request::enableHttpMethodParameterOverride();
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
