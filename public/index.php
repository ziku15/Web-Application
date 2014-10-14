<?php

error_reporting(E_ALL);

class Application extends \Phalcon\Mvc\Application
{
//$config = new Phalcon\Config\Adapter\Ini(__DIR__ . '/../app/config/config.ini');
    /**
     * Register the services here to make them general or register in the ModuleDefinition to make them module-specific
     */
    protected function _registerServices()
    {

        $config = new Phalcon\Config\Adapter\Ini('/Library/WebServer/Documents/biz_mela/public/config/config.ini');


        $di = new \Phalcon\DI\FactoryDefault();

        $loader = new \Phalcon\Loader();

        $di->set('url', function () use ($di, $config) {
            $url = new \Phalcon\Mvc\Url();
            $dispatcher = $di->getShared('dispatcher');
            $url->setBaseUri($config->url->baseUrl);
//
            return $url;

        });

//        echo $url;


        /**
         * We're a registering a set of directories taken from the configuration file
         */
        $loader->registerDirs(
            array(
                __DIR__ . '/../apps/library/'
            )
        )->register();

        //Registering a router
        $di->set('router', function(){

            $router = new \Phalcon\Mvc\Router();

            $router->setDefaultModule("frontend");

            $router->add('/:controller/:action', array(
                'module' => 'frontend',
                'controller' => 1,
                'action' => 2,
            ));

            $router->add("/login", array(
                'module' => 'backend',
                'controller' => 'login',
                'action' => 'index',
            ));

            $router->add("/admin/:products/:action", array(
                'module' => 'backend',
                'controller' => 1,
                'action' => 2,
            ));

            $router->add("/products/:action", array(
                'module' => 'frontend',
                'controller' => 'products',
                'action' => 1,
            ));
            $router->add("/index/:action", array(
                'module' => 'frontend',
                'controller' => 'index',
                'action' => 1,
            ));

            $router->add("/:controller/:action", array(
                'module' => 'frontend',
                'controller' => 1,
                'action' => 2,
            ));

            return $router;

        });

        $this->setDI($di);
    }

    public function main()
    {

        $this->_registerServices();

        //Register the installed modules
        $this->registerModules(array(
            'frontend' => array(
                'className' => 'Biz_mela\Frontend\Module',
                'path' => '../apps/frontend/Module.php'
            ),
            'backend' => array(
                'className' => 'Biz_mela\Backend\Module',
                'path' => '../apps/backend/Module.php'
            )
        ));

        echo $this->handle()->getContent();
    }

}

$application = new Application();
$application->main();

