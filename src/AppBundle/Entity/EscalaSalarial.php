<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EscalaSalarial
 *
 * @ORM\Table
 * @ORM\Entity(repositoryClass="AppBundle\Repository\EscalaSalarialRepository")
 */
class EscalaSalarial
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
     * @ORM\Column(type="string", length=20)
     */
    private $escala;

    /**
     * @var string
     *
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    private $salario;

    /**
     * @ORM\OneToMany(targetEntity="Trabajador", mappedBy="escalaSalarial")
     */
    private $trabajadores;

    /**
     * Get __toString()
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getEscala() . ' - ' . number_format($this->getSalario(), 2);
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->trabajadores = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set escala
     *
     * @param string $escala
     *
     * @return EscalaSalarial
     */
    public function setEscala($escala)
    {
        $this->escala = $escala;

        return $this;
    }

    /**
     * Get escala
     *
     * @return string
     */
    public function getEscala()
    {
        return $this->escala;
    }

    /**
     * Set salario
     *
     * @param string $salario
     *
     * @return EscalaSalarial
     */
    public function setSalario($salario)
    {
        $this->salario = $salario;

        return $this;
    }

    /**
     * Get salario
     *
     * @return string
     */
    public function getSalario()
    {
        return $this->salario;
    }

    /**
     * Add trabajadore
     *
     * @param \AppBundle\Entity\Trabajador $trabajadore
     *
     * @return EscalaSalarial
     */
    public function addTrabajadore(\AppBundle\Entity\Trabajador $trabajadore)
    {
        $this->trabajadores[] = $trabajadore;

        return $this;
    }

    /**
     * Remove trabajadore
     *
     * @param \AppBundle\Entity\Trabajador $trabajadore
     */
    public function removeTrabajadore(\AppBundle\Entity\Trabajador $trabajadore)
    {
        $this->trabajadores->removeElement($trabajadore);
    }

    /**
     * Get trabajadores
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTrabajadores()
    {
        return $this->trabajadores;
    }
}
