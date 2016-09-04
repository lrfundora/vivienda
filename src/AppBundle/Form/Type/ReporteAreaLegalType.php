<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class ReporteAreaLegalType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('columna', ChoiceType::class, array(
                'choices' => [
                    'Abogado' => 'abogado',
                    'Cliente: Carnet' => 'carnet',
                    'Cliente: Nombre' => 'nombre',
                    'Cliente: Apellidos' => 'apellidos',
                    'Cliente: Dirección' => 'direccion',
                    'Trámite: Descripción' => 'descripcion',
                    'Trámite: Fecha de entrada' => 'fechaEntrada',
                    'Trámite: Fecha de vencimiento' => 'fechaVencimiento'
                ]
            ))
            ->add('coincidencia', ChoiceType::class, array(
                'choices' => [
                    'Contiene' => '1',
                    'Comienza con' => '2',
                    'Exactamente' => '3',
                    'Termina con' => '4'
                ]
            ))
            ->add('criterio', TextType::class)
            ->add('completado', CheckboxType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
//        $resolver->setDefaults(array(
//            'data_class' => 'AppBundle\Entity\Trabajador'
//        ));
    }

    public function getName()
    {
        return 'rAL';
    }
}