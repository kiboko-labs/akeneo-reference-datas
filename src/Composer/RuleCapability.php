<?php

namespace Kiboko\Component\AkeneoProductValuesPackage\Composer;

use Composer\Composer;
use Kiboko\Component\AkeneoProductValuesPackage\Builder\MultipleColorRGBRule;
use Kiboko\Component\AkeneoProductValuesPackage\Builder\SingleColorRGBRule;
use Kiboko\Component\AkeneoProductValues\Composer\RuleCapability as RuleCapabilityInterface;

class RuleCapability implements RuleCapabilityInterface
{
    public function getRules(Composer $composer)
    {
        $root = $composer->getConfig()->get('akeneo-appbundle-root-dir') ?: 'src';
        $vendor = $composer->getConfig()->get('akeneo-appbundle-vendor-name') ?: null;
        $bundle = $composer->getConfig()->get('akeneo-appbundle-bundle-name') ?: 'AppBundle';

        return [
            'color.rgb.single'   => new SingleColorRGBRule($root, $bundle, $vendor),
            'color.rgb.multiple' => new MultipleColorRGBRule($root, $bundle, $vendor),
        ];
    }
}
