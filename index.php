<?php

// web/index.php
require_once __DIR__.'/vendor/autoload.php';

use Silex\Application;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\DoctrineServiceProvider;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$app = new Application();

$app['debug'] = true;

$app->register(new Silex\Provider\TwigServiceProvider(), array(
  'twig.path' => __DIR__.'/views',
));

$app->get('/hello/{name}', function ($name) use ($app) {
  return $app['twig']->render('hello.twig', array(
    'name' => $name,
  ));
});

$app->get('/', function () use ($app) {
  return $app['twig']->render('index/index.twig');
});

$app->get('/me', function () use ($app) {
  return $app['twig']->render('about/about.me.twig');
});

$app->error(function (\Exception $e, $code) use ($app) {
 switch ($code) {
 case 404:
 $message = $app['twig']->render('error404.twig', array(
 'code' => $code,
 'message' => $e->getMessage ()
 ));
 break;
 default:
 $message = $e->getMessage() . ' no arquivo ' . $e->getFile() . ', na linha: ' . $e->getLine();
 break;
 }
 return new Response($message, $code);
});

$app->run();

?>