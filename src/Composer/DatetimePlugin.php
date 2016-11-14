<?php

namespace Kiboko\AkeneoProductValuesPackage\Datetime\Composer;

use Composer\Composer;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\Installer\PackageEvent;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;
use Kiboko\AkeneoProductValuesPackage\Datetime\Builder\DatetimeSingleRule;
use Kiboko\Component\AkeneoProductValues\Composer\ReferenceDataInstaller;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;

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

    /**
     * @var Filesystem
     */
    private $filesystem;

    public function activate(Composer $composer, IOInterface $io)
    {
        $this->composer = $composer;
        $this->io = $io;
        $this->filesystem = new Filesystem(
            new Local(getcwd())
        );
    }

    public static function getSubscribedEvents()
    {
        return [
            'post-package-install' => [
                ['onPostPackageInstall', 0]
            ],
            'post-package-update' => [
                ['onPostPackageInstall', 0]
            ],
        ];
    }

    public function onPostPackageInstall(PackageEvent $event)
    {
        $root = $this->composer->getConfig()->get('akeneo-appbundle-root-dir') ?: 'src';
        $vendor = $this->composer->getConfig()->get('akeneo-appbundle-vendor-name') ?: null;
        $bundle = $this->composer->getConfig()->get('akeneo-appbundle-bundle-name') ?: 'AppBundle';

        /** @var ReferenceDataInstaller $installer */
        $installer = $this->composer->getInstallationManager()->getInstaller('akeneo-reference-data');
        var_dump(get_class($installer));

        $rule = new DatetimeSingleRule($root, $bundle, $vendor, 'datetime');
        $installer->registerRule($rule);
    }
}
