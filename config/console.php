<?php

$params = yii\helpers\ArrayHelper::merge(
    require_once(__DIR__ . '/params.php'),
    require_once(__DIR__ . '/params_console.php')
);

return
    [
        'id' => Yii::getAlias('@app.type'),
        'name' => 'API MOCK Console App',
        'basePath' => dirname(__DIR__),
        'bootstrap' => ['log'],
        //Take care of this line. Switched controller root to common module. Before it was: app\commands
        'controllerNamespace' => 'Srplx\Mock\Command',
        'runtimePath' => dirname(__DIR__) . '/var',
        'components' =>
            require_once(__DIR__ . '/components.php')
        ,
        'params' => $params,
        'modules' => [
            'mock' => [
                'class' => \Srplx\Mock\MockModule::class,
                'controllerNamespace' => 'Srplx\Mock\Controller'
            ]
        ]
    ];
