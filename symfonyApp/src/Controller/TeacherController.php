<?php
/**
 * Created by PhpStorm.
 * User: kihun
 * Date: 24/09/2018
 * Time: 20:08
 */

namespace App\Controller;


use App\Entity\Exam;
use App\Entity\Question;
use App\Entity\Teacher;
use App\Form\QuestionType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TeacherController extends AbstractController
{
    public function teacherMain($teacherId){
        $exams = $this-> getDoctrine()->getRepository(Exam::class)->
        findBy(array('teacher' => $teacherId));

        return  $this->render('teacher/teacher_main.html.twig', array('teacherId' => $teacherId, 'exams' =>$exams));
    }

    public function makeQuestion(Request $request, $teacherId){

//        Here is used for form bundle
        $form = $this->createForm(QuestionType::class)
            ->add('save', SubmitType::class, array('label'=>'Make Question'));
        $form->handleRequest($request);

        if($form->isSubmitted()){
            $teacherData = $this-> getDoctrine()->getRepository(Teacher::class)->find($teacherId);
            $newQuestion = $form->getData();
            $newQuestion-> setDate(new \DateTime());
            $newQuestion-> setTeacher($teacherData);
            $em = $this -> getDoctrine() -> getManager();

            $em->persist($newQuestion);
            $em->flush();
            $this->redirectToRoute('teacher_make_question',array('teacherId'=>$teacherId));
        }

        $questionData = $this->getDoctrine()->getRepository(Question::class)->findBy(array('teacher'=>$teacherId));

        return $this->render('teacher/make_question.html.twig', array('question' => $questionData,
            'teacherId' => $teacherId, 'addNewQuestionForm' => $form->createView())
        );
    }

    public function editQuestion(Request $request, $teacherId,$questionId){
        $questionData = $this-> getDoctrine()->getRepository(Question::class)->find($questionId);
        $form = $this->createFormBuilder($questionData)
            ->add('question', TextType::class)
            ->add('category', TextType::class)
            ->add('examples', TextType::class)
            ->add('answers', TextType::class)
            ->add('save', SubmitType::class, array('label'=> 'Save'))
            ->getForm();
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $questionData = $form->getData();
            $questionData-> setDate(new \DateTime());
            $em = $this->getDoctrine()->getManager();
            $em->persist($questionData);
            $em->flush();
        }

        return $this->render('teacher/edit_question.html.twig',array('question' => $questionData,
                                                                'teacherId' => $teacherId,
                                                                'editForm'=> $form->createView()));
    }

    public function deleteQuestion($questionId){
        $questionData = $this->getDoctrine()->getRepository(Question::class)->find($questionId);
        $em = $this->getDoctrine()->getManager();
        $em->remove($questionData);
        $em->flush();
        return new Response();
    }

    public function makeExam($teacherId){
        $questions = $this-> getDoctrine()->getRepository(Question::class)->
            findBy(array('teacher' => $teacherId));

        return $this->render('teacher/make_exam.html.twig', array("questions" =>$questions, "teacherId" => $teacherId));
    }

    public function makeExamSelected($teacherId, $questionIds, $examTitle){

        $em = $this->getDoctrine()->getManager();
        $teacherData =  $this->getDoctrine()->getRepository(Teacher::class)
            ->find($teacherId);
        $newExam = new Exam();

        $newExam->setDate(new \DateTime());
        $newExam->setQuestionIds($questionIds);
        $newExam->setIsPublished(false);
        $newExam->setTeacher($teacherData);
        $newExam->setExamTitle($examTitle);

        $em->persist($newExam);
        $em->flush();

        return new Response();
    }

    public function makeExamRandom($teacherId){
        $questions = $this->getDoctrine()->getRepository(Question::class)->findBy(array('teacher' => $teacherId));

        return $this->render('teacher/make_exam_random_option.html.twig',
            array('teacherId' => $teacherId, 'question'=>$questions));
    }

    public function  makeExamRandomSelected($teacherId, $examTitle, $numbers, $category){
        $em = $this->getDoctrine()->getManager();
        $teacherData =  $this->getDoctrine()->getRepository(Teacher::class)
            ->find($teacherId);

        $questions = $this->getDoctrine()->getRepository(Question::class)->findBy(array('teacher' => $teacherId));
        $testArray = [];
        $category_array = $questions->getCategory();


        $result_array = array_merge($testArray, $category_array);

        return $this->render('test.html.twig',
            array('test'=>$category_array));



//        $newExam = new Exam();
//
//        $newExam->setDate(new \DateTime());
//
//        $newExam->setQuestionIds('1,2,3,4');
//        $newExam->setIsPublished(false);
//        $newExam->setTeacher($teacherData);
//        $newExam->setExamTitle($examTitle);
//
//        $em->persist($newExam);
//        $em->flush();
//
//        return new Response();
    }
    
    public function makeExamPublish($examId){
        $examData = $this->getDoctrine()->getRepository(Exam::class)->find($examId);
        $examData->setIspublished(!$examData->getIspublished());
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($examData);
        $entityManager->flush();

        return new Response();
    }

    public function examDelete($examId){
        $examData = $this->getDoctrine()->getRepository(Exam::class)->find($examId);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($examData);
        $entityManager->flush();
        return new Response();
    }


}///this is end of class closing curly bracket