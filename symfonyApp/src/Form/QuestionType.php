<?php

namespace App\Form;

use App\Entity\Question;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuestionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('question', TextType::class, array('attr' => array('class'=>'form-control')))
            ->add('category', TextType::class, array('attr' => array('class'=>'form-control')))
            ->add('examples', TextType::class,array('attr' => array('placeholder' => 'Separate with comma(,) For the multiple answer options, if there are no options, then can be blank'
                                                                              ,'class'=>'form-control'), 'required' => false ))
            ->add('answers', TextType::class, array('attr' => array('placeholder' => 'For the multiple answers separate with comma(,)',
                                                                               'class'=>'form-control')))
        ;
//        $builder ->get('examples')
//            ->addModelTransformer(new CallbackTransformer(
//                function ($tagsAsString){
//                    return explode(', ', $tagsAsString);
//                },
//                function($tagsAsArray){
//                    return /*implode(', ', $tagsAsArray)*/;
//                }
//            ));
//        $builder ->get('answers')
//            ->addModelTransformer(new CallbackTransformer(
//                function ($tagsAsString){
//                    return explode(', ', $tagsAsString);
//                },
//                function($tagsAsArray){
//                    return /*implode(', ', $tagsAsArray)*/;
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
