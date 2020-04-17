<?php
/**
 *  INFO:
 *  Please note the regex matching and array order. Rules that are at top in array order, will match first
 */

return yii\helpers\ArrayHelper::merge(
    require __DIR__ . '/routes/mock.php',
    []
);
