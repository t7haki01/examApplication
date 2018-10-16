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
use App\Entity\User;
use App\Form\QuestionType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class TeacherController extends AbstractController
{
    protected $teacher;
    protected $isTeacher;

    public function __construct(TokenStorageInterface $tokenStorage){
        $this->teacher = $tokenStorage->getToken()->getUser();
        $teacherCheck=$tokenStorage->getToken()->getUser()->getIsTeacher();

//        if(!$teacherCheck){
//            $this-> isTeacher = false;
//         }
//        else{
//            $this-> isTeacher = true;
//        }
    }

    public function teacherMain(Request $request){
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

//        if(!($this->isTeacher)){
//            $request->getSession()
//                ->getFlashBag()
//                ->add('msg','You are not allowed to access');
//
//            return $this->redirectToRoute('index');
//        }
        $teacherId=$this->getUser()->getTeacher()->getId();
        $exams = $this-> getDoctrine()->getRepository(Exam::class)->
        findBy(array('teacher' => $teacherId));

        $teacherData = $this->getUser();

        return  $this->render('teacher/teacher_main.html.twig',
            array('teacherId' => $teacherId, 'exams' =>$exams,
                   'teacherData' => $teacherData
                ));
    }

    public function makeQuestion(Request $request){
        $teacherId=$this->getUser()->getTeacher()->getId();
//        Here is used for form bundle
        $form = $this->createForm(QuestionType::class)
            ->add('save', SubmitType::class,
                array('label'=>'Make Question',
                    'attr'=> array('class'=>'btn btn-primary')));
        $form->handleRequest($request);

        if($form->isSubmitted()){
            $teacherData = $this-> getDoctrine()->getRepository(Teacher::class)->find($teacherId);
            $newQuestion = $form->getData();
            $newQuestion-> setDate(new \DateTime());
            $newQuestion-> setTeacher($teacherData);
            $em = $this -> getDoctrine() -> getManager();

            $em->persist($newQuestion);
            $em->flush();
            $request->getSession()
                ->getFlashBag()
                ->add('success','Question created!');
            $this->redirectToRoute('teacher_make_question');
        }

        $questionData = $this->getDoctrine()->getRepository(Question::class)->findBy(array('teacher'=>$teacherId));

        return $this->render('teacher/make_question.html.twig', array('question' => $questionData,
            'teacherId' => $teacherId, 'addNewQuestionForm' => $form->createView())
        );
    }

    public function editQuestion(Request $request, $questionId){
        $teacherId=$this->getUser()->getTeacher()->getId();
        $questionData = $this-> getDoctrine()->getRepository(Question::class)->find($questionId);
        $form = $this->createFormBuilder($questionData)
            ->add('question', TextType::class,
                array('attr'=>array('class'=>'form-control')))
            ->add('category', TextType::class,
                array('attr'=>array('class'=>'form-control')))
            ->add('examples', TextType::class,
                array('attr'=>array('class'=>'form-control')))
            ->add('answers', TextType::class,
                array('attr'=>array('class'=>'form-control')))
            ->add('save', SubmitType::class,
                array('label'=> 'Save', 'attr'=>array('class'=>'btn btn-primary')))
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

    public function makeExam(){
        $teacherId=$this->getUser()->getTeacher()->getId();
        $questions = $this-> getDoctrine()->getRepository(Question::class)->
            findBy(array('teacher' => $teacherId));

        return $this->render('teacher/make_exam.html.twig',
            array("questions" =>$questions, 'teacherId'=>$teacherId));
    }

    public function makeExamSelected($teacherId, $questionIds, $examTitle, Request $request){

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

        $request->getSession()
            ->getFlashBag()
            ->add('success','Exam created!');
        return $this->redirectToRoute('teacher_make_exam');

    }

    public function makeExamRandom(){
        $teacherId=$this->getUser()->getTeacher()->getId();
        $questions = $this->getDoctrine()->getRepository(Question::class)->findBy(array('teacher' => $teacherId));

        return $this->render('teacher/make_exam_random_option.html.twig',
            array('teacherId' => $teacherId, 'question'=>$questions));
    }

    public function  makeExamRandomSelected(Request $request){
        $teacherId=$this->getUser()->getTeacher()->getId();
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
        //here was the making random exam when i misunderstood
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

        if($numberOfQuestion>count($questionIds))
        {
            $request->getSession()
                ->getFlashBag()
                ->add('msg',
                    'You set number of question 
                        more than questions already exist');
        }
        else
        {

////until here misunderstood

            $randomSetting = [];
            array_push($randomSetting, 'random');
            array_push($randomSetting, $numberOfQuestion);
            array_push($randomSetting, $category);
            $exam->setQuestionIds(implode(',',$randomSetting));
            $em->persist($exam);
            $em->flush();
            $request->getSession()
                ->getFlashBag()
                ->add('msg','Exam created successfully');
        }

        return $this->redirectToRoute('teacher_make_exam_random');

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
        $randomCheck = $examData->getQuestionIds();
        $randomCheckArray = explode(',',$randomCheck);
    if($randomCheckArray[0]!=="random") {
        $questionIds = $examData->getQuestionIds();
        $questionIdsArray = explode(',', $questionIds);

        $student = $this->getDoctrine()->getRepository(\App\Entity\Student::class)
            ->find($studentId);

        $examResult = $this->getDoctrine()->getRepository(Result::class)
            ->findBy(array('exam' => $examData,
                'student' => $student));
        $questions = [];
        $answers = [];
        $studentAnswer = [];
        $isCorrect = [];
        foreach ($examResult as $index => $result) {
            array_push($studentAnswer,
                $examResult[$index]->getStudentAnswer());
            array_push($isCorrect,
                $examResult[$index]->getIsCorrect());
        }

        foreach ($questionIdsArray as $id) {
            if ($id != " " && $id != null) {
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
            ->findBy(array('exam' => $examData,
                'student' => $student
            ));
    }
    //Here is case that if exam type were random
        else{
            $student = $this->getDoctrine()->getRepository(\App\Entity\Student::class)
                ->find($studentId);

            $examResult = $this->getDoctrine()->getRepository(Result::class)
                ->findBy(array('exam'=>$examData,
                    'student' => $student));
            $questionIdsArray =[];

            foreach ($examResult as $result){
                array_push($questionIdsArray, $result->getQuestion()->getId());
            }

            $questions =[];
            $answers =[];
            $studentAnswer =[];
            $isCorrect = [];
            foreach($examResult as $index => $result){
                array_push($studentAnswer,
                    $examResult[$index]->getStudentAnswer());
                array_push($isCorrect,
                    $examResult[$index]->getIsCorrect());
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
        }


        return $this->render('teacher/exam_result_detail.html.twig',
            array('studentAnswer' => $studentAnswer, 'examData' => $examData,
                'questionData' => $questions, 'score' => $score,
                'answerData' => $answers, 'studentId'=> $studentId,
                'studentData' => $student, 'isCorrect'=>$isCorrect
            ));
    }

}///this is end of class closing curly bracket