<?php

return \yii\helpers\ArrayHelper::merge(
    //Common
    (YII_ENV_DEV)
        ? require_once(dirname(__DIR__) . '/src/Common/Resources/config/services_dev.php')
        : require_once(dirname(__DIR__) . '/src/Common/Resources/config/services.php'),
    //Other services
    []
);
