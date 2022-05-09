<?php

return yii\helpers\ArrayHelper::merge(
    require_once(__DIR__ . '/web_prod.php'),
    [
        'bootstrap' => ['log'],
        'modules' => []
    ]
);
