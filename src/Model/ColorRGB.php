<?php

namespace Kiboko\Component\AkeneoProductValuesPackage\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class ColorRGB
 *
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class ColorRGB extends Color
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
     * @var int
     * @ORM\Column(type="smallint")
     */
    private $red;

    /**
     * @var int
     * @ORM\Column(type="smallint")
     */
    private $green;

    /**
     * @var int
     * @ORM\Column(type="smallint")
     */
    private $blue;

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
     * @return int
     */
    public function getRed()
    {
        return $this->red;
    }

    /**
     * @param int $red
     */
    public function setRed($red)
    {
        $this->red = max(0, $red % 256);
    }

    /**
     * @return int
     */
    public function getGreen()
    {
        return $this->green;
    }

    /**
     * @param int $green
     */
    public function setGreen($green)
    {
        $this->green = max(0, $green % 256);
    }

    /**
     * @return int
     */
    public function getBlue()
    {
        return $this->blue;
    }

    /**
     * @param int $blue
     */
    public function setBlue($blue)
    {
        $this->blue = max(0, $blue % 256);
    }

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return 'color_rgb';
    }

    /**
     * @param int $red
     * @param int $green
     * @param int $blue
     */
    public function set($red, $green, $blue)
    {
        $this->setRed($red);
        $this->setGreen($green);
        $this->setBlue($blue);
    }
}
