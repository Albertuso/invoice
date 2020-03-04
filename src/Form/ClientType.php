<?php

namespace App\Form;

use App\Entity\Client;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array('attr' => array('label' => 'Nombre', 'class' => 'form-control txt')))
            ->add('address', TextType::class, array('attr' => array('label' => 'Calle', 'class' => 'form-control txt')))
            ->add('nif', TextType::class, array('attr' => array('label' => 'NIF', 'class' => 'form-control txt')))
            ->add('email', TextType::class, array('attr' => array('label' => 'Email', 'class' => 'form-control txt')))
            ->add('telephone', TextType::class, array('attr' => array('label' => 'Telefono', 'class' => 'form-control txt')))
            ->add('web', TextType::class, array('attr' => array('label' => 'Web', 'class' => 'form-control txt')))
            // ->add('supervisor',null, array('label' => 'Supervisor'))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Client::class,
        ]);
    }
}
