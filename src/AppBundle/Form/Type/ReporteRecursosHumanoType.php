<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class ReporteRecursosHumanoType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('columna', ChoiceType::class, array(
                'choices' => [
                    'Carnet' => 'carnet',
                    'Nombre' => 'nombre',
                    'Apellidos' => 'apellidos',
                    'Dirección' => 'direccion',
                    'Fecha de Alta' => 'fechaAlta',
                    'Cargo' => 'cargo',
                    'Categoría' => 'categoria',
                    'Entidad' => 'entidad',
                    'Escala Salarial' => 'escalaSalarial',
                    'Integración' => 'integracion',
                    'Nivel escolar' => 'escolaridad',
                    'Tipo de Contrato' => 'contrato'
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
            ->add('hombre', CheckboxType::class)
            ->add('mujer', CheckboxType::class)
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
        return 'rRH';
    }
}