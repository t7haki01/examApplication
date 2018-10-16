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
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class Student extends AbstractController
{
    public function studentMain(){

        $student=$this->getUser()->getStudent();
        $studentId = $student->getId();
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

    if($randomCheckArray[0]!=="random"){
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

//        $questionIds = $examData->getQuestionIds();
//        $questionIdsArray = explode(',', $questionIds);


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

    ///until here random exam type


     return $this->render('student/exam_result.html.twig',
         array('studentAnswer' => $studentAnswer, 'examData' => $examData,
                'questionData' => $questions, 'score' => $score,
             'answerData' => $answers, 'studentId'=> $studentId,
             'isCorrect'=>$isCorrect
             ));
    }

}