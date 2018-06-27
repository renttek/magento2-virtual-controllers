<?php declare(strict_types=1);

namespace Renttek\VirtualControllers\Test\Controller;

use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject as MockObject;
use Renttek\VirtualControllers\Controller\Router;
use Renttek\VirtualControllers\Model\Config;
use Renttek\VirtualControllers\Model\ActionFactory;

class RouterTest extends TestCase
{
    /**
     * @var Config|MockObject
     */
    private $configMock;

    /**
     * @var ActionFactory|MockObject
     */
    private $actionFactoryMock;

    /**
     * @var Router
     */
    private $router;

    protected function setUp()
    {
        $this->configMock = $this->getMockBuilder(Config::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->actionFactoryMock = $this->getMockBuilder(ActionFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->router = new Router($this->configMock, $this->actionFactoryMock);
    }

    public function testHandleActionReadsConfigFromModelWithGivenType()
    {
        $this->configMock
            ->expects(self::once())
            ->method('get')
            ->with('mytype');

        $this->router->handleAction('mytype', '');
    }

    public function testReturnsNullIfNoConfigurationWasFoundForGivenType()
    {
        self::assertNull($this->router->handleAction('mytype', ''));
    }

    public function testCallsActionFactoryIfMatchingConfigurationWasFound()
    {
        $this->configMock
            ->method('get')
            ->willReturn(['mypath' => []]);

        $this->actionFactoryMock
            ->expects(self::once())
            ->method('create')
            ->with('mytype', []);

        $this->router->handleAction('mytype', 'mypath');
    }
}
