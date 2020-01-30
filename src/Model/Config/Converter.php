<?php declare(strict_types=1);

namespace Renttek\VirtualControllers\Model\Config;

use DOMXPath;
use Magento\Framework\Config\ConverterInterface;
use Renttek\VirtualControllers\Model\Config;

/**
 * Class Converter
 * @codeCoverageIgnore
 */
class Converter implements ConverterInterface
{
    public const CONTROLLER_QUERY = '/controllers/controller';
    public const FORWARD_QUERY = '/controllers/forward';

    /**
     * Convert config
     *
     * @param \DOMDocument $source
     *
     * @return array
     */
    public function convert($source) : array
    {
        $xpath = new DOMXPath($source);

        $config = [
            Config::CONTROLLER => $this->parseControllerConfig($xpath),
            Config::FORWARD    => $this->parseForwardConfig($xpath),
        ];

        return $config;
    }

    /**
     * Parses the controller config
     *
     * @param \DOMXPath $xpath
     *
     * @return array
     */
    public function parseControllerConfig(\DOMXPath $xpath) : array
    {
        return $this->parseConfig(
            $xpath,
            self::CONTROLLER_QUERY,
            function ($item) {
                return [
                    'path'   => $item['path'],
                    'handle' => $item['handle'] ?? null,
                    'title'  => $item['title'] ?? null,
                ];
            }
        );
    }

    /**
     * Parses the forward config
     *
     * @param \DOMXPath $xpath
     *
     * @return array
     */
    public function parseForwardConfig(\DOMXPath $xpath) : array
    {
        return $this->parseConfig(
            $xpath,
            self::FORWARD_QUERY,
            function ($item) {
                return [
                    'path'       => $item['path'],
                    'module'     => $item['module'],
                    'controller' => $item['controller'] ?? null,
                    'action'     => $item['action'] ?? null,
                ];
            }
        );
    }

    /**
     * Parses the configuration
     *
     * @param \DOMXPath     $xpath
     * @param string        $query
     * @param callable|null $mapFn
     *
     * @return array
     */
    public function parseConfig(\DOMXPath $xpath, string $query, ?callable $mapFn) : array
    {
        $disabledFilter = function ($node) {
            return !filter_var($node['disabled'] ?? false, FILTER_VALIDATE_BOOLEAN);
        };

        $nodes = iterator_to_array($xpath->query($query));
        $nodes = array_map('\getDomNodeAttributeValues', $nodes);
        $nodes = array_filter($nodes, $disabledFilter);

        if ($mapFn !== null) {
            $nodes = array_map($mapFn, $nodes);
        }

        return array_column($nodes, null, 'path');
    }
}
