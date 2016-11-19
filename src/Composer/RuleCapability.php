<?php

namespace Kiboko\Component\AkeneoProductValuesPackage\Composer;

use Composer\Composer;
use Kiboko\Component\AkeneoProductValuesPackage\Builder\DatetimeMultipleRule;
use Kiboko\Component\AkeneoProductValuesPackage\Builder\DatetimeSingleRule;
use Kiboko\Component\AkeneoProductValues\Composer\RuleCapability as RuleCapabilityInterface;

class RuleCapability implements RuleCapabilityInterface
{
    public function getRules(Composer $composer)
    {
        $root = $composer->getConfig()->get('akeneo-appbundle-root-dir') ?: 'src';
        $vendor = $composer->getConfig()->get('akeneo-appbundle-vendor-name') ?: null;
        $bundle = $composer->getConfig()->get('akeneo-appbundle-bundle-name') ?: 'AppBundle';

        return [
            'dateteime.single' => new DatetimeSingleRule($root, $bundle, $vendor),
            'dateteime.multiple' => new DatetimeMultipleRule($root, $bundle, $vendor),
        ];
    }
}
