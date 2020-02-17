<?php

namespace App\Form;

use App\Entity\Client;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',null, array('label' => 'Nombre'))
            ->add('address',null, array('label' => 'Direccion'))
            ->add('nif',null, array('label' => 'DNI'))
            ->add('email',null, array('label' => 'E-mail'))
            ->add('telephone',null, array('label' => 'Telefono'))
            ->add('web',null, array('label' => 'Web'))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Client::class,
        ]);
    }
}
