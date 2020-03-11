<?php

namespace App\Form;

use App\Entity\Client;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array('attr' => array('label' => 'Nombre', 'class' => 'form-control txt')))
            ->add('address', TextType::class, array('attr' => array('label' => 'Calle', 'class' => 'form-control txt')))
            ->add('nif', TextType::class, array('attr' => array('onBlur' => 'validateDNI()', 'label' => 'NIF', 'class' => 'form-control txt')))
            ->add('email', EmailType::class, array('attr' => array('label' => 'Email', 'class' => 'form-control txt')))
            ->add('telephone', TelType::class, array('attr' => array('label' => 'Telefono', 'class' => 'form-control txt')))
            ->add('web', UrlType::class, array('attr' => array('label' => 'Web', 'class' => 'form-control txt')))
            ->add('submit', SubmitType::class, array('label' => 'Guardar', 'attr' => array('class' => 'form-control txt mt-4', 'onsubmit' => 'return validateDNI()')))
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
