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
    public function showResult($studentId, $examId){
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
    $studentAnswer = $examResult ->getStudentAnswers();

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


     return $this->render('student/exam_result.html.twig',
         array('studentAnswer' => $studentAnswer, 'examData' => $examData,
                'questionData' => $questions, 'score' => $score,
             'answerData' => $answers
             ));
    }

}