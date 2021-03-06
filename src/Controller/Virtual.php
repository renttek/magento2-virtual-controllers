<?php declare(strict_types=1);

namespace Renttek\VirtualControllers\Controller;

use Magento\Framework\App\ActionInterface;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;

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
     * @var null|string
     */
    private $title;

    public function __construct(
        PageFactory $resultPageFactory,
        string $path,
        ?string $handle = null,
        ?string $title = null,
        string $defaultHandle = 'virtual_controller'
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->title = $title;
        $this->defaultHandle = $defaultHandle;

        $handle = preg_replace('/[^A-Za-z0-9\_]/', '_', $handle ?? $path) ?? '';
        $this->handle = trim($handle, '_');
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

        if ($this->title !== null) {
            $page->getConfig()->getTitle()->set(__($this->title));
        }

        return $page;
    }
}
