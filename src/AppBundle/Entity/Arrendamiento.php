<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Arrendamiento
 *
 * @ORM\Table
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ArrendamientoRepository")
 */
class Arrendamiento
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
     * @ORM\Column(type="integer", unique=true)
     */
    private $numeroRegistro;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=50)
     */
    private $resolucion;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=20)
     */
    private $expediente;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $numeroContrato;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="date")
     */
    private $fechaContrato;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=4)
     */
    private $anoConstruccion;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=500)
     */
    private $motivo;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=10)
     */
    private $estado;

    /**
     * @var string
     *
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    private $valor;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    private $isBaja;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="date")
     */
    private $fechaBaja;

    /**
     * @ORM\Column(type="string", length=1000)
     */
    private $comentario;

    /**
     * @ORM\ManyToMany(targetEntity="Cliente", inversedBy="arrendamientos")
     */
    private $clientes;

    /**
     * @ORM\OneToMany(targetEntity="Pago", mappedBy="arrendamiento", cascade={"persist"})
     * @ORM\OrderBy({"ano"="ASC", "mes"="ASC"})
     */
    private $pagos;

    /**
     * Get Meses Pagados
     */
    public function getMesesPagados()
    {
        $meses = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
        $pagados = '';
        $today = date_format(new \DateTime('now'), 'Y');
        foreach ($this->getPagos() as $pago) {
            if ($pago->getPagado() && $pago->getAno() == $today) {
                if ($pagados == '') {
                    $pagados = $meses[$pago->getMes()];
                } else {
                    $pagados .= ', ' . $meses[$pago->getMes()];
                }
            }
        }
        return $pagados;
    }

    /**
     * Get Meses Sin Pagados
     */
    public function getMesesSinPagar()
    {
        $meses = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
        $pagados = '';
        $today = date_format(new \DateTime('now'), 'Y');
        foreach ($this->getPagos() as $pago) {
            if (!$pago->getPagado() && $pago->getAno() == $today) {
                if ($pagados == '') {
                    $pagados = $meses[$pago->getMes()];
                } else {
                    $pagados .= ', ' . $meses[$pago->getMes()];
                }
            }
        }
        return $pagados;
    }

    /**
     * Get boolean
     */
    public function getIsTodoPago()
    {
        $flag = true;
        $year = date_format(new \DateTime('now'), 'Y');
        foreach ($this->getPagos() as $pago) {
            if (!$pago->getPagado() && $pago->getAno() <= $year) {
                $flag = false;
                break;
            }
        }
        return $flag;
    }

    /**
     * Get __toString()
     *
     * @return string
     */
    public function __toString()
    {
        $baja = 'Baja: no';
        if ($this->getIsBaja()) {
            $baja = 'Baja: si, ' . date_format($this->getFechaBaja(), 'd/m/Y') . ', ' . $this->getComentario();
        }
        return $this->getNumeroRegistro() . ', ' .
        $this->getResolucion() . ', ' .
        $this->getExpediente() . ', ' .
        $this->getNumeroContrato() . ', ' .
        date_format($this->getFechaContrato(), 'd/m/Y') . ', ' .
        $this->getAnoConstruccion() . ', ' .
        $this->getEstado() . ', ' .
        $this->getMotivo() . ', ' .
        number_format($this->getValor(), '2', '.', ',') . ', ' .
        $baja;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->clientes = new \Doctrine\Common\Collections\ArrayCollection();
        $this->pagos = new \Doctrine\Common\Collections\ArrayCollection();
        $this->isBaja = false;
        $this->fechaBaja = new \DateTime('now');
        $this->comentario = '';
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
     * Set numeroRegistro
     *
     * @param integer $numeroRegistro
     *
     * @return Arrendamiento
     */
    public function setNumeroRegistro($numeroRegistro)
    {
        $this->numeroRegistro = $numeroRegistro;

        return $this;
    }

    /**
     * Get numeroRegistro
     *
     * @return integer
     */
    public function getNumeroRegistro()
    {
        return $this->numeroRegistro;
    }

    /**
     * Set resolucion
     *
     * @param string $resolucion
     *
     * @return Arrendamiento
     */
    public function setResolucion($resolucion)
    {
        $this->resolucion = $resolucion;

        return $this;
    }

    /**
     * Get resolucion
     *
     * @return string
     */
    public function getResolucion()
    {
        return $this->resolucion;
    }

    /**
     * Set expediente
     *
     * @param string $expediente
     *
     * @return Arrendamiento
     */
    public function setExpediente($expediente)
    {
        $this->expediente = $expediente;

        return $this;
    }

    /**
     * Get expediente
     *
     * @return string
     */
    public function getExpediente()
    {
        return $this->expediente;
    }

    /**
     * Set numeroContrato
     *
     * @param integer $numeroContrato
     *
     * @return Arrendamiento
     */
    public function setNumeroContrato($numeroContrato)
    {
        $this->numeroContrato = $numeroContrato;

        return $this;
    }

    /**
     * Get numeroContrato
     *
     * @return integer
     */
    public function getNumeroContrato()
    {
        return $this->numeroContrato;
    }

    /**
     * Set fechaContrato
     *
     * @param \DateTime $fechaContrato
     *
     * @return Arrendamiento
     */
    public function setFechaContrato($fechaContrato)
    {
        $this->fechaContrato = $fechaContrato;

        return $this;
    }

    /**
     * Get fechaContrato
     *
     * @return \DateTime
     */
    public function getFechaContrato()
    {
        return $this->fechaContrato;
    }

    /**
     * Set anoConstruccion
     *
     * @param string $anoConstruccion
     *
     * @return Arrendamiento
     */
    public function setAnoConstruccion($anoConstruccion)
    {
        $this->anoConstruccion = $anoConstruccion;

        return $this;
    }

    /**
     * Get anoConstruccion
     *
     * @return string
     */
    public function getAnoConstruccion()
    {
        return $this->anoConstruccion;
    }

    /**
     * Set motivo
     *
     * @param string $motivo
     *
     * @return Arrendamiento
     */
    public function setMotivo($motivo)
    {
        $this->motivo = $motivo;

        return $this;
    }

    /**
     * Get motivo
     *
     * @return string
     */
    public function getMotivo()
    {
        return $this->motivo;
    }

    /**
     * Set estado
     *
     * @param string $estado
     *
     * @return Arrendamiento
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;

        return $this;
    }

    /**
     * Get estado
     *
     * @return string
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * Set valor
     *
     * @param string $valor
     *
     * @return Arrendamiento
     */
    public function setValor($valor)
    {
        $this->valor = $valor;

        return $this;
    }

    /**
     * Get valor
     *
     * @return string
     */
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * Set isBaja
     *
     * @param boolean $isBaja
     *
     * @return Arrendamiento
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
     * @return Arrendamiento
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
     * Add cliente
     *
     * @param \AppBundle\Entity\Cliente $cliente
     *
     * @return Arrendamiento
     */
    public function addCliente(\AppBundle\Entity\Cliente $cliente)
    {
        $this->clientes[] = $cliente;

        return $this;
    }

    /**
     * Remove cliente
     *
     * @param \AppBundle\Entity\Cliente $cliente
     */
    public function removeCliente(\AppBundle\Entity\Cliente $cliente)
    {
        $cliente->removeArrendamiento($this);
        $this->clientes->removeElement($cliente);
    }

    /**
     * Get clientes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getClientes()
    {
        return $this->clientes;
    }

    /**
     * Add pago
     *
     * @param \AppBundle\Entity\Pago $pago
     *
     * @return Arrendamiento
     */
    public function addPago(\AppBundle\Entity\Pago $pago)
    {
        $pago->setArrendamiento($this);
        $this->pagos[] = $pago;

        return $this;
    }

    /**
     * Remove pago
     *
     * @param \AppBundle\Entity\Pago $pago
     */
    public function removePago(\AppBundle\Entity\Pago $pago)
    {
        $this->pagos->removeElement($pago);
    }

    /**
     * Get pagos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPagos()
    {
        return $this->pagos;
    }

    /**
     * Set comentario
     *
     * @param string $comentario
     *
     * @return Arrendamiento
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
}
