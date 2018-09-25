<?php

namespace App\Form;

use App\Entity\Question;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuestionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('question', TextType::class)
            ->add('category', TextType::class)
            ->add('examples', TextType::class)
            ->add('answers', TextType::class)
        ;
//        $builder ->get('examples')
//            ->addModelTransformer(new CallbackTransformer(
//                function ($tagsAsString){
//                    return explode(', ', $tagsAsString);
//                },
//                function($tagsAsArray){
//                    return implode(', ', $tagsAsArray);
//                }
//            ));
//        $builder ->get('answers')
//            ->addModelTransformer(new CallbackTransformer(
//                function ($tagsAsString){
//                    return explode(', ', $tagsAsString);
//                },
//                function($tagsAsArray){
//                    return implode(', ', $tagsAsArray);
//                }
//            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Question::class,
        ]);
    }
}
