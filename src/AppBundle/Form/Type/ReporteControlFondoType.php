<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class ReporteControlFondoType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('columna', ChoiceType::class, array(
                'choices' => [
                    'Cliente: Carnet' => 'carnet',
                    'Cliente: Nombre' => 'nombre',
                    'Cliente: Apellidos' => 'apellidos',
                    'Cliente: Dirección' => 'direccion',
                    'Número de Registro' => 'numeroRegistro',
                    'Resolución' => 'resolucion',
                    'Número de Expediente' => 'expediente',
                    'Número de Contrato' => 'numeroContrato',
                    'Fecha de Contrato' => 'fechaContrato',
                    'Año de Construcción' => 'anoConstruccion',
                    'Motivo de Construcción' => 'motivo',
                    'Estado' => 'estado',
                    'Valor' => 'valor'
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
            ->add('baja', CheckboxType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
//        $resolver->setDefaults(array(
//            'data_class' => 'AppBundle\Entity\Trabajador'
//        ));
    }

    public function getName()
    {
        return 'rCF';
    }
}