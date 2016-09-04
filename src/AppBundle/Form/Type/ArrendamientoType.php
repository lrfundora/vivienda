<?php

namespace AppBundle\Form\Type;

use AppBundle\Form\DataTransformer\StringToDateTimeTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ArrendamientoType extends AbstractType
{
    private $anos;

    public function __construct()
    {
        $ini = 1900;
        $end = date_format(new \DateTime('now'), 'Y');
        $this->anos[''] = '';
        for ($i = $ini; $i <= $end; $i++) {
            $this->anos[$i] = $i;
        }
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('numeroRegistro', NumberType::class)
            ->add('resolucion', TextType::class)
            ->add('expediente', TextType::class)
            ->add('numeroContrato', NumberType::class)
            ->add('fechaContrato', TextType::class)
            ->add('anoConstruccion', ChoiceType::class, array(
                'choices' => $this->anos
            ))
            ->add('motivo', TextareaType::class)
            ->add('estado', ChoiceType::class, array(
                'choices' => [
                    '' => '',
                    'Malo' => 'Malo',
                    'Regular' => 'Regular',
                    'Bueno' => 'Bueno'
                ]

            ))
            ->add('valor', NumberType::class);

        $builder
            ->get('fechaContrato')->addModelTransformer(new StringToDateTimeTransformer());
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Arrendamiento'
        ));
    }

    public function getName()
    {
        return 'arrendamiento';
    }
}