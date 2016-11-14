<?php

namespace Kiboko\AkeneoProductValuesPackage\Datetime\Builder;

use Composer\Composer;
use Composer\IO\IOInterface;
use Kiboko\Component\AkeneoProductValues\AnnotationGenerator\DoctrineColumnAnnotationGenerator;
use Kiboko\Component\AkeneoProductValues\Builder\BundleBuilder;
use Kiboko\Component\AkeneoProductValues\Builder\RuleInterface;
use Kiboko\Component\AkeneoProductValues\CodeGenerator\DoctrineEntity\DoctrineEntityScalarFieldCodeGenerator;
use Kiboko\Component\AkeneoProductValues\CodeGenerator\DoctrineEntity\DoctrineEntityScalarFieldGetMethodCodeGenerator;
use Kiboko\Component\AkeneoProductValues\CodeGenerator\DoctrineEntity\DoctrineEntityScalarFieldSetMethodCodeGenerator;
use Kiboko\Component\AkeneoProductValues\CodeGenerator\ProductValueCodeGenerator;

class DatetimeSingleRule implements RuleInterface
{
    /**
     * @var string
     */
    private $root;

    /**
     * @var string
     */
    private $bundle;

    /**
     * @var string
     */
    private $vendor;

    /**
     * @var string
     */
    private $defaultField;

    /**
     * @var string
     */
    private $path;

    /**
     * @var string
     */
    private $namespace;

    /**
     * @var string
     */
    private $class;

    /**
     * DatetimeRule constructor.
     * @param string $root
     * @param string $bundle
     * @param string|null $vendor
     * @param string $defaultField
     */
    public function __construct($root, $bundle, $vendor = null, $defaultField = 'datetime')
    {
        $this->root = $root;
        $this->vendor = $vendor;
        $this->bundle = $bundle;
        $this->defaultField = $defaultField;
        $this->class = \DateTimeInterface::class;

        if ($vendor === '') {
            $this->namespace = $this->bundle;
            $this->path = $this->bundle . '/';
        } else {
            $this->namespace = $this->vendor . '\\Bundle\\' . $this->bundle;
            $this->path = $this->vendor . '/Bundle/' . $this->bundle . '/';
        }
    }

    /**
     * @param IOInterface $io
     * @param Composer $composer
     * @return bool
     */
    public function interact(IOInterface $io, Composer $composer)
    {
        $fieldName = $io->askAndValidate(
            [
                'Please enter the field name'
            ],
            function ($value) {
                return preg_match('/[a-z][A-Za-z0-9]*/', $value);
            },
            2,
            $this->defaultField
        );

        return $io->askConfirmation(
            [
                sprintf(
                    'You are about to to add a single reference data of type "%s" in the "%s" field of your Akeneo ProductValue class',
                    $this->namespace === null ? $this->class : ($this->namespace  .'\\'. $this->class),
                    $fieldName
                ),
                'Are you sure ?'
            ]
        );
    }

    public function applyTo(BundleBuilder $builder)
    {
        $productValueClass = new ProductValueCodeGenerator('ProductValue', $this->namespace . '\\Model');

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

        $builder->setFileDefinition('Model/ProductValue.php',
            [
                $productValueClass->getNode()
            ]
        );
    }

    public function getName()
    {
        return 'dateteime.single';
    }

    public function getReferenceClass()
    {
        return \DateTimeInterface::class;
    }
}
