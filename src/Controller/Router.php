<?php declare(strict_types=1);

namespace Renttek\VirtualControllers\Controller;

use Magento\Framework\App\ActionFactory as Action;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\Request\Http;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\UrlRewrite\Model\UrlFinderInterface;
use Renttek\VirtualControllers\Model\ActionFactory;
use Renttek\VirtualControllers\Model\Config;

/**
 * Class Router
 */
class Router extends \Magento\UrlRewrite\Controller\Router
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @var ActionFactory
     */
    private $virtualActionFactory;

    /**
     * @var bool
     */
    protected $dispatched;

    public function __construct(
        Action $actionFactory,
        UrlInterface $url,
        StoreManagerInterface $storeManager,
        ResponseInterface $response,
        UrlFinderInterface $urlFinder,
        ActionFactory $virtualActionFactory,
        Config $config
    ) {
        $this->config               = $config;
        $this->virtualActionFactory = $virtualActionFactory;

        parent::__construct($actionFactory, $url, $storeManager, $response, $urlFinder);
    }

    /**
     * Checks if there are matching virtual controllers and returns a dummy action; Returns null if none is found
     *
     * @param RequestInterface|Http $request
     *
     * @return ActionInterface|null
     *
     * @throws NoSuchEntityException
     *
     * @codeCoverageIgnore
     */
    public function match(RequestInterface $request)
    {
        if (!$this->dispatched) {
            $path             = trim($request->getOriginalPathInfo() ?? '', '/');
            $this->dispatched = true;

            return $this->handleAction(Config::CONTROLLER, $path)
                ?? $this->handleAction(Config::FORWARD, $path)
                ?? parent::match($request);
        }

        return null;
    }

    /**
     * Creates an action and returns it or null if no matching configuration was found
     *
     * @param string $type
     * @param string $path
     *
     * @return ActionInterface|null
     */
    public function handleAction(string $type, string $path): ?ActionInterface
    {
        $config = $this->config->get($type)[$path] ?? null;
        return $config !== null
            ? $this->virtualActionFactory->create($type, $config)
            : null;
    }
}
