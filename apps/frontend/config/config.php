<?php

return new \Phalcon\Config(array(
    'database' => array(
        'adapter'  => 'Mysql',
        'host'     => '114.134.91.91',
        'username' => 'root',
        'password' => 'btz123',
        'dbname'     => 'bizmela',
    ),
    'application' => array(
        'controllersDir' => __DIR__ . '/../controllers/',
        'modelsDir' => __DIR__ . '/../models/',
        'viewsDir' => __DIR__ . '/../views/',
        'libraryDir' => __DIR__ . '/../library/',
        'pluginsDir' => __DIR__ . '/../plugins/',
        'baseUri' => '/biz_mela/'
    )
));
