<?php

/**
 * Register application modules
 */
$application->registerModules(array(
    'frontend' => array(
        'className' => 'Biz_mela\Frontend\Module',
        'path' => __DIR__ . '/../apps/frontend/Module.php'
    ),
    'backend' => array(
        'className' => 'Biz_mela\Backend\Module',
        'path' => __DIR__ . '/../apps/backend/Module.php'
    )
));
