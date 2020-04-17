<?php
namespace SrplxBoiler\Common\Component\Response;

use yii\web\Response;
use yii\web\ResponseFormatterInterface;

class JsonFormatter implements ResponseFormatterInterface
{
    /**
     * Formats the specified response.
     *
     * @param Response $response the response to be formatted.
     */
    public function format($response)
    {
        $response->getHeaders()->set('Content-Type', 'application/json; charset=UTF-8');
        $response->content = json_encode($response->data);
    }
}
