<?php

namespace App\Form;

use App\Entity\Invoice;
use NumberFormatter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class InvoiceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // ->add('invoicenumber')
            ->add('date', DateType::class)
            ->add('description', TextareaType::class, array('attr' => array('maxlength' => '255', 'rows' => '5', 'class'=>'form-control txt')))
            ->add('footer')
            ->add('subtotal', TextType::class , array('attr' => array('readonly' => true)))
            ->add('total', TextType::class , array('attr' => array('readonly' => true)))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Invoice::class,
        ]);
    }
}
