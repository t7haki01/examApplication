<?php
/**
 * Created by PhpStorm.
 * User: kihun
 * Date: 03/10/2018
 * Time: 16:46
 */

namespace App\Controller;


use App\Entity\Student;
use App\Entity\Teacher;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class Test extends AbstractController
{
    public function dummyStudentMaker(){
        $teacher1 = new Teacher();
        $em = $this-> getDoctrine()-> getManager();

        $teacher1->setFirstname('teacher');
        $teacher1->setLastname('test2');
        $teacher1->setUsername('teacherTest');
        $teacher1->setPassword('test');
        $teacher1->setEmail('teacher2@test.com');

        $em -> persist($teacher1);
        $em->flush();
        return ;
    }
}