<?php

namespace Srplx\Mock\Service;

use yii\base\BaseObject;
use yii\web\Cookie;
use yii\web\CookieCollection;

class SessionService extends BaseObject
{
    /**
     * Starts the session in Yii
     * SessionService constructor.
     *
     * @param array $config
     */
    public function __construct($config = [])
    {
        parent::__construct($config);
    }

    /**
     * Get the Session ID
     * @return string
     * @throws \Exception
     */
    public function getSession(): string
    {
        return \Yii::$app->getRequest()->getHeaders()['session_id'] ??
            \Yii::$app->getRequest()->getQueryParam('session_id') ??
            \Yii::$app->getRequest()->getCookies()->getValue('session_id') ??
            //\Yii::$app->getRequest()->getBodyParam('session_id') ??
            $this->createSession();
    }

    /**
     * Creates random session id and returns the session id
     * @return string
     * @throws \Exception
     */
    public function createSession(): string
    {
        $cookie = \Yii::createObject(Cookie::class);
        $cookie->name = 'session_id';
        $cookie->value = bin2hex(random_bytes(25));
        \Yii::$app->getResponse()->getCookies()->add($cookie);
        return \Yii::$app->getResponse()->getCookies()->getValue('session_id') ?? '';
    }

}
