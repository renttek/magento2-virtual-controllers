<?php declare(strict_types=1);

namespace Renttek\VirtualControllers\Model;

use Magento\Framework\ObjectManagerInterface;
use Renttek\VirtualControllers\Controller\Index\Virtual;

/**
 * Class VirtualActionFactory
 * @codeCoverageIgnore
 */
class VirtualActionFactory
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
     * Returns a new Index-Action instance
     *
     * @param string $path
     * @param string $handle
     *
     * @return null|Virtual
     */
    public function create(string $path, ?string $handle) : ?Virtual
    {
        return $this->objectManager->create(
            Virtual::class,
            [
                'path'   => $path,
                'handle' => $handle
            ]
        );
    }
}
