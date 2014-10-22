<?php

use Phalcon\Loader;

$loader = new Loader();

$loader->registerNamespaces(array(
    'Biz_mela\Frontend\Controllers' => __DIR__ . '/../apps/frontend/controllers/',
    'Biz_mela\Backend\Controllers'  => __DIR__ . '/../apps/backend/controllers/',
    'Biz_mela\Frontend\models' => __DIR__ . '/../apps/frontend/models/',
    'Biz_mela\Backend\models'  => __DIR__ . '/../apps/backend/models/',
//    'Biz_mela\Backend\Forms'        => __DIR__ . '/../apps/backend/forms/',
    'Biz_mela\Models'               => __DIR__ . '/../common/models/',
   'Biz_mela\Library\PHPMailer'      =>        __DIR__ . '/../library/PHPMailer/',
    'Biz_mela\Controllers'          => __DIR__ . '/../common/controllers/',
    /*'Biko\Validators'           => __DIR__ . '/../shared/validators/',
    'Biko\Behaviors'            => __DIR__ . '/../shared/behaviors/',
    'Biko'                      => __DIR__ . '/../library/Biko/',*/
));

$loader->register();