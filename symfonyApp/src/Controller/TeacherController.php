<?php
/**
 * Created by PhpStorm.
 * User: kihun
 * Date: 24/09/2018
 * Time: 20:08
 */

namespace App\Controller;


use App\Controller\debug\ChromePhp;
use App\Entity\Exam;
use App\Entity\ExamResult;
use App\Entity\Question;
use App\Entity\Result;
use App\Entity\Teacher;
use App\Form\QuestionType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TeacherController extends AbstractController
{

    public function teacherMain(){
        $teacherId=$this->getUser()->getId();
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

    public function  makeExamRandomSelected($teacherId, Request $request){
        //Here is used for advice 'request' get methods
        $category = $request->request->get('category');
        $numberOfQuestion = $request->request->get('numberOfQuestions');
        $examTitle = $request->request->get('examTitle');

        $em = $this->getDoctrine()->getManager();
        $teacherData =  $this->getDoctrine()->getRepository(Teacher::class)
            ->find($teacherId);

        $exam = new Exam();
        $exam->setDate(new \DateTime());
        $exam->setExamTitle($examTitle);
        $exam->setTeacher($teacherData);
        $exam->setIsPublished(false);
//From here for the question Ids
        if($category == 'all'){
        $questionData = $this->getDoctrine()->getRepository(Question::class)
            ->findBy(array('teacher' => $teacherData));
        }
        else{
            $questionData = $this->getDoctrine()->getRepository(Question::class)
                ->findBy(array('category'=>$category,
                    'teacher' => $teacherData));
        }

        $questionIds = [];

        foreach ($questionData as $question){
            array_push($questionIds,$question->getId());
        }

        $finalQuestion = [];

        shuffle($questionIds);


        if($numberOfQuestion>count($questionIds))
        {
            $msg = "You set number of question 
            more than questions already exist";
        }
        else
        {
            for($i=0; $i<$numberOfQuestion; $i++){
                array_push($finalQuestion, $questionIds[$i]);
            }

            $exam->setQuestionIds(implode(",",$finalQuestion));

            $em->persist($exam);
            $em->flush();
            $msg ="Exam created successfully";
        }


        return new Response($msg);
    }
    
    public function makeExamPublish($examId){
        $examData = $this->getDoctrine()->getRepository(Exam::class)->find($examId);
        $examData->setIspublished(!$examData->getIspublished());
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($examData);
        $entityManager->flush();

        return new Response();
    }

//    public function editExam(Request $request, $teacherId, $examId){
//        $examData = $this-> getDoctrine()->getRepository(Exam::class)->find($examId);
//
//        $form = $this->createFormBuilder($examData)
//            ->add('question', TextType::class)
//            ->add('category', TextType::class)
//            ->add('examples', TextType::class)
//            ->add('answers', TextType::class)
//            ->add('save', SubmitType::class, array('label'=> 'Save'))
//            ->getForm();
//        $form->handleRequest($request);
//
//        if($form->isSubmitted() && $form->isValid()){
//            $questionData = $form->getData();
//            $questionData-> setDate(new \DateTime());
//            $em = $this->getDoctrine()->getManager();
//            $em->persist($questionData);
//            $em->flush();
//        }
//
//        return $this->render('teacher/edit_question.html.twig',array('question' => $questionData,
//            'teacherId' => $teacherId,
//            'editForm'=> $form->createView()));
//    }

    public function examDelete($examId){
        $examData = $this->getDoctrine()->getRepository(Exam::class)->find($examId);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($examData);
        $entityManager->flush();
        return new Response();
    }

    public function showExamResult($examId){
        $examData = $this->getDoctrine()->getRepository(Exam::class)
            ->find($examId)->getResults();
        $resultData = $this->getDoctrine()->getRepository(Exam::class)
            ->find($examId)->getExamResults();
        return $this->render('teacher/exam_result.html.twig'
        ,array('examData' => $examData,
                'resultData' => $resultData
        ));
    }

    public function checkExamResult($examId, $studentId){
        $examData = $this->getDoctrine()->getRepository(\App\Entity\Exam::class)
            ->find($examId);

        $questionIds = $examData->getQuestionIds();
        $questionIdsArray = explode(',', $questionIds);

        $student = $this->getDoctrine()->getRepository(\App\Entity\Student::class)
            ->find($studentId);

        $examResult = $this->getDoctrine()->getRepository(Result::class)
            ->findBy(array('exam'=>$examData,
                'student' => $student));
        $questions =[];
        $answers =[];
        $studentAnswer =[];
        foreach($examResult as $index => $result){
            array_push($studentAnswer,
                $examResult[$index]->getStudentAnswer());
        }

        foreach ($questionIdsArray as $id){
            if($id != " " && $id != null){
                str_replace(" ", "", $id);
                $questionById = $this->getDoctrine()->getRepository(Question::class)
                    ->find($id);
                $questionData = $questionById->getQuestion();
                $questionAnswers = $questionById->getAnswers();
                array_push($questions, $questionData);
                array_push($answers, $questionAnswers);
            }
        }

        $score = $this->getDoctrine()->getRepository(ExamResult::class)
            ->findBy(array('exam'=>$examData,
                'student'=>$student
            ));


        return $this->render('teacher/exam_result_detail.html.twig',
            array('studentAnswer' => $studentAnswer, 'examData' => $examData,
                'questionData' => $questions, 'score' => $score,
                'answerData' => $answers, 'studentId'=> $studentId
            ));
    }

}///this is end of class closing curly bracket