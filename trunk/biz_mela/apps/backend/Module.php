<?php

namespace Biz_mela\Backend;

use Phalcon\Loader;
use Phalcon\Mvc\View;
use Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;
use Phalcon\Mvc\ModuleDefinitionInterface;


class Module implements ModuleDefinitionInterface
{

    /**
     * Registers the module auto-loader
     */
    public function registerAutoloaders()
    {

//        $loader = new Loader();
//
//        $loader->registerNamespaces(array(
//            'Biz_mela\Backend\Controllers' => __DIR__ . '/controllers/',
//            'Biz_mela\Backend\Models' => __DIR__ . '/models/',
////            'Biz_mela\Frontend\Models' => __DIR__ . '/models/',
//        ));
//
//        $loader->register();


//        $loader->registerNamespaces(array(
//            'Biz_mela\Frontend\Controllers' => __DIR__ . '/controllers/',
////            'Biz_mela\Backend\Models' => __DIR__ . '/models/',
//            'Biz_mela\Frontend\Models' => __DIR__ . '/models/',
//        ));
//
//        $loader->register();
    }

    /**
     * Registers the module-only services
     *
     * @param Phalcon\DI $di
     */
    public function registerServices($di)
    {

        /**
         * Read configuration
         */
        $config = include __DIR__ . "/config/config.php";

        /**
         * Setting up the view component
         */
        $di['view'] = function () {
            $view = new View();
            $view->setViewsDir(__DIR__ . '/views/');

            return $view;
        };

        /**
         * Database connection is created based in the parameters defined in the configuration file
         */
        $di['db'] = function () use ($config) {
            return new DbAdapter(array(
                "host" => $config->database->host,
                "username" => $config->database->username,
                "password" => $config->database->password,
                "dbname" => $config->database->dbname
            ));
        };

    }

}
