<?php
//HTTP_X_CODECEPTION_CODECOVERAGE
if (!array_key_exists('HTTP_X_CODECEPTION_CODECOVERAGE', $_SERVER)) {
    $_SERVER['HTTP_X_CODECEPTION_CODECOVERAGE'] = 'web-test';
}

//no report will be generated in media storage when dgub is active
//$_SERVER['HTTP_X_CODECEPTION_CODECOVERAGE_DEBUG'] = 1;
define('C3_CODECOVERAGE_ERROR_LOG_FILE', dirname(__DIR__) . '/tests/_output/c3_error.log'); //Optional (if not set the default c3 output dir will be used)
require '../c3.php';

//required for stopping after c3 functions
$testPath = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
if('index_coverage.php' == $testPath[0] && 'c3' == $testPath[1]) {
    die();
}

// comment out the following two lines when deployed to production
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

//define aliases
Yii::setAlias('@app.type', 'web');
Yii::setAlias('@app.runtime.path', dirname(__DIR__) . '/var');

//overwrite classes
Yii::$classMap['yii\helpers\Json'] = dirname(__DIR__) . '/src/Common/Helper/Json.php';

//create app
(new yii\web\Application(require_once __DIR__ . '/../config/web_dev.php'))->run();