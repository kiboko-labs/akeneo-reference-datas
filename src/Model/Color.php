<?php


namespace Kiboko\Component\AkeneoProductValuesPackage\Model;

class Color
{
    /**
     * @var string
     * @ORM\Column(type="binary", length=4)
     */
    private $color;

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
