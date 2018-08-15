<?php declare(strict_types=1);
/**
 * Copyright © 2018 Neusta. All rights reserved.
 */

namespace Renttek\VirtualControllers\Controller;

use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\Request\Http;

/**
 * Class Forward
 */
class Forward implements ActionInterface
{
    /**
     * @var string
     */
    private $module;

    /**
     * @var string
     */
    private $controller;

    /**
     * @var string
     */
    private $action;

    /**
     * @var RequestInterface|Http
     */
    private $request;

    /**
     * Forward constructor.
     *
     * @param RequestInterface $request
     * @param string           $module
     * @param null|string      $controller
     * @param null|string      $action
     */
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
     */
    public function execute() : void
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

    /**
     * Builds the new request uri
     *
     * @return string
     */
    private function getNewRequestUri() : string
    {
        return implode('/', array_filter([$this->module, $this->controller, $this->action]));
    }
}
