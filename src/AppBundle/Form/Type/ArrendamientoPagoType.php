<?php

namespace AppBundle\Form\Type;

use AppBundle\Form\Type\PagoType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class ArrendamientoPagoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('pagos', CollectionType::class, array(
            'entry_type' => PagoType::class,
//            'by_reference' => 'false'
        ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
//            'data_class' => 'AppBundle\Entity\Arrendamiento'
        ));
    }

    public function getName()
    {
        return 'arrendamientoPago';
    }
}