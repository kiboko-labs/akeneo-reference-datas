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
class ColorRGB extends AbstractReferenceData
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
    public function getRed()
    {
        return $this->red;
    }

    /**
     * @param int $red
     */
    public function setRed($red)
    {
        $this->red = $red;
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
        $this->green = $green;
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
        $this->blue = $blue;
    }

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return 'colorrgb';
    }

    /**
     * @return string
     */
    public function asHexCode()
    {
        return sprintf('#%X%X%X', $this->getRed(), $this->getGreen(), $this->getBlue());
    }

    /**
     * @param string $hexCode
     */
    public function fromHexCode($hexCode)
    {
        switch (strlen($hexCode)) {
            case 6:
                sscanf($hexCode, '#%2X%2X%2X', $this->red, $this->green, $this->blue);
                break;

            case 3:
                sscanf($hexCode, '#%1X%1X%1X', $this->red, $this->green, $this->blue);

                $this->red |= ($this->red << 4);
                $this->green |= ($this->green << 4);
                $this->blue |= ($this->blue << 4);
                break;

            case 2:
                sscanf($hexCode, '#%2X', $this->blue);

                $this->red = $this->green = $this->blue;
                break;
        }
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
