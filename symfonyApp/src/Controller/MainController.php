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
}