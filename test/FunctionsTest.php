<?php declare(strict_types=1);

namespace Renttek\VirtualControllers\Test;

use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject as MockObject;

/**
 * Class FunctionsTest
 */
class FunctionsTest extends TestCase
{
    /**
     * @var \DOMNode|MockObject
     */
    private $nodeMock;

    protected function setUp()
    {
        $this->nodeMock = $this->getMockBuilder(\DOMNode::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function testChecksIfNodeHasAttributes()
    {
        $this->nodeMock
            ->expects(self::once())
            ->method('hasAttributes');

        getDomNodeAttributeValues($this->nodeMock);
    }

    public function testReturnsEmptyArrayIfNodeDoesNotHaveAttributes()
    {
        $this->nodeMock
            ->method('hasAttributes')
            ->willReturn(false);

        self::assertEquals([], getDomNodeAttributeValues($this->nodeMock));
    }
}
