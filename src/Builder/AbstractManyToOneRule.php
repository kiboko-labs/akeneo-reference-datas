<?php

namespace Kiboko\Component\AkeneoProductValuesPackage\Builder;

use Kiboko\Component\AkeneoProductValues\AnnotationGenerator\Doctrine\DoctrineJoinColumnAnnotationGenerator;
use Kiboko\Component\AkeneoProductValues\AnnotationGenerator\Doctrine\DoctrineManyToOneAnnotationGenerator;
use Kiboko\Component\AkeneoProductValues\Builder\BundleBuilder;
use Kiboko\Component\AkeneoProductValues\CodeGenerator\DoctrineEntity\DoctrineEntityReferenceFieldCodeGenerator;
use Kiboko\Component\AkeneoProductValues\CodeGenerator\DoctrineEntity\DoctrineEntityReferenceFieldGetMethodCodeGenerator;
use Kiboko\Component\AkeneoProductValues\CodeGenerator\DoctrineEntity\DoctrineEntityReferenceFieldSetMethodCodeGenerator;
use Kiboko\Component\AkeneoProductValues\CodeGenerator\ProductValueCodeGenerator;
use Kiboko\Component\AkeneoProductValues\Visitor\CodeGeneratorApplierVisitor;
use Kiboko\Component\AkeneoProductValuesPackage\Helper;

abstract class AbstractManyToOneRule implements ManyToOneReferenceRuleInterface
{
    /**
     * Entity type class
     *
     * @var string
     */
    private $referenceClass;

    /**
     * @var string
     */
    private $path;

    /**
     * @var string
     */
    private $namespace;

    /**
     * The default field name
     *
     * @var string
     */
    private $defaultField;

    /**
     * The actual field name (after user interaction)
     *
     * @var string
     */
    private $fieldName;

    /**
     * DatetimeRule constructor.
     *
     * @param string $bundleName
     * @param string $referenceClass
     * @param string|null $vendorName
     * @param string $defaultField
     */
    public function __construct(
        $bundleName,
        $referenceClass,
        $vendorName = null,
        $defaultField = null
    ) {
        $this->referenceClass = $referenceClass;
        $this->defaultField = $defaultField;

        if ($vendorName === '') {
            $this->namespace = $bundleName;
            $this->path = $bundleName . '/';
        } else {
            $this->namespace = $vendorName . '\\Bundle\\' . $bundleName;
            $this->path = $vendorName . '/Bundle/' . $bundleName . '/';
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
    public function getProductValueClass()
    {
        return $this->namespace . '\\Entity\\ProductValue';
    }

    public function applyTo(BundleBuilder $builder)
    {
        $builder->ensureClassExists(
            'Entity/ProductValue.php',
            $this->getProductValueClass(),
            new ProductValueCodeGenerator(
                ...Helper\ClassName::extractClassAndNamespace($this->getProductValueClass())
            )
        );

        $visitor = new CodeGeneratorApplierVisitor();

        list($className, $namespace) = Helper\ClassName::extractClassAndNamespace($this->getProductValueClass());
        $visitor->appendPropertyCodeGenerator(
            new DoctrineEntityReferenceFieldCodeGenerator(
                $this->getFieldName(),
                $className,
                $namespace,
                [
                    new DoctrineManyToOneAnnotationGenerator(
                        [
                            'targetEntity' => $this->referenceClass
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
                ...Helper\ClassName::extractClassAndNamespace($this->referenceClass)
            )
        );

        $visitor->appendMethodCodeGenerator(
            new DoctrineEntityReferenceFieldSetMethodCodeGenerator(
                $this->getFieldName(),
                ...Helper\ClassName::extractClassAndNamespace($this->referenceClass)
            )
        );

        $builder->visitClassDefinition(
            $this->getProductValueClass(),
            [
                $visitor
            ]
        );
    }

    /**
     * @param string $referenceClass
     */
    protected function setReferenceClass($referenceClass)
    {
        $this->referenceClass = $referenceClass;
    }

    /**
     * @return string
     */
    public function getReferenceClass()
    {
        return $this->referenceClass;
    }

    /**
     * @return string
     */
    public function getDefaultField()
    {
        return $this->defaultField;
    }

    public function getRuleName()
    {
        return static::getName();
    }
}
