<?php

// web/index.php
require_once __DIR__.'/vendor/autoload.php';


use Silex\Application;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\DoctrineServiceProvider;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Principal\General;
use Principal\LinkList;
use Principal\ListNode;

$app = new Application();

$app->before(function ($request) {
    $request->getSession()->start();
});

$app['debug'] = true;

$app->register(new Silex\Provider\TwigServiceProvider(), array(
  'twig.path' => __DIR__.'/views',
));

$app->register(new Silex\Provider\SessionServiceProvider, array(
    'session.storage.save_path' => dirname(__DIR__) . '/tmp/sessions'
));

$app->before(function (Request $request) use ($app) {
//    if ($app['debug'] == true) {
//        $request->getSession()->set('test', array('key' => 'value'));
//    }
    if ($request->hasPreviousSession() && $request->getSession()->has('test')) {
        $test = $request->getSession()->get('test');
        if ($test !== null) {
            $app['twig']->addGlobal('before', $test);
        }
    }
});
if(null === $user = $app['session']->get('listnode')){
  $app['session']->set('listnode', new LinkList());
}

$app->get('/hello/{name}', function ($name) use ($app) {
  return $app['twig']->render('hello.twig', array(
    'name' => $name,
  ));
});

$app->get('/', function () use ($app) {
  return $app['twig']->render('index/index.twig');
});

$app->get('/bubble', function () use ($app) {
  $general = new General();
  $final = $general->BubbleSort();

  return $app['twig']->render('index/bubble.twig', array(
    'data' => $final['data'],
    'data1' => $final['data1']
  ));
});

$app->get('/linkedlist', function () use ($app, $list) {
  $list = $app['session']->get('listnode');
  $array = $list->readList();
  return $app['twig']->render('index/linkedlist.twig', array(
    'list' => $array
  ));
});

$app->post('/linkedlist/add/{$number}', function (Request $request) use ($app, $list) {
  $message = $request->get('node');
  $list = $app['session']->get('listnode');
  $list->insertFirst($message);
  $array = $list->readList();
  $app['session']->set('listnode', $list);
  return new Response(json_encode($array), 200);
});

$app->get('/backquest', function () use ($app) {
  return $app['twig']->render('index/backend.twig');
});

$app->get('/frontquest', function () use ($app) {
  return $app['twig']->render('index/frontend.twig');
});

$app->get('/me', function () use ($app) {
  return $app['twig']->render('about/about.me.twig');
});

$app->run();

?>