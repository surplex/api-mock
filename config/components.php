<?php
/**
 * As lng as you do not need to configure the array afterwards, this approach is totally fine
 */

use Monolog\Handler\StreamHandler;
use Monolog\Logger;

$psrLogger = new Logger('api-mock');
$psrLogger->pushHandler(new StreamHandler(__DIR__ . '/../var/logs/web.log', (
YII_ENV_DEV ? Logger::DEBUG : Logger::ERROR
)));

$componentConfigs = yii\helpers\ArrayHelper::merge(
    [
        'dic-config' => [
            'class' => \SrplxBoiler\Common\Component\YiiDicConfig\YiiDicConfigComponent::class,
            'singletons' => require(__DIR__ . '/services.php'),
            'nonSingletons' => require(__DIR__ . '/non_singletons.php'),
        ],
        'db' => [
            'class' => \yii\db\Connection::class,
            'dsn' => 'sqlite:' . __DIR__ . '/../db/mock.db',
            'charset' => 'utf8'
        ],
    ],
    ('web' === Yii::getAlias('@app.type'))
        ? [
        'log' => [
            'targets' => [
                [
                    'class' => \samdark\log\PsrTarget::class,
                    'logger' => $psrLogger
                ],
            ],
        ],
        'request' => [
            'enableCsrfCookie' => false,
            'cookieValidationKey' => $params['cookie.validation.key'],
            'parsers' => [
                'application/json' => yii\web\JsonParser::class,
                'application/xml' => \SrplxBoiler\Common\Component\Parser\XmlParser::class,
                'text/xml' => \SrplxBoiler\Common\Component\Parser\XmlParser::class,
                'application/vnd.api+json' => yii\web\JsonParser::class
            ]
        ],
        'response' => [
            'class' => \Srplx\Mock\Component\Response::class,
            'format' => \Srplx\Mock\Component\Response::FORMAT_JSON,
            'formatters' => [
                \Srplx\Mock\Component\Response::FORMAT_JSON => \yii\web\JsonResponseFormatter::class,
                \Srplx\Mock\Component\Response::FORMAT_JSON_API => \yii\web\JsonResponseFormatter::class,
                \Srplx\Mock\Component\Response::FORMAT_XML => \SrplxBoiler\Common\Component\Response\XmlFormatter::class
            ]
        ],
        'urlManager' => [
            'class' => \yii\web\UrlManager::class,
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => true,
            'suffix' => false,
            'rules' => (YII_ENV_DEV)
                ? require(__DIR__ . '/routes_dev.php')
                : require(__DIR__ . '/routes.php'),
        ],
        'errorHandler' => [
            'class' => \yii\web\ErrorHandler::class
        ],
        'user' => [
            'identityClass' => \yii\web\User::class
        ]
    ]
        : []
);
return $componentConfigs;
