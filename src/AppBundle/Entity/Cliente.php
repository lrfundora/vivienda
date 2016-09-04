<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Cliente
 *
 * @ORM\Table
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ClienteRepository")
 */
class Cliente
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
     * @ORM\Column(type="decimal", precision=11, scale=0)
     */
    private $carnet;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=25)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="apellidos", type="string", length=50)
     */
    private $apellidos;

    /**
     * @var string
     *
     * @ORM\Column(name="direccion", type="string", length=255)
     */
    private $direccion;

    /**
     * @ORM\ManyToMany(targetEntity="Arrendamiento", mappedBy="clientes", cascade={"persist"})
     */
    private $arrendamientos;

    /**
     * @ORM\OneToMany(targetEntity="Tramite", mappedBy="cliente", cascade={"persist"})
     */
    private $tramites;

    /**
     * Get Nombre y Apellidos
     *
     * @return string
     */
    public function getNombreCompleto()
    {
        return $this->nombre . ' ' . $this->apellidos;
    }

    /**
     * Get __toString()
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getCarnet().', '.
            $this->getNombreCompleto().', '.
            $this->getDireccion();
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->arrendamientos = new \Doctrine\Common\Collections\ArrayCollection();
        $this->tramites = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set carnet
     *
     * @param string $carnet
     *
     * @return Cliente
     */
    public function setCarnet($carnet)
    {
        $this->carnet = $carnet;

        return $this;
    }

    /**
     * Get carnet
     *
     * @return string
     */
    public function getCarnet()
    {
        return $this->carnet;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return Cliente
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set apellidos
     *
     * @param string $apellidos
     *
     * @return Cliente
     */
    public function setApellidos($apellidos)
    {
        $this->apellidos = $apellidos;

        return $this;
    }

    /**
     * Get apellidos
     *
     * @return string
     */
    public function getApellidos()
    {
        return $this->apellidos;
    }

    /**
     * Set direccion
     *
     * @param string $direccion
     *
     * @return Cliente
     */
    public function setDireccion($direccion)
    {
        $this->direccion = $direccion;

        return $this;
    }

    /**
     * Get direccion
     *
     * @return string
     */
    public function getDireccion()
    {
        return $this->direccion;
    }

    /**
     * Add arrendamiento
     *
     * @param \AppBundle\Entity\Arrendamiento $arrendamiento
     *
     * @return Cliente
     */
    public function addArrendamiento(\AppBundle\Entity\Arrendamiento $arrendamiento)
    {
        $arrendamiento->addCliente($this);
        $this->arrendamientos[] = $arrendamiento;

        return $this;
    }

    /**
     * Remove arrendamiento
     *
     * @param \AppBundle\Entity\Arrendamiento $arrendamiento
     */
    public function removeArrendamiento(\AppBundle\Entity\Arrendamiento $arrendamiento)
    {
        $this->arrendamientos->removeElement($arrendamiento);
    }

    /**
     * Get arrendamientos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getArrendamientos()
    {
        return $this->arrendamientos;
    }

    /**
     * Add tramite
     *
     * @param \AppBundle\Entity\Tramite $tramite
     *
     * @return Cliente
     */
    public function addTramite(\AppBundle\Entity\Tramite $tramite)
    {
        $this->tramites[] = $tramite;

        return $this;
    }

    /**
     * Remove tramite
     *
     * @param \AppBundle\Entity\Tramite $tramite
     */
    public function removeTramite(\AppBundle\Entity\Tramite $tramite)
    {
        $this->tramites->removeElement($tramite);
    }

    /**
     * Get tramites
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTramites()
    {
        return $this->tramites;
    }
}
