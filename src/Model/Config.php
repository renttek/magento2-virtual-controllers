<?php declare(strict_types=1);

namespace Renttek\VirtualControllers\Model;

use Magento\Framework\Config\CacheInterface;
use Renttek\VirtualControllers\Model\Config\Reader;

/**
 * Class Config
 * @codeCoverageIgnore
 */
class Config extends \Magento\Framework\Config\Data
{
    public const CACHE_ID = 'virtual_controller_config';

    public const CONTROLLER = 'controllers';
    public const FORWARD = 'forwards';

    /**
     * Config constructor.
     *
     * @param Reader         $reader
     * @param CacheInterface $cache
     * @param string         $cacheId
     */
    public function __construct(Reader $reader, CacheInterface $cache, string $cacheId = self::CACHE_ID)
    {
        parent::__construct($reader, $cache, $cacheId);
    }
}
