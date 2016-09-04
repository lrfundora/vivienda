<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use AppBundle\Form\Type\ArrendamientoType;

class ClienteArrendamientoType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('carnet', TextType::class)
            ->add('nombre', TextType::class)
            ->add('apellidos', TextType::class)
            ->add('direccion', TextareaType::class)
            ->add('arrendamientos', CollectionType::class, array(
                'entry_type' => ArrendamientoType::class
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Cliente'
        ));
    }

    public function getName()
    {
        return 'cliente';
    }
}