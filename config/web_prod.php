<?php

$params = require __DIR__ . '/params.php';

return [
    'id' => Yii::getAlias('@app.type'),
    'name' => 'APIMOCK Web App',
    'basePath' => dirname(__DIR__),
    'bootstrap' => [
        'dic-config',
        'common',
        'log'
    ],
    'components' =>  require_once __DIR__ . '/components.php',
    'runtimePath' => Yii::getAlias('@app.runtime.path'),
    'params' => $params,
    'modules' => [
        'mock' => [
            'class' => \Srplx\Mock\MockModule::class
        ],
        'common' => [
            'class' => \SrplxBoiler\Common\CommonModule::class
        ]
    ]
];
