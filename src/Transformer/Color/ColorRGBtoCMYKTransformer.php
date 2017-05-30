<?php

namespace Kiboko\Component\AkeneoProductValuesPackage\Transformer\Color;

use Kiboko\Component\AkeneoProductValuesPackage\Model\ColorCMYK;
use Kiboko\Component\AkeneoProductValuesPackage\Model\ColorRGB;
use Kiboko\Component\AkeneoProductValuesPackage\Transformer\Color\CompositionTrait\RGBtoCMYKTrait;
use Kiboko\Component\AkeneoProductValuesPackage\Transformer\TransformerInterface;

class ColorRGBtoCMYKTransformer implements TransformerInterface
{
    use RGBtoCMYKTrait {
        transform as private transformRGBtoCMYK;
    }

    /**
     * @param ColorRGB $object
     * @param string $desiredType
     *
     * @return ColorCMYK
     */
    public function transform($object, $desiredType)
    {
        return $this->transformRGBtoCMYK($object);
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
