<?php
/**
 * Created by PhpStorm.
 * User: kihun
 * Date: 02/10/2018
 * Time: 22:03
 */

namespace App\Controller;


use App\Entity\Question;
use App\Entity\Result;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Exam extends AbstractController
{
    public function examShow($examId, $studentId){
        $questions = $this->getDoctrine()->getRepository(Question::class)->findAll();
        $examData = $this->getDoctrine()->getRepository(\App\Entity\Exam::class)->find($examId);

        return $this->render('exam/showExam.html.twig',
            array('examData' => $examData,
                'questions' => $questions,
                'studentId' => $studentId));

    }

    public function examMain($studentId){
        $exams = $this-> getDoctrine()->getRepository(\App\Entity\Exam::class)->
        findAll();

        return  $this->render('exam/exam_main.html.twig',
            array('studentId' => $studentId, 'exams' =>$exams));
    }

    public function examResult($studentId, $examId, $questionIds, Request $request){

            $questionIdsArray = explode(",", $questionIds);
            foreach($questionIdsArray as $index => $questionId ){
                if($questionId != " "){
                $newResult = new Result();
                $em=$this->getDoctrine()->getManager();
                $questionData = $this->getDoctrine()->getRepository(Question::class)
                    ->find($questionId);

                $newResult->setDate(new \DateTime());

                $newResult->setQuestion($questionData);

                $answerIndex = "answer" . (int)$questionIdsArray[$index] ;

                $studentAnswer = $request->request->get($answerIndex);

                $studentAnswerString = implode(',', $studentAnswer);

                $newResult -> setStudentAnswer($studentAnswerString);

                $newResult->setIsCorrect(false);
//                if($student == $correct){
//                    $newResult->setIsCorrect(true);
//                }
//                else{
//                    $newResult->setIsCorrect(false);
//                }

                $em->persist($newResult);

                }
            }
            $em->flush();


            return $this->render('test.html.twig',
            array('studentId' => $studentId, 'examId' => $examId,
                'questionIds' => $questionIds, 'test'=>$studentAnswerString));
    }
}