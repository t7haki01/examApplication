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
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class Exam extends AbstractController
{
    //Original idea was that having three modules that each module handle
    //related tasks but end of project figured out that
    //actually exam controller could be included in student controller
    protected $student;

    public function __construct(TokenStorageInterface $tokenStorage){
        $this->student=$tokenStorage->getToken()->getUser()->getStudent()->getId();
    }

    public function examShow($examId){
        $studentId = $this->student;

        $examData = $this->getDoctrine()->getRepository(\App\Entity\Exam::class)->find($examId);

        $randomCheck = explode(',',$examData->getQuestionIds());

        //for showing exam to student first exam type is checked
        //based on questionIds in the exam entity
        //first string before , stored as "random"
        //if exam is random type
        if($randomCheck[0]!=='random'){
            //Here goes when exam type is not random type
            $questions = $this->getDoctrine()->getRepository(Question::class)->findAll();
            $isRandom = false;
            $questionsAll='';
        }
        else{
            //Here goes when exam type is random type
            $isRandom = true;

            $questionsAll = $this->getDoctrine()->getRepository(Question::class)->findAll();

            //When exam type is random type
            //array from exploding questionIds of exam entity
            //store three information
            //first 'random' string when exam type is random
            //second category string
            //third number of questions for the exam

            //so here when category is not 'All', it will find questions by category condition
            if($randomCheck[2]!=='all'){
                $questions = $this->getDoctrine()->getRepository(Question::class)
                    ->findBy(array('category'=>$randomCheck[2]));
            }
            else{
                //Here when category is 'all'
                $questions = $this->getDoctrine()->getRepository(Question::class)->findAll();
            }
        }
        $questionNumbers = $randomCheck[1];

        return $this->render('exam/showExam.html.twig',
            array('examData' => $examData,
                'questions' => $questions,
                'studentId' => $studentId,
                'isRandom' =>$isRandom,
                'questionNumber' =>$questionNumbers,
                'questionsAll' => $questionsAll));
    }

    public function examMain(){
        $studentId = $this->student;
        $exams = $this-> getDoctrine()->getRepository(\App\Entity\Exam::class)->
        findAll();
        $studentData = $this->getDoctrine()->getRepository(\App\Entity\Student::class)
            ->find($studentId);
        $result = $this->getDoctrine()->getRepository(ExamResult::class)
            ->findby(array('student'=>$studentData));
        $allUsers = $this->getDoctrine()->getRepository(User::class)
            ->findBy(array('isTeacher'=>false));

        return  $this->render('exam/exam_main.html.twig',
            array('studentId' => $studentId, 'exams' =>$exams,
                'result'=>$result, 'allUsers'=>$allUsers));
    }

    public function examResult($studentId, $examId, Request $request){

            $questionIds = $request->request->get('questionIds');

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

            //From here start to calculate score based on
            //original question answer from question entity
            //and student answer from request get with answer + index(number) as "name"

            foreach($questionIdsArray as $index => $questionId ){
                //Validation for questionId because exam entity's questionId is string array and
                //it initialize with " "
                if($questionId != " " && $questionId!=null){
                $newResult = new Result();

                $em=$this->getDoctrine()->getManager();

                $questionData = $this->getDoctrine()->getRepository(Question::class)
                    ->find($questionId);

                $newResult->setDate(new \DateTime());

                $newResult->setStudent($student);

                $newResult->setQuestion($questionData);

                //Here is used request get method is used
                    //in exam, student answer input has attribute name as answer + "index"(number)

                    //Here without variable type conversion it won't work so i had to convert

                $answerIndex = "answer" . (int)$questionIdsArray[$index] ;

                $studentAnswer = $request->request->get($answerIndex);

                //validation if student answer is array which means multiple answers or not
                    if(is_array($studentAnswer)){
                        $newResult -> setStudentAnswer(implode(',',$studentAnswer));
                    }
                    else{
                        $newResult -> setStudentAnswer($studentAnswer);
                    }

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

                $newResult ->setExam($examData);

//Here getting original correct answers from question entity
                $correctAnswer = $questionData->getAnswers();

//because answer column also stored as string array so remove the white space and make as array
                $correctAnswer = str_replace(' ', '',$correctAnswer);
                $correctAnswerArray = explode(',', $correctAnswer);

                //validation for the in case student answers and original correct answer length, if different then wrong answer
                if(count($studentAnswer) != count($correctAnswerArray)){
                    $newResult->setIsCorrect(false);
                    $totalQuestion++;
                }
                else if(count($correctAnswerArray) === 1){
                    //when answer is only one then make capital and remove the white space for checking correct
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
                    }
                }
                //Here is the when answers are more than one
                else{
                    $answerCheck = false;

                   $refinedAnswer = array_map('strtoupper',$correctAnswerArray);

                   //check the student answer is from original answers with in_array

                   foreach($studentAnswer as $i){
                       if(in_array(strtoupper($i),$refinedAnswer)){
                           $answerCheck = true;
                           continue;
                       }
                       else{
                           $answerCheck = false;
                           continue;
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

        $request->getSession()
            ->getFlashBag()
            ->add('msg',
                'Exam Submitted!');

            return $this->redirectToRoute('student_main');
    }
}