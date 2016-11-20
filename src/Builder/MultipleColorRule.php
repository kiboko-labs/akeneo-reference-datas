<?php

namespace Kiboko\Component\AkeneoProductValuesPackage\Builder;

use Composer\Composer;
use Kiboko\Component\AkeneoProductValues\AnnotationGenerator\DoctrineJoinColumnAnnotationGenerator;
use Kiboko\Component\AkeneoProductValues\AnnotationGenerator\DoctrineJoinTableAnnotationGenerator;
use Kiboko\Component\AkeneoProductValues\AnnotationGenerator\DoctrineManyToManyAnnotationGenerator;
use Kiboko\Component\AkeneoProductValues\Builder\BundleBuilder;
use Kiboko\Component\AkeneoProductValues\Builder\RuleInterface;
use Kiboko\Component\AkeneoProductValues\CodeGenerator\DoctrineEntity\DoctrineEntityReferenceFieldCodeGenerator;
use Kiboko\Component\AkeneoProductValues\CodeGenerator\DoctrineEntity\DoctrineEntityReferenceFieldGetMethodCodeGenerator;
use Kiboko\Component\AkeneoProductValues\CodeGenerator\DoctrineEntity\DoctrineEntityReferenceFieldSetMethodCodeGenerator;
use Kiboko\Component\AkeneoProductValues\CodeGenerator\ProductValueCodeGenerator;
use Kiboko\Component\AkeneoProductValues\Visitor\CodeGeneratorApplierVisitor;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;

class MultipleColorRule implements RuleInterface
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
     * @var string
     */
    private $foreignKey;

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
     * @return string
     */
    public function getForeignKey()
    {
        return $this->foreignKey;
    }

    /**
     * @param string $foreignKey
     */
    public function setForeignKey($foreignKey)
    {
        $this->foreignKey = $foreignKey;
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

        if ($this->fieldName === null) {
            $fieldNameQuestion = new Question('Please enter the field name: ', $this->defaultField);
            $fieldNameQuestion->setValidator(function ($value) {
                if (!preg_match('/^[a-z][A-Za-z0-9]*$/', $value)) {
                    throw new \RuntimeException(
                        'The field name should contain only alphanumeric characters and start with a lowercase letter.'
                    );
                }

                return $value;
            })->setMaxAttempts(2);

            $this->fieldName = $helper->ask($input, $output, $fieldNameQuestion);
        }

        $confirmation = new ConfirmationQuestion(sprintf(
            'You are about to to add a single reference data of type "%s" in the "%s" field of your Akeneo ProductValue class [<info>yes</info>]',
            $this->namespace === null ? $this->class : ($this->namespace  .'\\'. $this->class),
            $this->fieldName
        ));

        return $helper->ask($input, $output, $confirmation);
    }

    public function applyTo(BundleBuilder $builder)
    {
        $builder->ensureClassExists(
            $this->namespace.'\\Entity\\ProductValue',
            'Entity/ProductValue.php',
            new ProductValueCodeGenerator('ProductValue', $this->namespace.'\\Entity')
        );

        $visitor = new CodeGeneratorApplierVisitor();

        $visitor->appendPropertyCodeGenerator(
            new DoctrineEntityReferenceFieldCodeGenerator(
                $this->getFieldName(),
                'Color',
                'Kiboko\\Component\\AkeneoProductValuesPackage\\Model',
                [
                    new DoctrineManyToManyAnnotationGenerator(
                        [
                            'targetEntity' => 'Kiboko\\Component\\AkeneoProductValuesPackage\\Model\\Color'
                        ]
                    ),
                    new DoctrineJoinTableAnnotationGenerator(
                        [
                            'name' => $this->fieldName,
                            'joinColumns' => [
                                new DoctrineJoinColumnAnnotationGenerator(
                                    [
                                        'name' => 'value_id',
                                        'referencedColumnName' => 'id',
                                        'nullable' => true,
                                        'onDelete' => 'CASCADE'
                                    ]
                                ),
                            ],
                            'inverseJoinColumns' => [
                                new DoctrineJoinColumnAnnotationGenerator(
                                    [
                                        'name' => $this->fieldName,
                                        'referencedColumnName' => $this->foreignKey,
                                        'nullable' => false,
                                    ]
                                ),
                            ],
                        ]
                    ),
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
            $this->namespace . '\\Entity\\ProductValue',
            [
                $visitor
            ]
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'color.multiple';
    }

    /**
     * @return string
     */
    public function getReferenceClass()
    {
        return \DateTimeInterface::class;
    }
}
