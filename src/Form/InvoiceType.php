<?php

namespace App\Form;

use App\Entity\Invoice;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InvoiceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('invoicenumber',null, array('label' => 'Numero de factura'))
            ->add('date',null, array('label' => 'Fecha'))
            ->add('description',null, array('label' => 'Descripcion'))
            ->add('footer',null, array('label' => 'Pie de pagina'))
            ->add('subtotal',null, array('label' => 'Subtotal'))
            ->add('total',null, array('label' => 'Total'))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Invoice::class,
        ]);
    }
}
