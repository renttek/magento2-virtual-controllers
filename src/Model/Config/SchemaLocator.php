<?php declare(strict_types=1);

namespace Renttek\VirtualControllers\Model\Config;

use Magento\Framework\Module\Dir;
use Magento\Framework\Config\SchemaLocatorInterface;

/**
 * Class SchemaLocator
 * @codeCoverageIgnore
 */
class SchemaLocator implements SchemaLocatorInterface
{
    /**
     * Path to corresponding XSD file with validation rules for both individual and merged configs.
     *
     * @var string
     */
    private $schema;

    /**
     * Constructor.
     *
     * @param Dir\Reader $moduleReader Module directory reader.
     */
    public function __construct(Dir\Reader $moduleReader)
    {
        $moduleDir = $moduleReader->getModuleDir(Dir::MODULE_ETC_DIR, 'Renttek_VirtualControllers');
        $this->schema = $moduleDir . '/virtual_controllers.xsd';
    }

    /**
     * {@inheritdoc}
     */
    public function getSchema() : ?string
    {
        return $this->schema;
    }

    /**
     * {@inheritdoc}
     */
    public function getPerFileSchema() : ?string
    {
        return $this->schema;
    }
}
