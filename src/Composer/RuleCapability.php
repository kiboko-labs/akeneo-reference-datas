<?php

namespace Kiboko\AkeneoProductValuesPackage\Datetime\Composer;

use Composer\Composer;
use Kiboko\AkeneoProductValuesPackage\Datetime\Builder\DatetimeMultipleRule;
use Kiboko\AkeneoProductValuesPackage\Datetime\Builder\DatetimeSingleRule;
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
