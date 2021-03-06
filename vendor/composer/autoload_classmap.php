<?php

// autoload_classmap.php @generated by Composer

$vendorDir = dirname(dirname(__FILE__));
$baseDir = dirname($vendorDir);

return array(
    'BaseController' => $baseDir . '/lib/base_controller.php',
    'BaseModel' => $baseDir . '/lib/base_model.php',
    'DB' => $baseDir . '/lib/database.php',
    'DatabaseConfig' => $baseDir . '/config/database.php',
    'Kilpailija' => $baseDir . '/app/models/Kilpailija.php',
    'Kilpailu' => $baseDir . '/app/models/Kilpailu.php',
    'Kilpailun_sarja' => $baseDir . '/app/models/Kilpailun_sarja.php',
    'Redirect' => $baseDir . '/lib/redirect.php',
    'Sarjan_osallistuja' => $baseDir . '/app/models/Sarjan_osallistuja.php',
    'View' => $baseDir . '/lib/view.php',
    'Whoops\\Module' => $vendorDir . '/filp/whoops/src/deprecated/Zend/Module.php',
    'Whoops\\Provider\\Zend\\ExceptionStrategy' => $vendorDir . '/filp/whoops/src/deprecated/Zend/ExceptionStrategy.php',
    'Whoops\\Provider\\Zend\\RouteNotFoundStrategy' => $vendorDir . '/filp/whoops/src/deprecated/Zend/RouteNotFoundStrategy.php',
    'kilpailija_controller' => $baseDir . '/app/controllers/kilpailija_controller.php',
    'kilpailu_controller' => $baseDir . '/app/controllers/kilpailu_controller.php',
    'kilpailun_sarja_controller' => $baseDir . '/app/controllers/kilpailun_sarja_controller.php',
    'login_controller' => $baseDir . '/app/controllers/login_controller.php',
    'yleisetNakymat_controller' => $baseDir . '/app/controllers/yleisetNakymat_controller.php',
    'yllapitajan_controller' => $baseDir . '/app/controllers/yllapitajan_controller.php',
);
