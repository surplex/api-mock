<?php

namespace Srplx\Mock\Service;

use Srplx\Mock\Model\Mock;
use yii\base\BaseObject;
use yii\web\Response;

class ResponseService extends BaseObject
{
    /**
     * Crafts the response for a mock
     * @param Response $response
     * @param Mock $mock
     * @return Response
     */
    public function createResponse(Response $response, Mock $mock): Response
    {
        $response->setStatusCode($mock->status_code);
        $mock->headers = (is_array($mock->headers)) ? $mock->headers : json_decode($mock->headers, true);
        foreach ($mock->headers as $key => $value) {
            $response->getHeaders()->set($key, $value);
        }
        $response->content = (is_array($mock->data)) ? json_encode($mock->data) : $mock->data;
        return $response;
    }

}
