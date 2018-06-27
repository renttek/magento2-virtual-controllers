<?php declare(strict_types=1);

namespace Renttek\VirtualControllers\Model;

use Magento\Framework\App\ActionInterface;
use Magento\Framework\ObjectManagerInterface;
use Renttek\VirtualControllers\Controller\Forward;
use Renttek\VirtualControllers\Controller\Virtual;

/**
 * Class ActionFactory
 * @codeCoverageIgnore
 */
class ActionFactory
{
    /**
     * @var ObjectManagerInterface
     */
    private $objectManager;

    /**
     * VirtualActionFactory constructor.
     *
     * @param ObjectManagerInterface $objectManager
     */
    public function __construct(ObjectManagerInterface $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    /**
     * Creates an action based on the given type and returns null if type is unknown
     *
     * @param string $type
     * @param array  $config
     *
     * @return null|Forward|Virtual
     */
    public function create(string $type, array $config) : ?ActionInterface
    {
        switch ($type) {
            case Config::CONTROLLER:
                return $this->createVirtualAction($config);
            case Config::FORWARD:
                return  $this->createForwardAction($config);
        }

        return null;
    }

    /**
     * Creates a virtual controller action based on the configuration
     *
     * @param array $config
     *
     * @return Virtual|null
     */
    public function createVirtualAction(array $config) : ?Virtual
    {
        return $this->objectManager->create(
            Virtual::class,
            [
                'path'   => $config['path'],
                'handle' => $config['handle']
            ]
        );
    }

    /**
     * Creates a forward action based on the configuration
     *
     * @param array $config
     *
     * @return Forward|null
     */
    public function createForwardAction(array $config) : ?Forward
    {
        return $this->objectManager->create(
            Forward::class,
            [
                'module'     => $config['module'],
                'controller' => $config['controller'],
                'action'     => $config['action'],
            ]
        );
    }
}
