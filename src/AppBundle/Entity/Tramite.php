<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Tramite
 *
 * @ORM\Table
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TramiteRepository")
 */
class Tramite
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
     * @var string
     *
     * @ORM\Column(type="string", length=1000)
     */
    private $descripcion;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="date")
     */
    private $fechaEntrada;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="date")
     */
    private $fechaVencimiento;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=1000)
     */
    private $comentario;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    private $isCompletado;

    /**
     * @ORM\ManyToOne(targetEntity="Trabajador", inversedBy="tramites")
     */
    private $trabajador;

    /**
     * @ORM\ManyToOne(targetEntity="Cliente", inversedBy="tramites")
     */
    private $cliente;

    /**
     * Get __toString()
     *
     * @return string
     */
    public function __toString()
    {
        $abogado = $this->getTrabajador() ? $this->getTrabajador()->getId() : '';
        $cliente = $this->getCliente() ? $this->getCliente()->getId() : '';

        return date_format($this->getFechaEntrada(), 'd/m/Y') . ', ' .
        date_format($this->getFechaVencimiento(), 'd/m/Y') . ', ' .
        $this->getDescripcion() . ', ' .
        $this->getComentario() . ', ' .
        $this->getIsCompletado() . ', ' .
        $abogado . ', ' .
        $cliente;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->comentario = '';
        $this->isCompletado = false;
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
     * Set descripcion
     *
     * @param string $descripcion
     *
     * @return Tramite
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get descripcion
     *
     * @return string
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Set fechaEntrada
     *
     * @param \DateTime $fechaEntrada
     *
     * @return Tramite
     */
    public function setFechaEntrada($fechaEntrada)
    {
        $this->fechaEntrada = $fechaEntrada;

        return $this;
    }

    /**
     * Get fechaEntrada
     *
     * @return \DateTime
     */
    public function getFechaEntrada()
    {
        return $this->fechaEntrada;
    }

    /**
     * Set fechaVencimiento
     *
     * @param \DateTime $fechaVencimiento
     *
     * @return Tramite
     */
    public function setFechaVencimiento($fechaVencimiento)
    {
        $this->fechaVencimiento = $fechaVencimiento;

        return $this;
    }

    /**
     * Get fechaVencimiento
     *
     * @return \DateTime
     */
    public function getFechaVencimiento()
    {
        return $this->fechaVencimiento;
    }

    /**
     * Set descripcion
     *
     * @param string $comentario
     *
     * @return Tramite
     */
    public function setComentario($comentario)
    {
        $this->comentario = $comentario;

        return $this;
    }

    /**
     * Get comentario
     *
     * @return string
     */
    public function getComentario()
    {
        return $this->comentario;
    }

    /**
     * Set isCompletado
     *
     * @param boolean $isCompletado
     *
     * @return Tramite
     */
    public function setIsCompletado($isCompletado)
    {
        $this->isCompletado = $isCompletado;

        return $this;
    }

    /**
     * Get isCompletado
     *
     * @return boolean
     */
    public function getIsCompletado()
    {
        return $this->isCompletado;
    }

    /**
     * Set trabajador
     *
     * @param \AppBundle\Entity\Trabajador $trabajador
     *
     * @return Tramite
     */
    public function setTrabajador(\AppBundle\Entity\Trabajador $trabajador = null)
    {
        $this->trabajador = $trabajador;

        return $this;
    }

    /**
     * Get trabajador
     *
     * @return \AppBundle\Entity\Trabajador
     */
    public function getTrabajador()
    {
        return $this->trabajador;
    }

    /**
     * Set cliente
     *
     * @param \AppBundle\Entity\Cliente $cliente
     *
     * @return Tramite
     */
    public function setCliente(\AppBundle\Entity\Cliente $cliente = null)
    {
        $this->cliente = $cliente;

        return $this;
    }

    /**
     * Get cliente
     *
     * @return \AppBundle\Entity\Cliente
     */
    public function getCliente()
    {
        return $this->cliente;
    }
}
