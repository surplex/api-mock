<?php

namespace Srplx\Tests\Unit;

use PHPUnit\Framework\MockObject\MockObject;
use Srplx\Mock\Model\Mock;
use Srplx\Mock\Service\ResponseService;
use yii\web\HeaderCollection;
use yii\web\Response;

class ResponseServiceTest extends \Codeception\Test\Unit
{
    /** @var Response | MockObject */
    private $_responseMock;
    /** @var ResponseService */
    private $_responseServiceObj;
    /** @var HeaderCollection | MockObject */
    private $_headerCollectionMock;

    public function _before()
    {
        $this->_headerCollectionMock = $this->createMock(HeaderCollection::class);
        $this->_responseMock = $this->getMockBuilder(Response::class)
            ->setMethodsExcept([
                'setStatusCode',
                'getStatusCode',
                '__set',
                '__get'
            ])
            ->getMock();
        $this->_responseMock->method('getHeaders')->willReturn($this->_headerCollectionMock);
        $this->_responseServiceObj = new ResponseService();
    }

    /**
     * TEST: When the response is crafted then it should contain the given status code 404.
     */
    public function testIfResponseIsCraftedItShouldContainStatusCode418()
    {
        $mock = new class extends Mock
        {
            public $status_code = 418;
            public $headers = [];
            public $data = '';
        };
        $response = $this->_responseServiceObj->createResponse($this->_responseMock, $mock);
        $this->assertEquals(418, $response->getStatusCode());
    }

}
