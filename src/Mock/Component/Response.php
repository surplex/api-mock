<?php

namespace Srplx\Mock\Component;

use yii\helpers\ArrayHelper;
use yii\web\JsonResponseFormatter;

/**
 * Insert your custom formatters/types here to avoid the exception yii\web\UnsupportedMediaTypeHttpException
 * @package Srplx\Mock\Component
 */
class Response extends \yii\web\Response
{
    /** @var string */
    const FORMAT_JSON_API = 'json-api';
    /**
     * {@inheritDoc}
     */
    public function defaultFormatters(): array
    {
        return ArrayHelper::merge(parent::defaultFormatters(), [
            self::FORMAT_JSON_API => [
                'class' => JsonResponseFormatter::class
            ]
        ]);
    }

}