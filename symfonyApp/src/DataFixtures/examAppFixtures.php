<?php
/**
 * Created by PhpStorm.
 * User: kihun
 * Date: 24/09/2018
 * Time: 23:13
 */

namespace App\DataFixtures;

use App\Entity\Teacher;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

use App\Entity\Question;

class examAppFixtures extends Fixture
{
    public function load(ObjectManager $manager){
        $dummyUser1 = new Teacher();
        $dummyUser1->setFirstname('John');
        $dummyUser1->setLastname('Doe');
        $dummyUser1->setUsername('test1');
        $dummyUser1->setPassword('test1');
        $dummyUser1->setEmail('test1@test.com');
        $manager->persist($dummyUser1);

        $dummyData1 = new Question();
        $dummyData1->setQuestion('is this test working?');
        $dummyData1->setCategory('test');
        $dummyData1->setExamples('test1, test2, test3, test4');
        $dummyData1->setAnswers('test1, test2');
        $dummyData1->setDate(new \DateTime());
        $dummyData1->setTeacher($dummyUser1);
        $manager->persist($dummyData1);

        $dummyData2 = new Question();
        $dummyData2->setQuestion('is this test2 working?');
        $dummyData2->setCategory('test');
        $dummyData2->setExamples('test1,test2, test3, test4');
        $dummyData2->setAnswers('test3, test4');
        $dummyData2->setDate(new \DateTime());
        $dummyData2->setTeacher($dummyUser1);
        $manager->persist($dummyData2);

        $manager->flush();
    }
}