<?php

namespace Kiboko\AkeneoProductValuesPackage\Datetime\Composer;

use Composer\Composer;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\Installer\PackageEvent;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginEvents;
use Composer\Plugin\PluginInterface;

class DatetimePlugin implements PluginInterface, EventSubscriberInterface
{
    /**
     * @var Composer
     */
    protected $composer;

    /**
     * @var IOInterface
     */
    protected $io;

    public function activate(Composer $composer, IOInterface $io)
    {
        $this->composer = $composer;
        $this->io = $io;
    }

    public static function getSubscribedEvents()
    {
        return [
            'post-package-install' => [
                ['onPostPackageInstall', 0]
            ],
        ];
    }

    public function onPostPackageInstall(PackageEvent $event)
    {
        var_dump($this->composer->getConfig()->all());
    }
}
