<?php

namespace Kiboko\Component\AkeneoProductValuesPackage\Transformer\Color\CompositionTrait;

use Kiboko\Component\AkeneoProductValuesPackage\Model\ColorHSL;
use Kiboko\Component\AkeneoProductValuesPackage\Model\ColorRGB;

trait HSLtoRGBTrait
{
    /**
     * @param ColorHSL $object
     *
     * @return ColorRGB
     */
    public function transform($object)
    {
        $c = 1 - abs((2 * $object->getLightness()) - 1);
        $x = $c * (1 - abs((($object->getHue() / 60) % 2) - 1));
        $m = $object->getLightness() - ($c / 2);

        $result = new ColorRGB();
        if ($object->getHue() < 60) {
            $result->setRed(($c + $m) * 255);
            $result->setGreen(($x + $m) * 255);
            $result->setBlue($m * 255);
        } else if ($object->getHue() < 120) {
            $result->setRed(($x + $m) * 255);
            $result->setGreen(($c + $m) * 255);
            $result->setBlue($m * 255);
        } else if ($object->getHue() < 180) {
            $result->setRed($m * 255);
            $result->setGreen(($c + $m) * 255);
            $result->setBlue(($x + $m) * 255);
        } else if ($object->getHue() < 240) {
            $result->setRed($m * 255);
            $result->setGreen(($x + $m) * 255);
            $result->setBlue(($c + $m) * 255);
        } else if ($object->getHue() < 300) {
            $result->setRed(($x + $m) * 255);
            $result->setGreen($m * 255);
            $result->setBlue(($c + $m) * 255);
        } else if ($object->getHue() < 360) {
            $result->setRed(($c + $m) * 255);
            $result->setGreen($m * 255);
            $result->setBlue(($x + $m) * 255);
        }

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
        return $object instanceof ColorRGB && $desiredType === ColorHSL::class;
    }
}
