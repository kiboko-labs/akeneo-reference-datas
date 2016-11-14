<?php

namespace Kiboko\AkeneoProductValuesPackage\Datetime\Composer;

use Composer\Composer;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\Installer\PackageEvent;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginEvents;
use Composer\Plugin\PluginInterface;
use Kiboko\Component\AkeneoProductValues\AnnotationGenerator\DoctrineColumnAnnotationGenerator;
use Kiboko\Component\AkeneoProductValues\Builder\BundleBuilder;
use Kiboko\Component\AkeneoProductValues\CodeGenerator\DoctrineEntity\DoctrineEntityReferenceFieldCodeGenerator;
use Kiboko\Component\AkeneoProductValues\CodeGenerator\DoctrineEntity\DoctrineEntityScalarFieldCodeGenerator;
use Kiboko\Component\AkeneoProductValues\CodeGenerator\DoctrineEntity\DoctrineEntityScalarFieldGetMethodCodeGenerator;
use Kiboko\Component\AkeneoProductValues\CodeGenerator\DoctrineEntity\DoctrineEntityScalarFieldSetMethodCodeGenerator;
use Kiboko\Component\AkeneoProductValues\CodeGenerator\ProductValueCodeGenerator;
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
        ];
    }

    public function onPostPackageInstall(PackageEvent $event)
    {
        $root = $this->composer->getConfig()->get('akeneo-appbundle-root-dir') ?: 'src';
        $vendor = $this->composer->getConfig()->get('akeneo-appbundle-vendor-name');
        $bundle = $this->composer->getConfig()->get('akeneo-appbundle-bundle-name') ?: 'AppBundle';

        if ($vendor === '') {
            $namespace = $bundle;
            $path = $bundle . '/';
        } else {
            $namespace = $vendor . '\\Bundle\\' . $bundle;
            $path = $vendor . '/Bundle/' . $bundle . '/';
        }

        $productValueClass = new ProductValueCodeGenerator('ProductValue', $namespace . '\\Model');

        $productValueClass->addInternalField(
            (new DoctrineEntityScalarFieldCodeGenerator('created', 'DateTimeInterface', [
                new DoctrineColumnAnnotationGenerator('datetime'),
            ]))
        );

        $productValueClass->addMethod(
            (new DoctrineEntityScalarFieldGetMethodCodeGenerator('created', 'DateTimeInterface'))
        );

        $productValueClass->addMethod(
            (new DoctrineEntityScalarFieldSetMethodCodeGenerator('created', 'DateTimeInterface'))
        );

        $productValueClass->addUseStatement('DateTimeInterface');

        $builder = new BundleBuilder();
        $builder->setFileDefinition('Model/ProductValue.php',
            [
                $productValueClass->getNode()
            ]
        );

        $builder->initialize($this->filesystem, $root . '/' . $path);
        $builder->generate($this->filesystem, $root . '/' . $path);
    }
}
