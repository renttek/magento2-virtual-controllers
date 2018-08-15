<?php declare(strict_types=1);
/**
 * Copyright © 2018 Neusta. All rights reserved.
 */

namespace Renttek\VirtualControllers\Test\Model;

use Magento\Framework\App\ObjectManager;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject as MockObject;
use Renttek\VirtualControllers\Controller\Forward;
use Renttek\VirtualControllers\Controller\Virtual;
use Renttek\VirtualControllers\Model\ActionFactory;

class ActionFactoryTest extends TestCase
{
    /**
     * @var ObjectManager|MockObject
     */
    private $objectManagerMock;

    /**
     * @var ActionFactory
     */
    private $actionFactory;

    protected function setUp()
    {
        $this->objectManagerMock = $this->getMockBuilder(ObjectManager::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->actionFactory = new ActionFactory($this->objectManagerMock);
    }

    public function testCreateReturnsNullIfAnUnknownTypeIsGiven()
    {
        self::assertNull($this->actionFactory->create('foo-type', []));
    }

    public function testCreateVirtualActionCreatesVirtualUsingObjectManager()
    {
        $this->objectManagerMock
            ->expects(self::once())
            ->method('create')
            ->with(
                Virtual::class,
                [
                    'path'   => 'mypath',
                    'handle' => 'myhandle',
                    'title'  => 'mytitle',
                ]
            );

        $this->actionFactory->createVirtualAction(
            [
                'path'   => 'mypath',
                'handle' => 'myhandle',
                'title'  => 'mytitle',
            ]
        );
    }

    public function testCreateForwardActionCreatesVirtualUsingObjectManager()
    {
        $this->objectManagerMock
            ->expects(self::once())
            ->method('create')
            ->with(
                Forward::class,
                [
                    'module'     => 'mymodule',
                    'controller' => 'mycontroller',
                    'action'     => 'myaction'
                ]
            );

        $this->actionFactory->createForwardAction(
            [
                'module'     => 'mymodule',
                'controller' => 'mycontroller',
                'action'     => 'myaction'
            ]
        );
    }
}
