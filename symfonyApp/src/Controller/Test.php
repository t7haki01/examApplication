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
        return new Response("login failure");
    }
}