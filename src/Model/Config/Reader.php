<?php declare(strict_types=1);

namespace Renttek\VirtualControllers\Model\Config;

use Magento\Framework\Config\Reader\Filesystem;
use Magento\Framework\Config\FileResolverInterface;
use Magento\Framework\Config\ValidationStateInterface;
use Magento\Framework\Config\Dom;

/**
 * Class Reader
 * @codeCoverageIgnore
 * @SuppressWarnings(PHPMD.CamelCaseMethodName)
 */
class Reader extends Filesystem
{
    /**
     * Reader supported filename.
     *
     * @var string
     */
    public const FILENAME = 'virtual_controllers.xml';

    //@codingStandardsIgnoreLine
    protected $_idAttributes = [
        '/controllers/controller' => 'path',
        '/controllers/forward'    => 'path',
    ];

    /**
     * {@inheritdoc}
     */
    public function __construct(
        FileResolverInterface $fileResolver,
        Converter $converter,
        SchemaLocator $schemaLocator,
        ValidationStateInterface $validationState,
        $fileName = self::FILENAME,
        $idAttributes = [],
        $domDocumentClass = Dom::class,
        $defaultScope = 'global'
    ) {
        parent::__construct(
            $fileResolver,
            $converter,
            $schemaLocator,
            $validationState,
            $fileName,
            $idAttributes,
            $domDocumentClass,
            $defaultScope
        );
    }
}
