<?php

namespace Kiboko\Component\AkeneoProductValuesPackage\Transformer\Color\CompositionTrait;

use Kiboko\Component\AkeneoProductValuesPackage\Model\ColorCMYK;
use Kiboko\Component\AkeneoProductValuesPackage\Model\ColorRGB;

trait RGBtoCMYKTrait
{
    /**
     * @param ColorRGB $object
     *
     * @return ColorCMYK
     */
    public function transform($object)
    {
        $red = $object->getRed() / 255;
        $green = $object->getGreen() / 255;
        $blue = $object->getBlue() / 255;

        $result = new ColorCMYK();
        $result->setKey(1 - max($red, $green, $blue));
        $result->setCyan((1 - $red - $result->getKey()) / (1 - $result->getKey()));
        $result->setMagenta((1 - $green - $result->getKey()) / (1 - $result->getKey()));
        $result->setYellow((1 - $blue - $result->getKey()) / (1 - $result->getKey()));

        return $result;
    }

    /**
     * @param object $object
     * @param string $desiredType
     *
     * @return bool
     */
    public function supportsTransformation($object, $desiredType)
    {
        return $object instanceof ColorRGB && $desiredType === ColorCMYK::class;
    }
}
