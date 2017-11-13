<?php
declare(strict_types=1);
namespace AppBundle\Form;

use AppBundle\Entity\Answer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class AnswerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add("name", TextType::class, array(
            "label" => false,
            'required' => true,
            'by_reference' => false,
            'constraints' => array(
                new Length(array('min' => 1)),
            ),
            'attr'=>array("class"=>"answer form-control")));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(array(
            'data_class' => Answer::class,
            'translation_domain' => 'translations',
        ));
    }
}
