<?php declare(strict_types=1);
/**
 * Copyright © 2018 Neusta. All rights reserved.
 */

namespace Renttek\VirtualControllers\Test\Controller;

use Magento\Framework\App\Request\Http;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject as MockObject;
use Renttek\VirtualControllers\Controller\Forward;

/**
 * Class ForwardTest
 */
class ForwardTest extends TestCase
{
    /**
     * @var Http|MockObject
     */
    private $httpRequestMock;

    protected function setUp()
    {
        $this->httpRequestMock = $this->getMockBuilder(Http::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function testCallsInitForwardOnRequest()
    {
        $this->httpRequestMock
            ->expects(self::once())
            ->method('initForward');

        (new Forward($this->httpRequestMock, ''))->execute();
    }

    public function testResetsRequestParameters()
    {
        $this->httpRequestMock
            ->expects(self::once())
            ->method('setParams')
            ->with([]);

        (new Forward($this->httpRequestMock, ''))->execute();
    }

    public function testSetsModuleFromConstructorToRequest()
    {
        $this->httpRequestMock
            ->expects(self::once())
            ->method('setModuleName')
            ->with('mymodule');

        (new Forward($this->httpRequestMock, 'mymodule'))->execute();
    }

    public function testSetsControllerFromConstructorToRequest()
    {
        $this->httpRequestMock
            ->expects(self::once())
            ->method('setControllerName')
            ->with('mycontroller');

        (new Forward($this->httpRequestMock, '', 'mycontroller'))->execute();
    }

    public function testSetsActionFromConstructorToRequest()
    {
        $this->httpRequestMock
            ->expects(self::once())
            ->method('setActionName')
            ->with('myaction');

        (new Forward($this->httpRequestMock, '', '', 'myaction'))->execute();
    }

    public function testUnsetsDispatchedFlagOnRequest()
    {
        $this->httpRequestMock
            ->expects(self::once())
            ->method('setDispatched')
            ->with(false);

        (new Forward($this->httpRequestMock, '', '', 'action'))->execute();
    }

    public function testControllerDefaultsToIndexWhenNoneGiven()
    {
        $this->httpRequestMock
            ->expects(self::once())
            ->method('setControllerName')
            ->with('index');

        (new Forward($this->httpRequestMock, '', null))->execute();
    }

    public function testActionDefaultsToIndexWhenNoneGiven()
    {
        $this->httpRequestMock
            ->expects(self::once())
            ->method('setActionName')
            ->with('index');

        (new Forward($this->httpRequestMock, '', '', null))->execute();
    }
}
