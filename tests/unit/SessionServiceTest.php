<?php

namespace Srplx\Tests\Unit;

use Srplx\Mock\Service\SessionService;
use yii\web\Cookie;
use yii\web\CookieCollection;
use yii\web\Response;

class SessionServiceTest extends \Codeception\Test\Unit
{
    /** @var SessionService */
    public $sessionService;
    /** @var \yii\web\Application | \PHPUnit\Framework\MockObject\MockObject */
    public $yiiMock;
    /** @var \yii\web\Request | \PHPUnit\Framework\MockObject\MockObject */
    public $requestMock;
    /** @var CookieCollection */
    public $cookieCollection;

    public function _before()
    {
        $this->cookieCollection = \Yii::createObject(CookieCollection::class);
        $cookie = \Yii::createObject(Cookie::class);
        $cookie->name = 'session_id';
        $cookie->value = 'hallo';
        $this->cookieCollection->add($cookie);
        $this->yiiMock = $this->createMock(\yii\web\Application::class);
        \Yii::$app = &$this->yiiMock;
        $this->sessionService = new SessionService();
        $this->requestMock = $this->createMock(\yii\web\Request::class);
    }

    /**
     * TEST: When the key "id" is given in the session then it should be returned by the method getSession().
     * @throws \Exception
     */
    public function testIfSessionIsInCookieThenItShouldBeReturned()
    {
        $this->requestMock->method('getHeaders')->willReturn([]);
        $this->requestMock->method('getQueryParam')->willReturn(null);
        $this->requestMock->method('getCookies')->willReturn($this->cookieCollection);
        $this->yiiMock->method('getRequest')->willReturn($this->requestMock);
        $this->assertEquals('hallo', $this->sessionService->getSession(), 'id is not set in cookie');
    }

    /**
     * TEST: When the key "session_id" is given in the request headers then it should be returned by the method
     * getSession()
     * @throws \Exception
     */
    public function testIfSessionIdIsInHeadersThenItShouldBeReturned()
    {
        $this->requestMock->method('getHeaders')->willReturn(['session_id' => 'testheader']);
        $this->yiiMock->method('getRequest')->willReturn($this->requestMock);
        $this->assertEquals('testheader', $this->sessionService->getSession(), 'session_id is not set in headers');
    }

    /**
     * TEST: When the key "session_id" is given in the query params then it should be returned by the method
     * getSession()
     * @throws \Exception
     */
    public function testIfSessionIdIsInQueryParamsThenItShouldBeReturned()
    {
        $this->requestMock->method('getQueryParam')->willReturn('testquery');
        $this->yiiMock->method('getRequest')->willReturn($this->requestMock);
        $this->assertEquals('testquery', $this->sessionService->getSession(), 'session_id is not set in query params');
    }

    /**
     * TEST: When the key "session_id" is not given in headers,body or query params then it should be generated and
     * saved in a cookie.
     * @throws \Exception
     */
    public function testIfNoSessionIdIsFoundThenItShouldGenerateNewSession()
    {
        $this->cookieCollection->removeAll();
        $this->requestMock->method('getCookies')->willReturn($this->cookieCollection);
        $this->yiiMock->method('getRequest')->willReturn($this->requestMock);
        $responseMock = $this->createMock(Response::class);
        $responseMock->method('getCookies')->willReturn($this->cookieCollection);
        $this->yiiMock->method('getResponse')->willReturn($responseMock);

        $this->sessionService->getSession();

        $this->assertTrue(\Yii::$app->getResponse()->getCookies()->has('session_id'), 'id is not set in cookie');
    }

    /**
     * TEST: The returned session_id of the method createSession should equal with saved session id.
     * @throws \Exception
     */
    public function testIfSessionIsCreatedThenItShouldReturnGeneratedSession()
    {
        $responseMock = $this->createMock(Response::class);
        $responseMock->method('getCookies')->willReturn($this->cookieCollection);
        $this->yiiMock->method('getResponse')->willReturn($responseMock);
        $id = $this->sessionService->createSession();
        $this->assertTrue(\Yii::$app->getResponse()->getCookies()->getValue('session_id') == $id, 'generated session not equals saved session');
    }

}
