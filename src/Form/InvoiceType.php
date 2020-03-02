<?php

namespace App\Form;

use App\Entity\Invoice;
use NumberFormatter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class InvoiceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // ->add('invoicenumber')
            ->add('date', DateType::class)
            ->add('description', TextareaType::class, array('attr' => array('maxlength' => '255', 'rows' => '5', 'class' => 'form-control txt')))
            ->add('footer', TextareaType::class, array('label'=>"Pie de página" ,'attr' => array('maxlength' => '255', 'rows' => '2', 'class' => 'form-control txt w-100')))
            ->add('subtotal', NumberType::class, array('attr' => array('readonly' => true, 'step' => "any", 'class' => 'form-control')))
            ->add('total', NumberType::class, array('attr' => array('readonly' => true, 'step' => "any", 'class' => 'form_control')));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Invoice::class,
        ]);
    }
}
