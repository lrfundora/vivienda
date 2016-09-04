<?php

namespace AppBundle\Form\Type;

use AppBundle\Form\DataTransformer\IdToCargoTransformer;
use AppBundle\Form\DataTransformer\IdToCategoriaTransformer;
use AppBundle\Form\DataTransformer\IdToContratoTransformer;
use AppBundle\Form\DataTransformer\IdToEntidadTransformer;
use AppBundle\Form\DataTransformer\IdToEscalaSalarialTransformer;
use AppBundle\Form\DataTransformer\IdToEscolaridadTransformer;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class TrabajadorType extends AbstractType
{
    private $em;
    private $cargos;
    private $categorias;
    private $contratos;
    private $entidades;
    private $escalas;
    private $niveles;

    public function __construct(ObjectManager $manager)
    {
        $this->em = $manager;

        $this->cargos[''] = '';
        $query = $this->em->getRepository('AppBundle:Cargo')->findAll();
        foreach ($query as $item) {
            $this->cargos[$item->getNombre()] = $item->getId();
        }

        $this->categorias[''] = '';
        $query = $this->em->getRepository('AppBundle:Categoria')->findAll();
        foreach ($query as $item) {
            $this->categorias[$item->getNombre()] = $item->getId();
        }

        $this->contratos[''] = '';
        $query = $this->em->getRepository('AppBundle:Contrato')->findAll();
        foreach ($query as $item) {
            $this->contratos[$item->getTipo()] = $item->getId();
        }

        $this->entidades[''] = '';
        $query = $this->em->getRepository('AppBundle:Entidad')->findBy(['externa' => false]);
        foreach ($query as $item) {
            $this->entidades[$item->getNombre()] = $item->getId();
        }

        $this->escalas[''] = '';
        $query = $this->em->getRepository('AppBundle:EscalaSalarial')->findAll();
        foreach ($query as $item) {
            $this->escalas[$item->__toString()] = $item->getId();
        }

        $this->niveles[''] = '';
        $query = $this->em->getRepository('AppBundle:Escolaridad')->findAll();
        foreach ($query as $item) {
            $this->niveles[$item->getNivel()] = $item->getId();
        }
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('carnet', TextType::class)
            ->add('nombre', TextType::class)
            ->add('apellidos', TextType::class)
            ->add('sexo', ChoiceType::class, array(
                'choices' => [
                    '' => '',
                    'Femenino' => false,
                    'Masculino' => true
                ]
            ))
            ->add('direccion', TextareaType::class)
            ->add('cargo', ChoiceType::class, array(
                'choices' => $this->cargos
            ))
            ->add('categoria', ChoiceType::class, array(
                'choices' => $this->categorias
            ))
            ->add('contrato', ChoiceType::class, array(
                'choices' => $this->contratos
            ))
            ->add('entidad', ChoiceType::class, array(
                'choices' => $this->entidades
            ))
            ->add('escalaSalarial', ChoiceType::class, array(
                'choices' => $this->escalas
            ))
            ->add('escolaridad', ChoiceType::class, array(
                'choices' => $this->niveles
            ));

        $builder
            ->get('cargo')->addModelTransformer(new IdToCargoTransformer($this->em));
        $builder
            ->get('categoria')->addModelTransformer(new IdToCategoriaTransformer($this->em));
        $builder
            ->get('contrato')->addModelTransformer(new IdToContratoTransformer($this->em));
        $builder
            ->get('entidad')->addModelTransformer(new IdToEntidadTransformer($this->em));
        $builder
            ->get('escalaSalarial')->addModelTransformer(new IdToEscalaSalarialTransformer($this->em));
        $builder
            ->get('escolaridad')->addModelTransformer(new IdToEscolaridadTransformer($this->em));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Trabajador'
        ));
    }

    public function getName()
    {
        return 'trabajador';
    }
}