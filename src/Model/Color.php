<?php


namespace Kiboko\Component\AkeneoProductValuesPackage\Model;

use Doctrine\ORM\Mapping as ORM;
use Pim\Component\ReferenceData\Model\AbstractReferenceData;

/**
 * Class ColorRGB
 *
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Color extends AbstractReferenceData
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
     * @var string
     * @ORM\Column(type="binary", length=4)
     */
    private $color;

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
     * @return int
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * @param int $color
     *
     * @return $this
     */
    public function setColor($color)
    {
        $this->color = $color;
        return $this;
    }
}
