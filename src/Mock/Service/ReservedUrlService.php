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
     * @return bool
     * @throws \yii\base\InvalidConfigException
     */
    public function isReservedRequest(Request $request): bool
    {
        /** @var ReservedUrl $reservedUrl */
        foreach (static::$_reservedResponses as $reservedUrl) {
            if ($reservedUrl->is($request->getUrl())) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param Request $request
     *
     * @return ReservedUrl|null
     * @throws \yii\base\InvalidConfigException
     */
    public function getReservedRequest(Request $request)
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
        if (!$this->isReservedRequest($request)) {
            throw new InvalidCallException('Url is not reserved');
        }
        call_user_func($this->getReservedRequest($request)->getCallback(), $request, $response);
    }
}