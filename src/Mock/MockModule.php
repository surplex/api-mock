<?php

namespace Srplx\Mock;

use yii\base\Module;

class MockModule extends Module
{
    /** @var string */
    public $controllerNamespace = 'Srplx\Mock\Controller';

    /**
     * {@inheritDoc}
     */
    public function init()
    {
        parent::init();
        if (\Yii::$app instanceof \yii\web\Application) {
            \Yii::$app->user->enableSession = false;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function behaviors(): array
    {
        return [
            'corsFilter' => [
                'class' => \yii\filters\Cors::class,
                'cors' => [
                    'Origin' => ['*'],
                    'Access-Control-Request-Method' => ['POST', 'GET', 'OPTIONS'],
                    'Access-Control-Max-Age' => 3600,
                    'Access-Control-Allow-Headers' => ['*'],
                ],

            ],
        ];
    }
}
