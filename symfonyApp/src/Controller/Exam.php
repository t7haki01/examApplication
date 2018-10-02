<?php
/**
 * Created by PhpStorm.
 * User: kihun
 * Date: 02/10/2018
 * Time: 22:03
 */

namespace App\Controller;


use App\Entity\Question;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class Exam extends AbstractController
{
    public function examShow($examId){
        $questions = $this->getDoctrine()->getRepository(Question::class)->findAll();
        $examData = $this->getDoctrine()->getRepository(\App\Entity\Exam::class)->find($examId);

        return $this->render('exam/showExam.html.twig',
            array('examData' => $examData,
                'questions' => $questions));

    }

}