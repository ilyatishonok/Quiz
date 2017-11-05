<?php

declare(strict_types=1);

namespace AppBundle\Form;

use AppBundle\Choices\AnswerChoice;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuestionChoiceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $question = $options['question'];
        $builder->add("answer", EntityType::class, array(
            'class'=> "AppBundle\Entity\Answer",
            'choice_label'=>'name',
            'query_builder' => function (EntityRepository $er) use ($question) {
                return $er->createQueryBuilder('a')
                    ->where("a.question = :question")
                    ->setParameter("question", $question);
            },
            'label'=>false,
            'attr' => array('class' => 'form_answers'),
            'expanded'=>true))
            ->add('Submit question', SubmitType::class, array(
                'attr' => array('class' => "btn btn-large submit-btn")
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => AnswerChoice::class,
            'answers' => null,
            'question' => null,
            'translation_domain' => 'translations',
        ));
    }
}