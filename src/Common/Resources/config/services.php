<?php

return \yii\helpers\ArrayHelper::merge(
    require_once __DIR__ . '/services/cache.php',
    require_once __DIR__ . '/services/component.php'
);
