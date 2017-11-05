<?php

namespace AppBundle\Form;


use AppBundle\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email',EmailType::class, array('label'=>'security.registration.labels.email'))
            ->add('username',TextType::class, array('label'=>'security.registration.labels.username'))
            ->add("firstname",TextType::class, array('label'=>'security.registration.labels.firstname'))
            ->add("lastname", TextType::class, array('label'=>'security.registration.labels.lastname'))
            ->add("middlename", TextType::class, array('label'=>'security.registration.labels.middlename'))
            ->add('plainPassword',RepeatedType::class,array(
               'type' => PasswordType::class,
                'first_options' => array('label' => 'security.registration.labels.firstpass'),
                'second_options' => array('label' => 'security.registration.labels.secondpass')
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => User::class,
            'translation_domain' => 'translations',
        ));
    }
}