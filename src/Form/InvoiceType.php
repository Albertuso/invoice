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
            ->add('date', DateType::class, [
                'widget' => 'single_text',
                'attr' => ['min' => $options ['min_date']],
            ])
            ->add('description', TextareaType::class, array('attr' => array('maxlength' => '255', 'rows' => '6', 'class' => 'form-control txt')))
            ->add('footer', TextareaType::class, array('label' => "Pie de página", 'attr' => array('maxlength' => '255', 'rows' => '2', 'class' => 'form-control txt w-100')))
            ->add('subtotal', NumberType::class, array('label' => 'Subtotal(€):', 'attr' => array('readonly' => true, 'step' => "any", 'class' => 'form-control w-100')))
            ->add('total', NumberType::class, array('label' => 'Total(€):', 'attr' => array('readonly' => true, 'step' => "any", 'class' => 'form-control w-100')));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Invoice::class,
            'min_date' => Invoice::class,
        ]);
    }
}
