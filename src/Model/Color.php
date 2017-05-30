<?php


namespace Kiboko\Component\AkeneoProductValuesPackage\Model;

use Doctrine\ORM\Mapping as ORM;
use Pim\Bundle\CustomEntityBundle\Entity\AbstractTranslatableCustomEntity;

/**
 * Class ColorRGB
 *
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
abstract class Color extends AbstractTranslatableCustomEntity implements ColorInterface
{
    /**
     * @param int
     *
     * @ORM\Column(type="string")
     * @ORM\Id
     * @ORM\GeneratedValue()
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getTranslationFQCN()
    {
        return ColorTranslation::class;
    }
}
