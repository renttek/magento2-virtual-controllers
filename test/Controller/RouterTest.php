<?php declare(strict_types=1);

namespace Renttek\VirtualControllers\Test\Controller;

use Magento\Framework\App\Request\Http;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject as MockObject;
use Renttek\VirtualControllers\Controller\Router;
use Renttek\VirtualControllers\Model\Config;
use Renttek\VirtualControllers\Model\VirtualActionFactory;

class RouterTest extends TestCase
{
    /**
     * @var Config|MockObject
     */
    private $configMock;

    /**
     * @var VirtualActionFactory|MockObject
     */
    private $virtualActionFactoryMock;

    /**
     * @var Http|MockObject
     */
    private $requestMock;

    /**
     * @var Router
     */
    private $router;

    protected function setUp()
    {
        $this->configMock = $this->getMockBuilder(Config::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->virtualActionFactoryMock = $this->getMockBuilder(VirtualActionFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->requestMock = $this->getMockBuilder(Http::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->router = new Router($this->configMock, $this->virtualActionFactoryMock);
    }

    public function testReadsPathInfoFromRequest()
    {
        $this->requestMock
            ->expects(self::once())
            ->method('getPathInfo');

        $this->router->match($this->requestMock);
    }

    public function testReadsControllerConfigFromConfigModel()
    {
        $this->configMock
            ->expects(self::once())
            ->method('get')
            ->with('controllers');

        $this->router->match($this->requestMock);
    }

    public function testReturnsNullIfPathIsNotFoundInConfig()
    {
        self::assertNull($this->router->match($this->requestMock));
    }

    public function testReturnsNullIfVirtualControllerIsDisabled()
    {
        $this->requestMock
            ->method('getPathInfo')
            ->willReturn('my/path');

        $this->configMock
            ->method('get')
            ->willReturn(['my/path' => ['disabled' => true]]);

        self::assertNull($this->router->match($this->requestMock));
    }

    /**
     * @expectedException \PHPUnit\Framework\Error\Notice
     * @expectedExceptionMessage Undefined index: disabled
     */
    public function testControllerConfigMustHaveDisabledIndex()
    {
        $this->requestMock
            ->method('getPathInfo')
            ->willReturn('my/path');

        $this->configMock
            ->method('get')
            ->willReturn(['my/path' => []]);

        $this->router->match($this->requestMock);
    }

    /**
     * @expectedException \PHPUnit\Framework\Error\Notice
     * @expectedExceptionMessage Undefined index: path
     */
    public function testControllerConfigMustHavePathIndex()
    {
        $this->requestMock
            ->method('getPathInfo')
            ->willReturn('my/path');

        $this->configMock
            ->method('get')
            ->willReturn(['my/path' => ['disabled' => false]]);

        $this->router->match($this->requestMock);
    }

    /**
     * @expectedException \PHPUnit\Framework\Error\Notice
     * @expectedExceptionMessage Undefined index: handle
     */
    public function testControllerConfigMustHaveHandleIndex()
    {
        $this->requestMock
            ->method('getPathInfo')
            ->willReturn('my/path');

        $this->configMock
            ->method('get')
            ->willReturn(['my/path' => ['disabled' => false, 'path' => '']]);

        $this->router->match($this->requestMock);
    }

    public function testCreatesAnActionWithPathAndHandleFromControllerConfig()
    {
        $this->requestMock
            ->method('getPathInfo')
            ->willReturn('my/path');

        $this->configMock
            ->method('get')
            ->willReturn([
                'my/path' => [
                    'path'     => 'my/path',
                    'handle'   => 'my_path',
                    'disabled' => false
                ]
            ]);

        $this->virtualActionFactoryMock
            ->expects(self::once())
            ->method('create')
            ->with('my/path', 'my_path')
            ->willReturn(null);

        $this->router->match($this->requestMock);
    }
}
