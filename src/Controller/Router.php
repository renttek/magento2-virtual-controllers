<?php declare(strict_types=1);

namespace Renttek\VirtualControllers\Controller;

use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\Request\Http;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\RouterInterface;
use Renttek\VirtualControllers\Model\Config;
use Renttek\VirtualControllers\Model\ActionFactory;

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
     * @var ActionFactory
     */
    private $actionFactory;

    /**
     * Router constructor.
     *
     * @param Config               $config
     * @param ActionFactory $actionFactory
     */
    public function __construct(Config $config, ActionFactory $actionFactory)
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
     *
     * @codeCoverageIgnore
     */
    public function match(RequestInterface $request) : ?ActionInterface
    {
        $path = trim($request->getPathInfo() ?? '', '/');

        return $this->handleAction(Config::CONTROLLER, $path)
            ?? $this->handleAction(Config::FORWARD, $path);
    }

    /**
     * Creates an action and returns it or null if no matching configuration was found
     *
     * @param string $type
     * @param string $path
     *
     * @return ActionInterface|null
     */
    public function handleAction(string $type, string $path) : ?ActionInterface
    {
        $config = $this->config->get($type)[$path] ?? null;

        return $config !== null
            ? $this->actionFactory->create($type, $config)
            : null;
    }
}
