<?php

namespace Kiboko\Component\AkeneoProductValuesPackage\Builder\Color;

use Kiboko\Component\AkeneoProductValues\Builder\RuleInterface;
use Kiboko\Component\AkeneoProductValuesPackage\Model\ColorCMYK;
use Kiboko\Component\AkeneoProductValuesPackage\Model\ColorHSL;
use Kiboko\Component\AkeneoProductValuesPackage\Model\ColorRGB;

interface ColorRuleInterface extends RuleInterface
{
    const NAME = 'Color';

    const TYPE_COLOR_RGB = ColorRGB::class;
    const TYPE_COLOR_CMYK = ColorCMYK::class;
    const TYPE_COLOR_HSL = ColorHSL::class;
}
