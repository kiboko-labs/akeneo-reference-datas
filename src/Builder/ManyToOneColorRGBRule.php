<?php

namespace Kiboko\Component\AkeneoProductValuesPackage\Builder;

use Composer\Composer;
use Kiboko\Component\AkeneoProductValues\AnnotationGenerator\DoctrineJoinColumnAnnotationGenerator;
use Kiboko\Component\AkeneoProductValues\AnnotationGenerator\DoctrineManyToOneAnnotationGenerator;
use Kiboko\Component\AkeneoProductValues\Builder\BundleBuilder;
use Kiboko\Component\AkeneoProductValues\Builder\RuleInterface;
use Kiboko\Component\AkeneoProductValues\CodeGenerator\DoctrineEntity\DoctrineEntityReferenceFieldCodeGenerator;
use Kiboko\Component\AkeneoProductValues\CodeGenerator\DoctrineEntity\DoctrineEntityReferenceFieldGetMethodCodeGenerator;
use Kiboko\Component\AkeneoProductValues\CodeGenerator\DoctrineEntity\DoctrineEntityReferenceFieldSetMethodCodeGenerator;
use Kiboko\Component\AkeneoProductValues\CodeGenerator\ProductValueCodeGenerator;
use Kiboko\Component\AkeneoProductValues\Visitor\CodeGeneratorApplierVisitor;
use Kiboko\Component\AkeneoProductValuesPackage\Model\ColorRGB;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;

class ManyToOneColorRGBRule implements RuleInterface
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
    public function __construct($root, $bundle, $vendor = null, $defaultField = 'colorRgb')
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

        if ($this->fieldName === null) {
            $fieldNameQuestion = new Question(
                sprintf(
                    'Please enter the field name: [<info>%s</info>]',
                    $this->defaultField
                ),
                $this->defaultField
            );
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
            'You are about to to add a many-to-one reference data of type "%s" in the "%s" field of your Akeneo ProductValue class [<info>yes</info>]',
            $this->namespace === null ? $this->class : ($this->namespace  .'\\'. $this->class),
            $this->fieldName
        ));

        return $helper->ask($input, $output, $confirmation);
    }

    public function applyTo(BundleBuilder $builder)
    {
        $builder->ensureClassExists(
            'Entity/ProductValue.php',
            $this->namespace.'\\Entity\\ProductValue',
            new ProductValueCodeGenerator('ProductValue', $this->namespace.'\\Entity')
        );

        $visitor = new CodeGeneratorApplierVisitor();

        $visitor->appendPropertyCodeGenerator(
            new DoctrineEntityReferenceFieldCodeGenerator(
                $this->getFieldName(),
                'ColorRGB',
                'Kiboko\\Component\\AkeneoProductValuesPackage\\Model',
                [
                    new DoctrineManyToOneAnnotationGenerator(
                        [
                            'targetEntity' => 'Kiboko\\Component\\AkeneoProductValuesPackage\\Model\\ColorRGB'
                        ]
                    ),
                    new DoctrineJoinColumnAnnotationGenerator(
                        [
                            'name' => $this->fieldName,
                            'referencedColumnName' => 'id'
                        ]
                    ),
                ]
            )
        );

        $visitor->appendMethodCodeGenerator(
            new DoctrineEntityReferenceFieldGetMethodCodeGenerator(
                $this->getFieldName(),
                'ColorRGB',
                'Kiboko\\Component\\AkeneoProductValuesPackage\\Model'
            )
        );

        $visitor->appendMethodCodeGenerator(
            new DoctrineEntityReferenceFieldSetMethodCodeGenerator(
                $this->getFieldName(),
                'ColorRGB',
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
        return 'color.rgb.many-to-one';
    }

    /**
     * @return string
     */
    public function getReferenceClass()
    {
        return ColorRGB::class;
    }
}
