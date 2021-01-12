<?php
/**
 * User: Daniel Schischkin <daniel.schischkin@surplex.com>
 * Date: 11.03.2019
 * Time: 15:46
 */
namespace Srplx\Tests\API\Mock\Controller;


use Codeception\Util\HttpCode;

class MockControllerCest
{
    /**
     * TEST: Save mock response in database
     * @param \ApiTester $I
     */
    public function tryToSaveResponse(\ApiTester $I)
    {
        $I->sendPOST('/api-mock/create', [
            'data' => [
                'test' => 'api'
            ],
            'status_code' => HttpCode::I_AM_A_TEAPOT,
            'headers' => [
                'Content-Type' => 'application/json'
            ],
            'session_id' => 'apitest'
        ]);
        $I->seeResponseCodeIs(HttpCode::CREATED);
    }

    /**
     * TEST: Receive count of not retrieved mocks. It should be 1
     * @param \ApiTester $I
     */
    public function tryToReceiveCountOfNotReceivedMocksAndShouldSeeOne(\ApiTester $I)
    {
        $I->sendGET('/api-mock/count?session_id=apitest');
        $I->see(1);
    }

    /**
     * TEST: Receive saved mock response
     * @param \ApiTester $I
     */
    public function tryToReceiveMock(\ApiTester $I)
    {
        $I->sendGET('/api/v1/test?session_id=apitest');
        $I->seeResponseCodeIs(HttpCode::I_AM_A_TEAPOT);
    }

    /**
     * TEST: Receive count of not retrieved mocks. It should be 0
     * @param \ApiTester $I
     */
    public function tryToReceiveCountOfNotReceivedMocksAndShouldSeeZero(\ApiTester $I)
    {
        $I->sendGET('/api-mock/count?session_id=apitest');
        $I->see(0);
    }

    /**
     * TEST: If a mock has a request_key assigned, the request is stored and
     * can be retrieved using that key. The retrieved request should contain
     * at least method and URL.
     * @param \ApiTester $I
     */
    public function tryToStoreClientRequest(\ApiTester $I)
    {
        $I->sendPOST('/api-mock/create', [
            'session_id'  => 'apitest',
            'status_code' => HttpCode::OK,
            'headers'     => ['Content-Type' => 'application/json'],
            'data'        => ['test' => 'api'],
            'request_key' => 'api_test_request',
        ]);
        $I->seeResponseCodeIs(HttpCode::CREATED);

        $testUrl = '/api/v1/test?session_id=apitest';
        $I->sendGET($testUrl);
        $I->seeResponseCodeIs(HttpCode::OK);

        $I->sendGET('/api-mock/client-request?session_id=apitest&request_key=api_test_request');
        $I->seeResponseCodeIs(HttpCode::OK);
        $response = json_decode($I->grabResponse(), true);
        $I->assertEquals('GET', $response['method']);
        $I->assertEquals($testUrl, $response['url']);
    }

    /**
     * TEST: Clear a session (and remove all expired mocks/requests). Should
     * return HTTP code 204.
     * @param \ApiTester $I
     */
    public function tryToClearSession(\ApiTester $I)
    {
        $I->sendGET('/api-mock/clear-session?session_id=apitest');
        $I->seeResponseCodeIs(HttpCode::NO_CONTENT);
    }
}
