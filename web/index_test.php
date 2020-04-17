<?php
// comment out the following two lines when deployed to production
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require_once(__DIR__ . '/../vendor/autoload.php');
require_once(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');

//define aliases
Yii::setAlias('@app.type', 'web');
Yii::setAlias('@app.runtime.path', dirname(__DIR__) . '/var');

//overwrite classes
Yii::$classMap['yii\helpers\Json'] = dirname(__DIR__) . '/src/Common/Helper/Json.php';

//create app
(new yii\web\Application(require_once __DIR__ . '/../config/web_prod.php'))->run();