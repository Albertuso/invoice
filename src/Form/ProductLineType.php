<?php

namespace App\Form;

use App\Entity\ProductLine;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductLineType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('description',null, array('label' => 'Descripcion'))
            ->add('quantity',null, array('label' => 'Cantidad'))
            ->add('price',null, array('label' => 'Precio'))
            ->add('vat',null, array('label' => 'IVA'))
            ->add('name',null, array('label' => 'Nombre'))
            ->add('invoice',null, array('label' => 'Factura (Creo que este no vale)'))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ProductLine::class,
        ]);
    }
}
