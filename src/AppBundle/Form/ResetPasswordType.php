<?php

namespace AppBundle\Form;


use AppBundle\Choices\EmailChoice;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ResetPasswordType extends AbstractType
{

   public function buildForm(FormBuilderInterface $builder, array $options)
   {
       $builder->add("email", EmailType::class, array('attr'=>array("class"=> "form-control", 'placeholder' => "Input Email")) )
                ->add("submit", SubmitType::class, array('attr'=>array('class' => "btn btn-large email-btn")))
                ->setMethod("PATCH");
   }

   public function configureOptions(OptionsResolver $resolver)
   {
       $resolver->setDefaults(array(
           'data_class' => EmailChoice::class,
           'translation_domain' => 'translations',
           'method' => 'PATCH',
       ));
   }

}