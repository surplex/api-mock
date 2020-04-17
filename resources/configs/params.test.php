<?php

$params = [
    'version' => '1',
    //web settings
    'web.host' => 'http://apimock-test.local',
    'cookie.validation.key' => 'cat'
];

$params = \yii\helpers\ArrayHelper::merge($params, require __DIR__ . '/../../config/params/log.php');

return $params;
