<?php declare(strict_types=1);

namespace Renttek\VirtualControllers\Model\Config;

use Magento\Framework\Config\ConverterInterface;

/**
 * Class Converter
 */
class Converter implements ConverterInterface
{
    public const CONTROLLER_QUERY = '/controllers/controller';

    /**
     * Convert config
     *
     * @param \DOMDocument $source
     *
     * @return array
     */
    public function convert($source) : array
    {
        $xpath = new \DOMXPath($source);

        return [
            'controllers' => $this->parseControllerConfig($xpath),
        ];
    }

    /**
     * Parses the controller config
     *
     * @param \DOMXPath $xpath
     *
     * @return array
     */
    private function parseControllerConfig(\DOMXPath $xpath) : array
    {
        $controllers = [];

        foreach ($xpath->query(self::CONTROLLER_QUERY) as $controllerNode) {
            /** @var \DOMNode $controllerNode */
            $attributes = getDomNodeAttributeValues($controllerNode);

            $path = $attributes['path'];

            $controllers[$path] = [
                'path'     => $path,
                'handle'   => $attributes['handle'] ?? null,
                'disabled' => filter_var($attributes['disabled'] ?? false, FILTER_VALIDATE_BOOLEAN),
            ];
        }

        return $controllers;
    }
}
