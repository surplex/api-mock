<?php

namespace Srplx\Mock\Service;

use Srplx\Mock\Component\ReservedUrl;
use yii\base\InvalidCallException;
use yii\web\Request;
use yii\web\Response;

class ReservedUrlService
{
    /** @var array */
    private static $_reservedResponses = [];

    /**
     * @param Request $request
     *
     * @return ReservedUrl|null
     * @throws \yii\base\InvalidConfigException
     */
    public function getReservedRequest(Request $request): ?ReservedUrl
    {
        /** @var ReservedUrl $reservedUrl */
        foreach (static::$_reservedResponses as $reservedUrl) {
            if ($reservedUrl->is($request->getUrl())) {
                return $reservedUrl;
            }
        }
        return null;
    }

    /**
     * Adds a Url to reserved responses
     *
     * @param ReservedUrl $reservedUrl
     */
    public function addUrl(ReservedUrl $reservedUrl)
    {
        array_push(static::$_reservedResponses, $reservedUrl);
    }

    /**
     * Returns an array with all reserved responses
     * @return array
     */
    public function getUrls(): array
    {
        return static::$_reservedResponses;
    }

    /**
     * @param Request $request
     * @param Response $response
     *
     * @throws \yii\base\InvalidConfigException
     */
    public function handle(Request $request, Response $response)
    {
        $reservedUrl = !$this->getReservedRequest($request);
        if (is_null($reservedUrl)) {
            throw new InvalidCallException('Url is not reserved');
        }
        call_user_func($reservedUrl->getCallback(), $request, $response);
    }
}