<?php

namespace Kiboko\Component\AkeneoProductValuesPackage\Transformer\Color;

use Kiboko\Component\AkeneoProductValuesPackage\Model\ColorHSL;
use Kiboko\Component\AkeneoProductValuesPackage\Model\ColorRGB;
use Kiboko\Component\AkeneoProductValuesPackage\Transformer\Color\CompositionTrait\HSLtoRGBTrait;
use Kiboko\Component\AkeneoProductValuesPackage\Transformer\TransformerInterface;

class ColorHSLtoRGBTransformer implements TransformerInterface
{
    use HSLtoRGBTrait {
        transform as private transformHSLtoRGB;
    }

    /**
     * @param ColorHSL $object
     * @param string $desiredType
     *
     * @return ColorRGB
     */
    public function transform($object, $desiredType)
    {
        return $this->transformHSLtoRGB($object);
    }

    /**
     * @param object $object
     * @param string $desiredType
     *
     * @return bool
     */
    public function supportsTransformation($object, $desiredType)
    {
        return $object instanceof ColorHSL && $desiredType === ColorRGB::class;
    }
}
