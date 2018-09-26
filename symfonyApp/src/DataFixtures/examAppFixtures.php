<?php
/**
 * Created by PhpStorm.
 * User: kihun
 * Date: 24/09/2018
 * Time: 23:13
 */

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

use App\Entity\Question;

class examAppFixtures extends Fixture
{
    public function load(ObjectManager $manager){
        $dummyData1 = new Question();
        $dummyData1->setQuestion('is this test working?');
        $dummyData1->setCategory('test');
        $dummyData1->setExamples('test1, test2, test3, test4');
        $dummyData1->setAnswers('test1, test2');
        $dummyData1->setDate(new \DateTime());
        $manager->persist($dummyData1);

        $dummyData2 = new Question();
        $dummyData2->setQuestion('is this test2 working?');
        $dummyData2->setCategory('test');
        $dummyData2->setExamples('test1,test2, test3, test4');
        $dummyData2->setAnswers('test3, test4');
        $dummyData2->setDate(new \DateTime());
        $manager->persist($dummyData2);

        $manager->flush();
    }
}