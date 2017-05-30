<?php

namespace Kiboko\Component\AkeneoProductValuesPackage\Transformer\Color;

use Kiboko\Component\AkeneoProductValuesPackage\Model\ColorCMYK;
use Kiboko\Component\AkeneoProductValuesPackage\Model\ColorHSL;
use Kiboko\Component\AkeneoProductValuesPackage\Transformer\Color\CompositionTrait\HSLtoRGBTrait;
use Kiboko\Component\AkeneoProductValuesPackage\Transformer\Color\CompositionTrait\RGBtoCMYKTrait;
use Kiboko\Component\AkeneoProductValuesPackage\Transformer\TransformerInterface;

class HSLtoYCMK implements TransformerInterface
{
    use HSLtoRGBTrait {
        transform as private transformHSLtoRGB;
    }
    use RGBtoCMYKTrait {
        transform as private transformRGBtoCMYK;
    }

    /**
     * @param ColorHSL $object
     * @param string $desiredType
     *
     * @return ColorCMYK
     */
    public function transform($object, $desiredType)
    {
        return $this->transformRGBtoCMYK(
            $this->transformHSLtoRGB($object)
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
        return $object instanceof ColorHSL && $desiredType === ColorCMYK::class;
    }
}
