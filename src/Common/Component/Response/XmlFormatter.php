<?php
namespace SrplxBoiler\Common\Component\Response;

use yii\web\Response;
use yii\web\ResponseFormatterInterface;

class XmlFormatter implements ResponseFormatterInterface
{
    /**
     * Formats the specified response.
     *
     * @param Response $response the response to be formatted.
     */
    public function format($response)
    {
        $response->getHeaders()->set('Content-Type', 'text/xml; charset=UTF-8');
        if ($response->data !== null) {
            $response->content = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL . $response->data;
        }
    }
}
