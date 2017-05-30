<?php

namespace Kiboko\Component\AkeneoProductValuesPackage\Composer;

use Composer\Composer;
use Kiboko\Component\AkeneoProductValuesPackage\Builder\Color\ManyToManyColorRule;
use Kiboko\Component\AkeneoProductValuesPackage\Builder\Color\ManyToOneColorRule;
use Kiboko\Component\AkeneoProductValues\Composer\RuleCapability as RuleCapabilityInterface;

class RuleCapability implements RuleCapabilityInterface
{
    public function getRules(Composer $composer)
    {
        $root = $composer->getConfig()->get('akeneo-appbundle-root-dir') ?: 'src';
        $vendor = $composer->getConfig()->get('akeneo-appbundle-vendor-name') ?: null;
        $bundle = $composer->getConfig()->get('akeneo-appbundle-bundle-name') ?: 'AppBundle';

        return [
            ManyToOneColorRule::NAME . '.' . ManyToOneColorRule::TYPE
                => new ManyToOneColorRule($root, $bundle, $vendor),
            ManyToManyColorRule::NAME . '.' . ManyToManyColorRule::TYPE
                => new ManyToManyColorRule($root, $bundle, $vendor),
        ];
    }
}
