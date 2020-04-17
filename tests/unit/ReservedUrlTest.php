<?php

use Srplx\Mock\Component\ReservedUrl;

class ReservedUrlTest extends \Codeception\Test\Unit
{
    /** @var ReservedUrl */
    private $_instance;

    public function _before()
    {
        $this->_instance = new ReservedUrl('/^hallo$/', function () {
            return 'Hallo Welt';
        });
    }

    public function testMethodIsShouldReturnFalseWhenUrlIsNotIncluded()
    {
        $this->assertTrue(!$this->_instance->is('halloWelt'), 'The regular expression is may not valid.');
    }

    public function testMethodIsShouldReturnTrueWhenUrlIsIncluded()
    {
        $this->assertTrue($this->_instance->is('hallo'), 'The regular expression is may not valid.');
    }

    public function testMethodGetCallbackShouldReturnCallable()
    {
        $this->assertTrue(is_callable($this->_instance->getCallback()));
    }

}
