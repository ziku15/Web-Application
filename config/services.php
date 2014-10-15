<?php

/**
 * Services are globally registered in this file
 */

use Phalcon\Mvc\Router;
use Phalcon\Mvc\Url as UrlResolver;
use Phalcon\DI\FactoryDefault;
use Phalcon\Session\Adapter\Files as SessionAdapter;

/**
 * The FactoryDefault Dependency Injector automatically register the right services providing a full stack framework
 */
$di = new FactoryDefault();

/**
 * Registering a router
 */
$di['router'] = function () {

    $router = new Router();

    $router->setDefaultModule("frontend");
    $router->setDefaultNamespace("Biz_mela\Frontend\Controllers");


    $router->add('/admin', array(
        'module' => "backend",
        'action' => "index",
        'params' => "index",
        'namespace' => 'Biz_mela\Backend\Controllers'
    ));

    $router->add('/admin/:controller', array(
        'module' => "backend",
        'controller' => 1,
        'action' => "index",
        'namespace' => 'Biz_mela\Backend\Controllers'
    ));

    $router->add('/admin/:controller/:action/', array(
        'module' => "backend",
        'controller' => 1,
        'action' => 2,
        'namespace' => 'Biz_mela\Backend\Controllers'
    ));

    $router->add('/admin/:controller/:action/:params', array(
        'module' => "backend",
        'controller' => 1,
        'action' => 2,
        'params' => 3,
        'namespace' => 'Biz_mela\Backend\Controllers'
    ));

    return $router;
};

/**
 * The URL component is used to generate all kind of urls in the application
 */
$di['url'] = function () {
    $url = new UrlResolver();
    $url->setBaseUri('/biz_mela/');

    return $url;
};

/**
 * Start the session the first time some component request the session service
 */
$di['session'] = function () {
    $session = new SessionAdapter();
    $session->start();

    return $session;
};
