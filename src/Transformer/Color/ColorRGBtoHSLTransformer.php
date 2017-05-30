<?php

namespace Kiboko\Component\AkeneoProductValuesPackage\Transformer\Color;

use Kiboko\Component\AkeneoProductValuesPackage\Model\ColorHSL;
use Kiboko\Component\AkeneoProductValuesPackage\Model\ColorRGB;
use Kiboko\Component\AkeneoProductValuesPackage\Transformer\Color\CompositionTrait\RGBtoHSLTrait;
use Kiboko\Component\AkeneoProductValuesPackage\Transformer\TransformerInterface;

class ColorRGBtoHSLTransformer implements TransformerInterface
{
    use RGBtoHSLTrait {
        transform as private transformRGBtoHSL;
    }

    /**
     * @param ColorRGB $object
     * @param string $desiredType
     *
     * @return ColorHSL
     */
    public function transform($object, $desiredType)
    {
        return $this->transformRGBtoHSL($object);
    }

    /**
     * @param object $object
     * @param string $desiredType
     *
     * @return bool
     */
    public function supportsTransformation($object, $desiredType)
    {
        return $object instanceof ColorRGB && $desiredType === ColorHSL::class;
    }
}
