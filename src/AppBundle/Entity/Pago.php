<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Pago
 *
 * @ORM\Table
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PagoRepository")
 */
class Pago
{
    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", length=4)
     */
    private $ano;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", length=4)
     */
    private $mes;

    /**
     * @var int
     *
     * @ORM\Column(type="boolean")
     */
    private $pagado;

    /**
     * @ORM\ManyToOne(targetEntity="Arrendamiento", inversedBy="pagos")
     */
    private $arrendamiento;

    /**
     * Construct
     */
    public function __construct()
    {
        $this->pagado = false;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set ano
     *
     * @param integer $ano
     *
     * @return Pago
     */
    public function setAno($ano)
    {
        $this->ano = $ano;

        return $this;
    }

    /**
     * Get ano
     *
     * @return integer
     */
    public function getAno()
    {
        return $this->ano;
    }

    /**
     * Set mes
     *
     * @param integer $mes
     *
     * @return Pago
     */
    public function setMes($mes)
    {
        $this->mes = $mes;

        return $this;
    }

    /**
     * Get mes
     *
     * @return integer
     */
    public function getMes()
    {
        return $this->mes;
    }

    /**
     * Set arrendamiento
     *
     * @param \AppBundle\Entity\Arrendamiento $arrendamiento
     *
     * @return Pago
     */
    public function setArrendamiento(\AppBundle\Entity\Arrendamiento $arrendamiento = null)
    {
        $this->arrendamiento = $arrendamiento;

        return $this;
    }

    /**
     * Get arrendamiento
     *
     * @return \AppBundle\Entity\Arrendamiento
     */
    public function getArrendamiento()
    {
        return $this->arrendamiento;
    }

    /**
     * Set pagado
     *
     * @param boolean $pagado
     *
     * @return Pago
     */
    public function setPagado($pagado)
    {
        $this->pagado = $pagado;

        return $this;
    }

    /**
     * Get pagado
     *
     * @return boolean
     */
    public function getPagado()
    {
        return $this->pagado;
    }
}
