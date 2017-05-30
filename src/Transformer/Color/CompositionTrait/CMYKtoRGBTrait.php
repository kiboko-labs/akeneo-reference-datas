<?php

namespace Kiboko\Component\AkeneoProductValuesPackage\Transformer\Color\CompositionTrait;

use Kiboko\Component\AkeneoProductValuesPackage\Model\ColorCMYK;
use Kiboko\Component\AkeneoProductValuesPackage\Model\ColorRGB;

trait CMYKtoRGBTrait
{
    /**
     * @param ColorCMYK $object
     *
     * @return ColorRGB
     */
    public function transform($object)
    {
        $result = new ColorRGB();
        $result->setRed(255 * (1 - $object->getCyan()) * (1 - $object->getKey()));
        $result->setGreen(255 * (1 - $object->getMagenta()) * (1 - $object->getKey()));
        $result->setBlue(255 * (1 - $object->getYellow()) * (1 - $object->getKey()));

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
        return $object instanceof ColorCMYK && $desiredType === ColorRGB::class;
    }
}
