<?php

namespace Kiboko\Component\AkeneoProductValuesPackage\Composer;

use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Plugin\Capable;
use Composer\Plugin\PluginInterface;
use Kiboko\Component\AkeneoProductValues\Composer\RuleCapability as RuleCapabilityInterface;

class AkeneoReferenceDataPlugin implements PluginInterface, Capable
{
    /**
     * @var Composer
     */
    protected $composer;

    /**
     * @var IOInterface
     */
    protected $io;

    /**
     * @param Composer $composer
     * @param IOInterface $io
     */
    public function activate(Composer $composer, IOInterface $io)
    {
        // NOOP
    }

    /**
     * @return array
     */
    public function getCapabilities()
    {
        return [
            RuleCapabilityInterface::class => RuleCapability::class
        ];
    }
}
