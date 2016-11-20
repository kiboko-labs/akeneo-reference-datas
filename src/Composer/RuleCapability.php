<?php

namespace Kiboko\Component\AkeneoProductValuesPackage\Composer;

use Composer\Composer;
use Kiboko\Component\AkeneoProductValuesPackage\Builder\ManyToManyColorRGBRule;
use Kiboko\Component\AkeneoProductValuesPackage\Builder\ManyToOneColorRGBRule;
use Kiboko\Component\AkeneoProductValues\Composer\RuleCapability as RuleCapabilityInterface;

class RuleCapability implements RuleCapabilityInterface
{
    public function getRules(Composer $composer)
    {
        $root = $composer->getConfig()->get('akeneo-appbundle-root-dir') ?: 'src';
        $vendor = $composer->getConfig()->get('akeneo-appbundle-vendor-name') ?: null;
        $bundle = $composer->getConfig()->get('akeneo-appbundle-bundle-name') ?: 'AppBundle';

        return [
            'color.rgb.many-to-one'   => new ManyToOneColorRGBRule($root, $bundle, $vendor),
            'color.rgb.many-to-many' => new ManyToManyColorRGBRule($root, $bundle, $vendor),
        ];
    }
}
