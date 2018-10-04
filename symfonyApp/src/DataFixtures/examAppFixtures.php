<?php
/**
 * Created by PhpStorm.
 * User: kihun
 * Date: 24/09/2018
 * Time: 23:13
 */

namespace App\DataFixtures;

use App\Entity\Student;
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

        $dummyUser2 = new Teacher();
        $dummyUser2->setFirstname('Teacher');
        $dummyUser2->setLastname('test');
        $dummyUser2->setUsername('test2');
        $dummyUser2->setPassword('test2');
        $dummyUser2->setEmail('test2@test.com');
        $manager->persist($dummyUser2);

        $dummyStudent1 = new Student();
        $dummyStudent1->setFirstname('student');
        $dummyStudent1->setLastname('test1');
        $dummyStudent1->setUsername('student1');
        $dummyStudent1->setPassword('test1');
        $manager->persist($dummyStudent1);

        $dummyStudent2 = new Student();
        $dummyStudent2->setFirstname('student');
        $dummyStudent2->setLastname('test2');
        $dummyStudent2->setUsername('student2');
        $dummyStudent2->setPassword('test2');
        $manager->persist($dummyStudent2);

        $dummyData1 = new Question();
        $dummyData1->setQuestion('What is the answer of 1 + 1 ?');
        $dummyData1->setCategory('Math');
        $dummyData1->setExamples('1, 2, 3, 4');
        $dummyData1->setAnswers('2');
        $dummyData1->setDate(new \DateTime());
        $dummyData1->setTeacher($dummyUser1);
        $manager->persist($dummyData1);

        $dummyData2 = new Question();
        $dummyData2->setQuestion('What is yes in Finnish');
        $dummyData2->setCategory('Finnish');
        $dummyData2->setExamples('kyllä, joo, ei, niin');
        $dummyData2->setAnswers('kyllä, joo');
        $dummyData2->setDate(new \DateTime());
        $dummyData2->setTeacher($dummyUser1);
        $manager->persist($dummyData2);

        $manager->flush();
    }
}