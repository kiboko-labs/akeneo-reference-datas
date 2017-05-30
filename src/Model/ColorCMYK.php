<?php

namespace Kiboko\Component\AkeneoProductValuesPackage\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class ColorHSL
 *
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class ColorCMYK extends Color
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
    private $cyan;

    /**
     * @var int
     * @ORM\Column(type="smallint")
     */
    private $magenta;

    /**
     * @var int
     * @ORM\Column(type="smallint")
     */
    private $yellow;

    /**
     * @var int
     * @ORM\Column(type="smallint")
     */
    private $key;

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
    public function getCyan()
    {
        return $this->cyan;
    }

    /**
     * @param int $cyan
     *
     * @return $this
     */
    public function setCyan($cyan)
    {
        $this->cyan = $cyan;
        return $this;
    }

    /**
     * @return int
     */
    public function getMagenta()
    {
        return $this->magenta;
    }

    /**
     * @param int $magenta
     *
     * @return $this
     */
    public function setMagenta($magenta)
    {
        $this->magenta = $magenta;
        return $this;
    }

    /**
     * @return int
     */
    public function getYellow()
    {
        return $this->yellow;
    }

    /**
     * @param int $yellow
     *
     * @return $this
     */
    public function setYellow($yellow)
    {
        $this->yellow = $yellow;
        return $this;
    }

    /**
     * @return int
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param int $key
     *
     * @return $this
     */
    public function setKey($key)
    {
        $this->key = $key;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return 'color_cmyk';
    }

    /**
     * @param int $cyan
     * @param int $magenta
     * @param int $yellow
     * @param int $key
     */
    public function set($cyan, $magenta, $yellow, $key)
    {
        $this->setCyan($cyan);
        $this->setMagenta($magenta);
        $this->setYellow($yellow);
        $this->setKey($key);
    }
}
