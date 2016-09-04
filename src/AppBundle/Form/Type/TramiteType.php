<?php

namespace AppBundle\Form\Type;

use AppBundle\Form\DataTransformer\StringToDateTimeTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class TramiteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fechaEntrada', TextType::class)
            ->add('fechaVencimiento', TextType::class)
            ->add('descripcion', TextareaType::class)
            ->add('comentario', TextareaType::class)
            ->add('isCompletado', CheckboxType::class);

        $builder->get('fechaEntrada')
            ->addModelTransformer(new StringToDateTimeTransformer());
        $builder->get('fechaVencimiento')
            ->addModelTransformer(new StringToDateTimeTransformer());
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Tramite'
        ));
    }

    public function getName()
    {
        return 'tramite';
    }
}