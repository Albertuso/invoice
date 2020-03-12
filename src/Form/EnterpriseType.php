<?php

namespace App\Form;

use App\Entity\Enterprise;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class EnterpriseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array('attr' => array('label' => 'Nombre', 'class' => 'form-control txt')))
            ->add('address', TextType::class, array('attr' => array('label' => 'Direccion', 'class' => 'form-control txt')))
            ->add('telephone', TextType::class, array('attr' => array('label' => 'Telefono', 'class' => 'form-control txt')))
            ->add('email', TextType::class, array('attr' => array('label' => 'Email', 'class' => 'form-control txt')))
            ->add('web', TextType::class, array('attr' => array('label' => 'Web', 'class' => 'form-control txt')))
            ->add('nif', TextType::class, array('attr' => array('label' => 'DNI', 'class' => 'form-control txt')))
            ->add('logo', FileType::class, array('label' => 'Logotipo', 'data_class' => null,  'required' => false))

            ->add('footer', TextType::class, array('attr' => array('label' => 'DNI', 'class' => 'form-control txt')))
            ->add('submit', SubmitType::class, array('label' => 'Actualizar', 'attr' => array('class' => 'form-control txt mt-4', 'onsubmit' => 'return validateDNI()')));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Enterprise::class,
        ]);
    }
}
