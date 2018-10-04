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

class Test extends AbstractController
{
    public function test(){
        for($i=0;$i<30;$i++){
        $result = $this->getDoctrine()->getRepository(Result::class)
            ->find($i);
        $em = $this->getDoctrine()->getManager();
        $em->remove($result);
        $em->flush();
        }


        return ;
    }
}