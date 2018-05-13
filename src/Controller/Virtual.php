<?php declare(strict_types=1);

namespace Renttek\VirtualControllers\Controller;

use Magento\Framework\App\ActionInterface;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;

/**
 * Class Virtual
 */
class Virtual implements ActionInterface
{
    /**
     * @var PageFactory
     */
    private $resultPageFactory;

    /**
     * @var string
     */
    private $handle;

    /**
     * @var string
     */
    private $defaultHandle;

    /**
     * Index constructor.
     *
     * @param PageFactory $resultPageFactory
     * @param string      $path
     * @param null|string $handle
     * @param string      $defaultHandle
     */
    public function __construct(
        PageFactory $resultPageFactory,
        string $path,
        ?string $handle = null,
        string $defaultHandle = 'virtual_controller'
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->handle = trim(preg_replace('/[^A-Za-z0-9\_]/', '_', $handle ?? $path), '_');
        $this->defaultHandle = $defaultHandle;
    }

    /**
     * Returns a simple Page
     *
     * @return Page
     */
    public function execute() : Page
    {
        $page = $this->resultPageFactory->create();
        $page->addHandle($this->handle);
        $page->addHandle($this->defaultHandle);
        $page->addDefaultHandle();

        return $page;
    }
}
