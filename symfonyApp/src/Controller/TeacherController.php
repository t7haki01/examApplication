<?php
/**
 * Created by PhpStorm.
 * User: kihun
 * Date: 24/09/2018
 * Time: 20:08
 */

namespace App\Controller;


use App\Entity\Question;
use App\Form\QuestionType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Debug\Debug;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;

class TeacherController extends AbstractController
{
    public function makeQuestion(Request $request){
        $newQuestion = new Question();
        $form = $this->createFormBuilder($newQuestion)
            ->add('question', TextType::class)
            ->add('category', TextType::class)
            ->add('examples', TextType::class)
            ->add('answers', TextType::class)
            ->add('save', SubmitType::class, array('label'=>'Make Question'))
            ->getForm();
        $form->handleRequest($request);

        if($form->isSubmitted()){
            $newQuestion = $form->getData();
            $newQuestion-> setDate(new \DateTime());
            $em = $this -> getDoctrine() -> getManager();

            $textExamples = $form['examples']->getData();
            $arrayExamples = explode(", ", $textExamples);
            $newQuestion-> setExamples($arrayExamples);
            $form->get('examples')->setData($arrayExamples);

            $textAnswers = $form['answers']->getData();
            $arrayAnswers = explode(", ", $textAnswers);
            $newQuestion->setAnswers($arrayAnswers);
            $form->get('answers')->setData($arrayAnswers);

            debugger_print($form->getData());

            $em->persist($newQuestion);
            $em->flush();
            $this->redirectToRoute('teacher/make_question');
        }

//        Here is used for form bundle
//        $form = $this->createForm(QuestionType::class)
//            ->add('save', SubmitType::class, array('label'=>'Make Question'));
//        $form->handleRequest($request);

        $testData = $this->getDoctrine()->getRepository(Question::class)->findAll();

        return $this->render('teacher/make_question.html.twig', array('test' => $testData,
            'addNewQuestionForm' => $form->createView())
        );
    }
}