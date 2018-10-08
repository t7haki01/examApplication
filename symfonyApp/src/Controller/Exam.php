<?php
/**
 * Created by PhpStorm.
 * User: kihun
 * Date: 02/10/2018
 * Time: 22:03
 */

namespace App\Controller;

use App\Controller\debug\ChromePhp;

use App\Entity\ExamResult;
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
        $studentData = $this->getDoctrine()->getRepository(\App\Entity\Student::class)
            ->find($studentId);
        $result = $this->getDoctrine()->getRepository(ExamResult::class)
            ->findby(array('student'=>$studentData));

        return  $this->render('exam/exam_main.html.twig',
            array('studentId' => $studentId, 'exams' =>$exams,
                'result'=>$result));
    }

    public function examResult($studentId, $examId, $questionIds, Request $request){
        //This is for test
        $correctAnswerArraytest = [];
        $studentAnswertest = [];


            $examResult = new ExamResult();
            $student = $this->getDoctrine()->getRepository(\App\Entity\Student::class)
                ->find($studentId);
            $examResult->setStudent($student);

            $questionIdsArray = explode(",", $questionIds);
            $examData = $this->getDoctrine()->getRepository(\App\Entity\Exam::class)
                ->find($examId);

            $examResult->setExam($examData);
            $score=0;
            $totalQuestion=0;

            foreach($questionIdsArray as $index => $questionId ){
                if($questionId != " " && $questionId!=null){
                $newResult = new Result();
                $em=$this->getDoctrine()->getManager();
                $questionData = $this->getDoctrine()->getRepository(Question::class)
                    ->find($questionId);

                $newResult->setDate(new \DateTime());
                $newResult->setStudent($student);

                $newResult->setQuestion($questionData);

                $answerIndex = "answer" . (int)$questionIdsArray[$index] ;

                $studentAnswer = $request->request->get($answerIndex);

                //here for test
                    array_push($studentAnswertest, $studentAnswer);

                    if(is_array($studentAnswer)){
                        if(count($studentAnswer)==1){
                            $studentAnswerString = $studentAnswer[0];
                        }
                        else{
                            $studentAnswer =str_replace(' ','',$studentAnswer);
                            $studentAnswerString = implode(',', $studentAnswer);
                        }
                    }
                    else{
                        $studentAnswerString = $studentAnswer;
                    }



                $newResult -> setStudentAnswer($studentAnswerString);

                $newResult ->setExam($examData);


                $correctAnswer = $questionData->getAnswers();

                $correctAnswer = str_replace(' ', '',$correctAnswer);
                $correctAnswerArray = explode(',', $correctAnswer);

                //here also for test
                    array_push($correctAnswerArraytest, $correctAnswerArray);

                if(count($studentAnswer) != count($correctAnswerArray)){
                    $newResult->setIsCorrect(false);
                    $totalQuestion++;
                }
                else if(count($correctAnswerArray) === 1){
                    if(str_replace(' ','',strtoupper($correctAnswerArray[0]))
                        ==
                        str_replace(' ','',strtoupper($studentAnswer[0]))){
                        $newResult->setIsCorrect(true);
                        $totalQuestion++;
                        $score++;
                    }
                    else{
                        $newResult->setIsCorrect(false);
                        $totalQuestion++;
                        $score++;
                    }
                }
                else{
                    $answerCheck = true;

                   $refinedAnswer = array_map('strtoupper',$correctAnswerArray);

                   foreach($studentAnswer as $i){
                       if(in_array(strtoupper($i),$refinedAnswer)){
                           break;
                       }
                       else{
                           $answerCheck = false;
                       }
                   }
                   if($answerCheck == true){
                       $newResult->setIsCorrect(true);
                       $totalQuestion++;
                       $score++;
                   }
                   else{
                       $newResult->setIsCorrect(false);
                       $totalQuestion++;
                   }
                }
                $em->persist($newResult);
                }
            }
            $examResult->setScore($score.' correct from '.$totalQuestion.' questions');
            $em->persist($examResult);
            $em->flush();

            return $this->redirectToRoute('student_main',array('studentId'=>$studentId));
    }
}