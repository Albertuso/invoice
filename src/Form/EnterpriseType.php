<?php

namespace App\Form;

use App\Entity\Enterprise;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EnterpriseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',null, array('label' => 'Nombre'))
            ->add('address',null, array('label' => 'Direccion'))
            ->add('telephone',null, array('label' => 'Telefono'))
            ->add('email',null, array('label' => 'E-mail'))
            ->add('web',null, array('label' => 'Web'))
            ->add('nif',null, array('label' => 'DNI'))
            ->add('logo',null, array('label' => 'Logotipo'))
            ->add('footer',null, array('label' => 'Pie de pagina'))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Enterprise::class,
        ]);
    }
}
