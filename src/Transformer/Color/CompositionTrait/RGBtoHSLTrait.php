<?php

namespace Kiboko\Component\AkeneoProductValuesPackage\Transformer\Color\CompositionTrait;

use Kiboko\Component\AkeneoProductValuesPackage\Model\ColorHSL;
use Kiboko\Component\AkeneoProductValuesPackage\Model\ColorRGB;

trait RGBtoHSLTrait
{
    /**
     * @param ColorRGB $object
     *
     * @return ColorHSL
     */
    public function transform($object)
    {
        $red = $object->getRed() / 255;
        $green = $object->getGreen() / 255;
        $blue = $object->getBlue() / 255;

        $cmax = max($red, $green, $blue);
        $cmin = min($red, $green, $blue);

        $delta = $cmax - $cmin;

        $result = new ColorHSL();
        $result->setLightness(($cmax + $cmin) / 2);

        if ($delta === 0) {
            $result->setHue(0);
            $result->setSaturation(0);
        } else {
            switch (array_search($cmax, ['red' => $red, 'green' => $green, 'blue' => $blue])) {
                case 'red':
                    $result->setHue(((($green - $blue) / $delta) % 6) * 60);
                    break;

                case 'green':
                    $result->setHue(((($blue - $red) / $delta) + 2) * 60);
                    break;

                case 'blue':
                    $result->setHue(((($red - $green) / $delta) + 4) * 60);
                    break;
            }
        }
        $result->setSaturation($delta / (1 - abs(2 * ($result->getLightness() - 1))));

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
        return $object instanceof ColorHSL && $desiredType === ColorRGB::class;
    }
}
