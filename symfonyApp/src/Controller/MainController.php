<?php
/**
 * Created by PhpStorm.
 * User: kihun
 * Date: 09/10/2018
 * Time: 10:11
 */

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainController extends AbstractController
{
    public function index(){
        return $this->render('main/index.html.twig');
    }

    public function teacherLogin(){
        return $this->render('main/teacher_login.html.twig');
    }

    public function studentLogin(){
        return $this->render('main/student_login.html.twig');
    }

    public function loginCheck(){
        $user=$this->getUser();
        $role = $user->getIsTeacher();
        if($role == true){
            return $this->redirectToRoute('teacher_main');
        }
        else{
            return $this->redirectToRoute('student_main');
        }
    }
}