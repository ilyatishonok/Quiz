<?php

declare(strict_types=1);

namespace AppBundle\Form;

use AppBundle\Entity\Question;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuestionType extends  AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add("name", TextType::class, array("attr"=>array('class'=>"question-name form-control")))
                ->add('answers', CollectionType::class, array(
                        'entry_type' => AnswerType::class,
                        'label'=>false,
                        'allow_add' => true,
                        'prototype' => true,
                        'csrf_protection' => true,
    ));
    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Question::class,
            'translation_domain' => 'translations',
        ));
    }
}
