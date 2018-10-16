<?php
/**
 * Created by PhpStorm.
 * User: kihun
 * Date: 06/10/2018
 * Time: 14:09
 */

namespace App\Controller;

use App\Controller\debug\ChromePhp;
use App\Entity\ExamResult;
use App\Entity\Question;
use App\Entity\Result;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;


class Student extends AbstractController
{
    protected $student;

    public function __construct(TokenStorageInterface $tokenStorage){
            $this->student = $tokenStorage-> getToken()->getUser()->getStudent()->getId();
    }

    public function studentMain(){

        $studentId=$this->student;

        $student=$this->getUser()->getStudent();

        $studentData = $this->getUser();

        $examResult = $this->getDoctrine()->getRepository(ExamResult::class)
            ->findBy(array('student'=>$student));

        return  $this->render('student/student_main.html.twig',
            array('studentId' => $studentId, 'examResult'=>$examResult,
                'studentData'=>$studentData
            ));
    }


    public function showResult($studentId, $examId){
    $examData = $this->getDoctrine()->getRepository(\App\Entity\Exam::class)
            ->find($examId);

    $randomCheck = $examData->getQuestionIds();

    $randomCheckArray = explode(',',$randomCheck);
//in Exam entity questionIds also store the type of exam when exam is random type
//for checking it get the questionIds first and check first element of array
//for deciding exam type
    if($randomCheckArray[0]!=="random"){
        //when exam is not random type

        $questionIds = $examData->getQuestionIds();

        $questionIdsArray = explode(',', $questionIds);

        $student = $this->getDoctrine()->getRepository(\App\Entity\Student::class)
            ->find($studentId);

        $examResult = $this->getDoctrine()->getRepository(Result::class)
            ->findBy(array('exam'=>$examData,
                'student' => $student));
        //here initialize four empty array for saving information for
        //result in order
        //from examResult which selected under condition exam and
        //student from result entity
        //store the isCorrect per question from exam and
        //student answer per question from exam
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

        //and here get the original information from question entity
        //based on questionIds of exam

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
    //here is for the if exam type is random then get question id differently
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
    }//until here random exam type


     return $this->render('student/exam_result.html.twig',
         array('studentAnswer' => $studentAnswer, 'examData' => $examData,
                'questionData' => $questions, 'score' => $score,
             'answerData' => $answers, 'studentId'=> $studentId,
             'isCorrect'=>$isCorrect
             ));
    }

}