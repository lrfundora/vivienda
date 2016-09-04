<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Trabajador
 *
 * @ORM\Table
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TrabajadorRepository")
 */
class Trabajador
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
     * @ORM\Column(type="string", length=25)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=50)
     */
    private $apellidos;

    /**
     * @var bool
     *
     * @ORM\Column(name="sexo", type="boolean")
     */
    private $sexo;

    /**
     * @var string
     *
     * @ORM\Column(name="direccion", type="string", length=255)
     */
    private $direccion;

    /**
     * @ORM\OneToOne(targetEntity="Foto", orphanRemoval=true)
     * @ORM\JoinColumn(name="foto", referencedColumnName="id")
     */
    private $foto;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fechaAlta", type="datetime")
     * @Gedmo\Timestampable(on="create")
     */
    private $fechaAlta;

    /**
     * @var bool
     *
     * @ORM\Column(name="isBaja", type="boolean")
     */
    private $isBaja;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fechaBaja", type="datetime")
     * @Gedmo\Timestampable(on="create")
     */
    private $fechaBaja;

    /**
     * @var string
     *
     * @ORM\Column(name="motivoBaja", type="string", length=1000)
     */
    private $motivoBaja;

    /**
     * @ORM\ManyToOne(targetEntity="Cargo", inversedBy="trabajadores")
     * @ORM\OrderBy({"nombre"="ASC"})
     */
    private $cargo;

    /**
     * @ORM\ManyToOne(targetEntity="Categoria", inversedBy="trabajadores")
     * @ORM\OrderBy({"nombre"="ASC"})
     */
    private $categoria;

    /**
     * @ORM\ManyToOne(targetEntity="Contrato", inversedBy="trabajadores")
     * @ORM\OrderBy({"tipo"="ASC"})
     */
    private $contrato;

    /**
     * @ORM\ManyToOne(targetEntity="Entidad", inversedBy="trabajadores")
     * @ORM\OrderBy({"nombre"="ASC"})
     */
    private $entidad;

    /**
     * @ORM\ManyToOne(targetEntity="EscalaSalarial", inversedBy="trabajadores")
     * @ORM\OrderBy({"escala"="ASC"})
     */
    private $escalaSalarial;

    /**
     * @ORM\ManyToOne(targetEntity="Escolaridad", inversedBy="trabajadores")
     * @ORM\OrderBy({"nivel"="ASC"})
     */
    private $escolaridad;

    /**
     * @ORM\ManyToMany(targetEntity="Integracion", mappedBy="trabajadores")
     * @ORM\OrderBy({"nombre"="ASC"})
     */
    private $integraciones;

    /**
     * @ORM\OneToMany(targetEntity="Tramite", mappedBy="trabajador")
     * @ORM\OrderBy({"fechaEntrada"="ASC"})
     */
    private $tramites;

    /**
     * @ORM\OneToOne(targetEntity="Usuario", inversedBy="trabajador")
     * @ORM\JoinColumn(name="usuario_id", referencedColumnName="id")
     */
    private $usuario;

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
     * Get sexo
     *
     * @return string
     */
    public function getSexoString()
    {
        return $this->sexo ? 'Masculino' : 'Femenino';
    }

    /**
     * Get __toString()
     *
     * @return string
     */
    public function __toString()
    {
        $sexo = $this->getSexo() ? 'Masculino' : 'Femenino';
        $cargo = $this->getCargo() ? $this->getCargo()->__toString() : '';
        $categoria = $this->getCategoria() ? $this->getCategoria()->__toString() : '';
        $contrato = $this->getContrato() ? $this->getContrato()->__toString() : '';
        $entidad = $this->getEntidad() ? $this->getEntidad()->__toString() : '';
        $escala = $this->getEscalaSalarial() ? $this->getEscalaSalarial()->__toString() : '';
        $escolaridad = $this->getEscolaridad() ? $this->getEscolaridad()->__toString() : '';
        $intgracion = '';
        foreach ($this->getIntegraciones() as $int) {
            if ($intgracion == '') {
                $intgracion = $int->getNombre();
            } else {
                $intgracion .= ', ' . $int->getNombre();
            }

        }

        return $this->getCarnet() . ', ' .
        $this->getNombreCompleto() . ', ' .
        $this->getDireccion() . ', ' .
        $sexo . ', ' .
        $cargo . ', ' .
        $categoria . ', ' .
        $contrato . ', ' .
        $entidad . ', ' .
        $escala . ', ' .
        $escolaridad . ', ' .
        $intgracion;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->integraciones = new \Doctrine\Common\Collections\ArrayCollection();
        $this->tramites = new \Doctrine\Common\Collections\ArrayCollection();
        $this->isBaja = false;
        $this->motivoBaja = '';
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
     * @return Trabajador
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
     * @return Trabajador
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
     * @return Trabajador
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
     * Set sexo
     *
     * @param boolean $sexo
     *
     * @return Trabajador
     */
    public function setSexo($sexo)
    {
        $this->sexo = $sexo;

        return $this;
    }

    /**
     * Get sexo
     *
     * @return boolean
     */
    public function getSexo()
    {
        return $this->sexo;
    }

    /**
     * Set direccion
     *
     * @param string $direccion
     *
     * @return Trabajador
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
     * Set fechaAlta
     *
     * @param \DateTime $fechaAlta
     *
     * @return Trabajador
     */
    public function setFechaAlta($fechaAlta)
    {
        $this->fechaAlta = $fechaAlta;

        return $this;
    }

    /**
     * Get fechaAlta
     *
     * @return \DateTime
     */
    public function getFechaAlta()
    {
        return $this->fechaAlta;
    }

    /**
     * Set isBaja
     *
     * @param boolean $isBaja
     *
     * @return Trabajador
     */
    public function setIsBaja($isBaja)
    {
        $this->isBaja = $isBaja;

        return $this;
    }

    /**
     * Get isBaja
     *
     * @return boolean
     */
    public function getIsBaja()
    {
        return $this->isBaja;
    }

    /**
     * Set fechaBaja
     *
     * @param \DateTime $fechaBaja
     *
     * @return Trabajador
     */
    public function setFechaBaja($fechaBaja)
    {
        $this->fechaBaja = $fechaBaja;

        return $this;
    }

    /**
     * Get fechaBaja
     *
     * @return \DateTime
     */
    public function getFechaBaja()
    {
        return $this->fechaBaja;
    }

    /**
     * Set motivoBaja
     *
     * @param string $motivoBaja
     *
     * @return Trabajador
     */
    public function setMotivoBaja($motivoBaja)
    {
        $this->motivoBaja = $motivoBaja;

        return $this;
    }

    /**
     * Get motivoBaja
     *
     * @return string
     */
    public function getMotivoBaja()
    {
        return $this->motivoBaja;
    }

    /**
     * Set foto
     *
     * @param \AppBundle\Entity\Foto $foto
     *
     * @return Trabajador
     */
    public function setFoto(\AppBundle\Entity\Foto $foto = null)
    {
        $this->foto = $foto;

        return $this;
    }

    /**
     * Get foto
     *
     * @return \AppBundle\Entity\Foto
     */
    public function getFoto()
    {
        return $this->foto;
    }

    /**
     * Set cargo
     *
     * @param \AppBundle\Entity\Cargo $cargo
     *
     * @return Trabajador
     */
    public function setCargo(\AppBundle\Entity\Cargo $cargo = null)
    {
        $this->cargo = $cargo;

        return $this;
    }

    /**
     * Get cargo
     *
     * @return \AppBundle\Entity\Cargo
     */
    public function getCargo()
    {
        return $this->cargo;
    }

    /**
     * Set categoria
     *
     * @param \AppBundle\Entity\Categoria $categoria
     *
     * @return Trabajador
     */
    public function setCategoria(\AppBundle\Entity\Categoria $categoria = null)
    {
        $this->categoria = $categoria;

        return $this;
    }

    /**
     * Get categoria
     *
     * @return \AppBundle\Entity\Categoria
     */
    public function getCategoria()
    {
        return $this->categoria;
    }

    /**
     * Set contrato
     *
     * @param \AppBundle\Entity\Contrato $contrato
     *
     * @return Trabajador
     */
    public function setContrato(\AppBundle\Entity\Contrato $contrato = null)
    {
        $this->contrato = $contrato;

        return $this;
    }

    /**
     * Get contrato
     *
     * @return \AppBundle\Entity\Contrato
     */
    public function getContrato()
    {
        return $this->contrato;
    }

    /**
     * Set entidad
     *
     * @param \AppBundle\Entity\Entidad $entidad
     *
     * @return Trabajador
     */
    public function setEntidad(\AppBundle\Entity\Entidad $entidad = null)
    {
        $this->entidad = $entidad;

        return $this;
    }

    /**
     * Get entidad
     *
     * @return \AppBundle\Entity\Entidad
     */
    public function getEntidad()
    {
        return $this->entidad;
    }

    /**
     * Set escalaSalarial
     *
     * @param \AppBundle\Entity\EscalaSalarial $escalaSalarial
     *
     * @return Trabajador
     */
    public function setEscalaSalarial(\AppBundle\Entity\EscalaSalarial $escalaSalarial = null)
    {
        $this->escalaSalarial = $escalaSalarial;

        return $this;
    }

    /**
     * Get escalaSalarial
     *
     * @return \AppBundle\Entity\EscalaSalarial
     */
    public function getEscalaSalarial()
    {
        return $this->escalaSalarial;
    }

    /**
     * Set escolaridad
     *
     * @param \AppBundle\Entity\Escolaridad $escolaridad
     *
     * @return Trabajador
     */
    public function setEscolaridad(\AppBundle\Entity\Escolaridad $escolaridad = null)
    {
        $this->escolaridad = $escolaridad;

        return $this;
    }

    /**
     * Get escolaridad
     *
     * @return \AppBundle\Entity\Escolaridad
     */
    public function getEscolaridad()
    {
        return $this->escolaridad;
    }

    /**
     * Add integracion
     *
     * @param \AppBundle\Entity\Integracion $integracion
     *
     * @return Trabajador
     */
    public function addIntegracion(\AppBundle\Entity\Integracion $integracion)
    {
        $integracion->addTrabajador($this);
        $this->integraciones[] = $integracion;

        return $this;
    }

    /**
     * Remove integracion
     *
     * @param \AppBundle\Entity\Integracion $integracion
     */
    public function removeIntegracion(\AppBundle\Entity\Integracion $integracion)
    {
        $integracion->removeTrabajador($this);
        $this->integraciones->removeElement($integracion);
    }

    /**
     * Get integraciones
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getIntegraciones()
    {
        return $this->integraciones;
    }

    /**
     * Add tramite
     *
     * @param \AppBundle\Entity\Tramite $tramite
     *
     * @return Trabajador
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

    /**
     * Set usuario
     *
     * @param \AppBundle\Entity\Usuario $usuario
     *
     * @return Trabajador
     */
    public function setUsuario(\AppBundle\Entity\Usuario $usuario = null)
    {
        $this->usuario = $usuario;

        return $this;
    }

    /**
     * Get usuario
     *
     * @return \AppBundle\Entity\Usuario
     */
    public function getUsuario()
    {
        return $this->usuario;
    }
}
