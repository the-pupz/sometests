<?php

// web/index.php
require_once __DIR__.'/vendor/autoload.php';
require_once __DIR__.'/backend/Principal.php';

use Silex\Application;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\DoctrineServiceProvider;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Principal\General;
use Principal\LinkList;
use Principal\ListNode;
use Principal\TwitterApi;
use Principal\GameOfStones;

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
//$app['session']->set('listnode', new LinkList());

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

$app->get('/linkedlist', function () use ($app) {
  $list = $app['session']->get('listnode');
  $array = $list->readList();
  return $app['twig']->render('index/linkedlist.twig', array(
    'list' => json_encode($array)
  ));
});

$app->post('/linkedlist/add/{number}', function (Request $request, $number) use ($app) {
  $message = $request->get('node');
  $list = $app['session']->get('listnode');
  if($number == '1'){
    $list->insertFirst($message);
  } elseif ($number == '2') {
    $list->insertLast($message);
  }
  $array = $list->readList();
  $app['session']->set('listnode', $list);
  return new Response(json_encode($array), 200);
});

$app->post('/linkedlist/remove/{number}', function (Request $request, $number) use ($app) {
  $list = $app['session']->get('listnode');
  if($number == '1'){
    $list->deleteFirstNode();
  } elseif ($number == '2') {
    $list->deleteLastNode();
  }
  $array = $list->readList();
  $app['session']->set('listnode', $list);
  return new Response(json_encode($array), 200);
});

$app->get('/tweet', function () use ($app) {
  return $app['twig']->render('index/tweet.twig');
});

$app->post('/tweet/search', function (Request $request) use ($app) {
  $message = $request->get('search');
  $tweet = new TwitterApi();
  $array = $tweet->call($message);
  return new Response(json_encode($array), 200);
});

$app->get('/stones', function () use ($app) {
  return $app['twig']->render('index/stones.twig');
});

$app->post('/stones/game', function (Request $request) use ($app) {
  $message = $request->get('cases');
  $list = new GameOfStones($message);
  $array = $list->loadResults();
  return new Response(json_encode($array), 200);
});

$app->get('/backquest', function () use ($app) {
  return $app['twig']->render('index/backend.twig');
});

$app->run();

?>