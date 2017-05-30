<?php

namespace Kiboko\Component\AkeneoProductValuesPackage\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class ColorHSL
 *
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class ColorHSL extends Color
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
     * @ORM\Column(type="mediumint")
     */
    private $hue;

    /**
     * @var int
     * @ORM\Column(type="smallint")
     */
    private $saturation;

    /**
     * @var int
     * @ORM\Column(type="smallint")
     */
    private $lightness;

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
    public function getHue()
    {
        return $this->hue;
    }

    /**
     * @param int $hue
     */
    public function setHue($hue)
    {
        $this->hue = max(0, $hue % 360);
    }

    /**
     * @return int
     */
    public function getSaturation()
    {
        return $this->saturation;
    }

    /**
     * @param int $saturation
     */
    public function setSaturation($saturation)
    {
        $this->saturation = max(0, $saturation % 100);
    }

    /**
     * @return int
     */
    public function getLightness()
    {
        return $this->lightness;
    }

    /**
     * @param int $lightness
     */
    public function setLightness($lightness)
    {
        $this->lightness = max(0, $lightness % 100);
    }

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return 'color_hsl';
    }

    /**
     * @param int $hue
     * @param int $saturation
     * @param int $lightness
     */
    public function set($hue, $saturation, $lightness)
    {
        $this->setHue($hue);
        $this->setSaturation($saturation);
        $this->setLightness($lightness);
    }
}
