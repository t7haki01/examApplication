<?php
/**
 * Created by PhpStorm.
 * User: kihun
 * Date: 03/10/2018
 * Time: 16:46
 */

namespace App\Controller;


use App\Entity\Result;
use App\Entity\Student;
use App\Entity\Teacher;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class Test extends AbstractController
{
    public function test(){
        $newTeacher= new Teacher();
        $newTeacher->setFirstname('test');
        $newTeacher->setLastname('teacher1');
        $newTeacher->setUsername('teacher1');
        $newTeacher->setEmail('teacher1@test.com');
        $newTeacher->setPassword('test1');
        $em = $this->getDoctrine()->getManager();
        $em->persist($newTeacher);
        $em->flush();

        return new Response("test ok");
    }
}