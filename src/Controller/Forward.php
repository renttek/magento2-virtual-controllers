<?php declare(strict_types=1);

namespace Renttek\VirtualControllers\Controller;

use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\Request\Http;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;

class Forward implements ActionInterface
{
    /**
     * @var string
     */
    private $module;

    /**
     * @var string|null
     */
    private $controller;

    /**
     * @var string|null
     */
    private $action;

    /**
     * @var RequestInterface|Http
     */
    private $request;

    public function __construct(
        RequestInterface $request,
        string $module,
        ?string $controller = null,
        ?string $action = null
    ) {
        $this->request = $request;
        $this->module = $module;
        $this->controller = $controller;
        $this->action = $action;
    }

    /**
     * Forward a request
     *
     * @return ResultInterface|ResponseInterface|void
     */
    public function execute()
    {
        $this->request->initForward();
        $this->request->setParams([]);
        $this->request->setModuleName($this->module);
        $this->request->setControllerName($this->controller);
        $this->request->setActionName($this->action);
        $this->request->setDispatched(false);

        $this->request->setRequestUri($this->getNewRequestUri());
        $this->request->setPathInfo();
    }

    private function getNewRequestUri() : string
    {
        return implode('/', array_filter([$this->module, $this->controller, $this->action]));
    }
}
