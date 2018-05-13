<?php

/**
 * Returns an all attribute  as an assoc. array [name => value]
 *
 * @param \DOMNode $node
 *
 * @return array
 *
 * @codeCoverageIgnore
 */
function getDomNodeAttributeValues(\DOMNode $node) : array
{
    $nodeAttributes = [];

    if (!$node->hasAttributes()) {
        return $nodeAttributes;
    }

    foreach ($node->attributes as $attribute) {
        /** @var \DOMNode $attribute */
        $nodeAttributes[$attribute->nodeName] = $attribute->nodeValue;
    }

    return $nodeAttributes;
}
