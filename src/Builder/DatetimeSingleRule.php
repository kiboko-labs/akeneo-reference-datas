<?php

namespace Kiboko\Component\AkeneoProductValuesPackage\Builder;

use Composer\Composer;
use Composer\IO\IOInterface;
use Kiboko\Component\AkeneoProductValues\AnnotationGenerator\DoctrineColumnAnnotationGenerator;
use Kiboko\Component\AkeneoProductValues\Builder\BundleBuilder;
use Kiboko\Component\AkeneoProductValues\Builder\RuleInterface;
use Kiboko\Component\AkeneoProductValues\CodeGenerator\DoctrineEntity\DoctrineEntityReferenceFieldCodeGenerator;
use Kiboko\Component\AkeneoProductValues\CodeGenerator\DoctrineEntity\DoctrineEntityReferenceFieldGetMethodCodeGenerator;
use Kiboko\Component\AkeneoProductValues\CodeGenerator\DoctrineEntity\DoctrineEntityReferenceFieldSetMethodCodeGenerator;
use Kiboko\Component\AkeneoProductValues\CodeGenerator\DoctrineEntity\DoctrineEntityScalarFieldCodeGenerator;
use Kiboko\Component\AkeneoProductValues\CodeGenerator\DoctrineEntity\DoctrineEntityScalarFieldGetMethodCodeGenerator;
use Kiboko\Component\AkeneoProductValues\CodeGenerator\DoctrineEntity\DoctrineEntityScalarFieldSetMethodCodeGenerator;
use Kiboko\Component\AkeneoProductValues\CodeGenerator\ProductValueCodeGenerator;
use Kiboko\Component\AkeneoProductValues\Visitor\CodeGeneratorApplierVisitor;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;

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
     * @var string
     */
    private $fieldName;

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
     * @return string
     */
    public function getFieldName()
    {
        return $this->fieldName;
    }

    /**
     * @param string $fieldName
     */
    public function setFieldName($fieldName)
    {
        $this->fieldName = $fieldName;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @param Composer $composer
     * @return bool
     */
    public function interact(InputInterface $input, OutputInterface $output, Composer $composer)
    {
        $helper = new QuestionHelper();

        $fieldNameQuestion = new Question('Please enter the field name', $this->defaultField);
        $fieldNameQuestion->setValidator(function ($value) {
            return preg_match('/[a-z][A-Za-z0-9]*/', $value);
        })->setMaxAttempts(2);

        $helper->ask($input, $output, $fieldNameQuestion);

        $confirmation = new ConfirmationQuestion(sprintf(
            'You are about to to add a single reference data of type "%s" in the "%s" field of your Akeneo ProductValue class',
            $this->namespace === null ? $this->class : ($this->namespace  .'\\'. $this->class),
            $this->fieldName
        ));

        return $helper->ask($input, $output, $confirmation);
    }

    public function applyTo(BundleBuilder $builder)
    {
        $visitor = new CodeGeneratorApplierVisitor();

        $visitor->appendPropertyCodeGenerator(
            new DoctrineEntityReferenceFieldCodeGenerator(
                $this->getFieldName(),
                'Color',
                'Kiboko\\Component\\AkeneoProductValuesPackage\\Model',
                [
                    new DoctrineColumnAnnotationGenerator('datetime'),
                ]
            )
        );

        $visitor->appendMethodCodeGenerator(
            new DoctrineEntityReferenceFieldGetMethodCodeGenerator(
                $this->getFieldName(),
                'Color',
                'Kiboko\\Component\\AkeneoProductValuesPackage\\Model'
            )
        );

        $visitor->appendMethodCodeGenerator(
            new DoctrineEntityReferenceFieldSetMethodCodeGenerator(
                $this->getFieldName(),
                'Color',
                'Kiboko\\Component\\AkeneoProductValuesPackage\\Model'
            )
        );

        $builder->visitClassDefinition(
            $this->namespace . '\\Model\\ProductValue',
            [
                $visitor
            ]
        );
        $productValueClass = new ProductValueCodeGenerator('ProductValue', $this->namespace . '\\Model');

        $builder->mergeClassDefinition(
            'Model/ProductValue.php',
            $this->namespace . '\\Model',
            $productValueClass->getNode()
        );
    }

    public function getName()
    {
        return 'datetime.single';
    }

    public function getReferenceClass()
    {
        return \DateTimeInterface::class;
    }
}
