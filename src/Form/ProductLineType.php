<?php

namespace App\Form;

use App\Entity\ProductLine;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ProductLineType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('description', TextType::class, array('label' => 'Descripcion', 'class' => 'form-control'))
            ->add('quantity', TextType::class, array('label' => 'Cantidad', 'class' => 'form-control'))
            ->add('price', TextType::class, array('label' => 'Precio', 'class' => 'form-control'))
            ->add('vat', TextType::class, array('label' => 'IVA', 'class' => 'form-control'))
            ->add('name', TextType::class, array('label' => 'Nombre', 'class' => 'form-control'));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ProductLine::class,
        ]);
    }
}
