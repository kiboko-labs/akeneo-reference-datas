<?php

namespace Kiboko\AkeneoProductValuesPackage\Datetime\Composer;

use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Plugin\Capable;
use Composer\Plugin\PluginInterface;
use Kiboko\Component\AkeneoProductValues\Composer\RuleCapability as RuleCapabilityInterface;
use League\Flysystem\Filesystem;

class DatetimePlugin implements PluginInterface, Capable
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
     * @var Filesystem
     */
    private $filesystem;

    public function activate(Composer $composer, IOInterface $io)
    {
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
