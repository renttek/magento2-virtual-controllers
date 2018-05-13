<?php declare(strict_types=1);

namespace Renttek\VirtualControllers\Controller;

use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\Request\Http;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\RouterInterface;
use Renttek\VirtualControllers\Model\Config;
use Renttek\VirtualControllers\Model\VirtualActionFactory;

/**
 * Class Router
 */
class Router implements RouterInterface
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @var VirtualActionFactory
     */
    private $actionFactory;

    /**
     * Router constructor.
     *
     * @param Config               $config
     * @param VirtualActionFactory $actionFactory
     */
    public function __construct(Config $config, VirtualActionFactory $actionFactory)
    {
        $this->config = $config;
        $this->actionFactory = $actionFactory;
    }

    /**
     * Checks if there are matching virtual controllers and returns a dummy action; Returns null if none is found
     *
     * @param RequestInterface|Http $request
     *
     * @return ActionInterface|null
     */
    public function match(RequestInterface $request) : ?ActionInterface
    {
        $path = trim($request->getPathInfo() ?? '', '/');

        $controllerConfig = $this->config->get('controllers');

        $canHandleAction = isset($controllerConfig[$path])
            && $controllerConfig[$path]['disabled'] === false;

        if (!$canHandleAction) {
            return null;
        }

        return $this->actionFactory->create(
            $controllerConfig[$path]['path'],
            $controllerConfig[$path]['handle']
        );
    }
}
