<?php declare(strict_types=1);

namespace Renttek\VirtualControllers\Test\Controller;

use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject as MockObject;
use Renttek\VirtualControllers\Controller\Virtual;

class VirtualTest extends TestCase
{
    /**
     * @var Page|MockObject
     */
    private $resultPageMock;

    /**
     * @var PageFactory|MockObject
     */
    private $resultPageFactoryMock;

    protected function setUp()
    {
        $this->resultPageMock = $this->getMockBuilder(Page::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->resultPageFactoryMock = $this->getMockBuilder(PageFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->resultPageFactoryMock
            ->method('create')
            ->willReturn($this->resultPageMock);
    }

    public function testCreatesResultPageByPageFactory()
    {
        $this->resultPageFactoryMock
            ->expects(self::once())
            ->method('create');

        (new Virtual($this->resultPageFactoryMock, ''))->execute();
    }

    public function testAddsDefaultVirtualOnResultPage()
    {
        $this->resultPageMock
            ->expects(self::at(1))
            ->method('addHandle')
            ->with('my_awesome_handle');

        (new Virtual($this->resultPageFactoryMock, '', null, 'my_awesome_handle'))->execute();
    }

    public function testAddsDefaultDefaultHandleOnResultPageIfNoHandleGiven()
    {
        $this->resultPageMock
            ->expects(self::at(1))
            ->method('addHandle')
            ->with('virtual_controller');

        (new Virtual($this->resultPageFactoryMock, ''))->execute();
    }

    public function testCallsAddDefaultHandleOnResultPage()
    {
        $this->resultPageMock
            ->expects(self::once())
            ->method('addDefaultHandle');

        (new Virtual($this->resultPageFactoryMock, ''))->execute();
    }

    public function testAddsCustomHandleOnResultPage()
    {
        $this->resultPageMock
            ->expects(self::at(0))
            ->method('addHandle')
            ->with('my_awesome_handle');

        (new Virtual($this->resultPageFactoryMock, '', 'my_awesome_handle'))->execute();
    }

    public function testCustomHandleFallsBackToPathIfNoHandleIsGiven()
    {
        $this->resultPageMock
            ->expects(self::at(0))
            ->method('addHandle')
            ->with('my_awesome_path');

        (new Virtual($this->resultPageFactoryMock, 'my_awesome_path'))->execute();
    }

    public function testReplacesAllSpecialCharsWithUnderscores()
    {
        $this->resultPageMock
            ->expects(self::at(0))
            ->method('addHandle')
            ->with('my_awesome_path');

        (new Virtual($this->resultPageFactoryMock, 'my!awesome$path'))->execute();
    }

    public function testRemovesUnderscoresOnBeginningAndEndOfPath()
    {
        $this->resultPageMock
            ->expects(self::at(0))
            ->method('addHandle')
            ->with('my_awesome_path');

        (new Virtual($this->resultPageFactoryMock, '__my_awesome_path__'))->execute();
    }

    public function testReturnsTheCreatedResultPage()
    {
        self::assertInstanceOf(Page::class, (new Virtual($this->resultPageFactoryMock, 'my_awesome_path'))->execute());
    }
}
