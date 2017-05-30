<?php

namespace Kiboko\Component\AkeneoProductValuesPackage\Transformer\Color;

use Kiboko\Component\AkeneoProductValuesPackage\Model\ColorCMYK;
use Kiboko\Component\AkeneoProductValuesPackage\Model\ColorRGB;
use Kiboko\Component\AkeneoProductValuesPackage\Transformer\Color\CompositionTrait\CMYKtoRGBTrait;
use Kiboko\Component\AkeneoProductValuesPackage\Transformer\TransformerInterface;

class ColorCMYKtoRGBTransformer implements TransformerInterface
{
    use CMYKtoRGBTrait {
        transform as private transformCMYKtoRGB;
    }

    /**
     * @param ColorCMYK $object
     * @param string $desiredType
     *
     * @return ColorRGB
     */
    public function transform($object, $desiredType)
    {
        return $this->transformCMYKtoRGB($object);
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
