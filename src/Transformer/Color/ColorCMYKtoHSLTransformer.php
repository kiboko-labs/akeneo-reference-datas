<?php

namespace Kiboko\Component\AkeneoProductValuesPackage\Transformer\Color;

use Kiboko\Component\AkeneoProductValuesPackage\Model\ColorCMYK;
use Kiboko\Component\AkeneoProductValuesPackage\Model\ColorHSL;
use Kiboko\Component\AkeneoProductValuesPackage\Transformer\Color\CompositionTrait\CMYKtoRGBTrait;
use Kiboko\Component\AkeneoProductValuesPackage\Transformer\Color\CompositionTrait\RGBtoHSLTrait;
use Kiboko\Component\AkeneoProductValuesPackage\Transformer\TransformerInterface;

class ColorCMYKtoHSLTransformer implements TransformerInterface
{
    use CMYKtoRGBTrait {
        transform as private transformCMYKtoRGB;
    }
    use RGBtoHSLTrait {
        transform as private transformRGBtoHSL;
    }

    /**
     * @param ColorCMYK $object
     * @param string $desiredType
     *
     * @return ColorHSL
     */
    public function transform($object, $desiredType)
    {
        return $this->transformRGBtoHSL(
            $this->transformCMYKtoRGB($object)
        );
    }

    /**
     * @param object $object
     * @param string $desiredType
     *
     * @return bool
     */
    public function supportsTransformation($object, $desiredType)
    {
        return $object instanceof ColorCMYK && $desiredType === ColorHSL::class;
    }
}
