<?php

namespace Biz_mela\Frontend;

class Module
{

    public function registerAutoloaders()
    {

        $loader = new \Phalcon\Loader();

        $loader->registerNamespaces(array(
            'Biz_mela\Frontend\Controllers' => '../apps/frontend/controllers/',
            'Biz_mela\Frontend\Models' => '../apps/frontend/models/',
        ));

        $loader->register();
    }

    /**
     * Register the services here to make them general or register in the ModuleDefinition to make them module-specific
     */
    public function registerServices($di)
    {

        //Registering a dispatcher
        $di->set('dispatcher', function () {
            $dispatcher = new \Phalcon\Mvc\Dispatcher();

            //Attach a event listener to the dispatcher
            $eventManager = new \Phalcon\Events\Manager();
            $eventManager->attach('dispatch', new \Acl('frontend'));

            $dispatcher->setEventsManager($eventManager);
            $dispatcher->setDefaultNamespace("Biz_mela\Frontend\Controllers\\");
            return $dispatcher;
        });

        //Registering the view component
        $di->set('view', function () {
            $view = new \Phalcon\Mvc\View();
            $view->setViewsDir('../apps/frontend/views/');
            return $view;
        });

        $di->set('db', function () {
            return new \Phalcon\Db\Adapter\Pdo\Mysql(array(
                "host" => "114.134.91.91",
                "username" => "root",
                "password" => "btz123",
                "dbname" => "bizmela"
            ));
        });

    }

}