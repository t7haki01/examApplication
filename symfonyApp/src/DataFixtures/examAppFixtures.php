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
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

use App\Entity\Question;

class examAppFixtures extends Fixture
{
    public function load(ObjectManager $manager){

        $teacher1 = new Teacher();
        $manager->persist($teacher1);
        $teacher2 = new Teacher();
        $manager->persist($teacher2);

        $dummyUser1 = new User();
        $dummyUser1->setFirstname('John');
        $dummyUser1->setLastname('Doe');
        $dummyUser1->setUsername('teacher1');
        $dummyUser1->setPassword(password_hash('test1',PASSWORD_BCRYPT));
        $dummyUser1->setEmail('test1@test.com');
        $dummyUser1->setIsTeacher(true);

        $dummyUser1->setTeacher($teacher1);
        $manager->persist($dummyUser1);


        $dummyUser2 = new User();
        $dummyUser2->setFirstname('Frank');
        $dummyUser2->setLastname('Lampard');
        $dummyUser2->setUsername('teacher2');
        $dummyUser2->setPassword(password_hash('test2',PASSWORD_BCRYPT));
        $dummyUser2->setEmail('test2@test.com');
        $dummyUser2->setIsTeacher(true);
        $dummyUser2->setTeacher($teacher2);
        $manager->persist($dummyUser2);


        $student1 = new Student();
        $manager->persist($student1);

        $student2 = new Student();
        $manager->persist($student2);

        $student3 = new Student();
        $manager->persist($student3);

        $testUser = new User();
        $testUser->setFirstname('Test');
        $testUser->setLastname('Student');
        $testUser->setEmail('noemail');
        $testUser->setIsTeacher(false);
        $testUser->setUsername('test');
        $testUser->setPassword(password_hash('student', PASSWORD_BCRYPT));
        $testUser->setStudent($student3);
        $manager->persist($testUser);

        $dummyStudent1 = new User();
        $dummyStudent1->setFirstname('Lionel');
        $dummyStudent1->setLastname('Messi');
        $dummyStudent1->setUsername('student1');
        $dummyStudent1->setPassword(password_hash('test1', PASSWORD_BCRYPT));
        $dummyStudent1->setEmail('student1@test.com');
        $dummyStudent1->setIsTeacher(false);
        $dummyStudent1->setStudent($student1);
        $manager->persist($dummyStudent1);

        $dummyStudent2 = new User();
        $dummyStudent2->setFirstname('Brad');
        $dummyStudent2->setLastname('Pitt');
        $dummyStudent2->setUsername('student2');
        $dummyStudent2->setPassword(password_hash('test2', PASSWORD_BCRYPT));
        $dummyStudent2->setEmail('student2@test.com');
        $dummyStudent2->setIsTeacher(false);
        $dummyStudent2->setStudent($student2);
        $manager->persist($dummyStudent2);

        $dummyData1 = new Question();
        $dummyData1->setQuestion('What is the answer of 1 + 1 ?');
        $dummyData1->setCategory('Math');
        $dummyData1->setExamples('1, 2, 3, 4');
        $dummyData1->setAnswers('2');
        $dummyData1->setDate(new \DateTime());
        $dummyData1->setTeacher($teacher1);
        $manager->persist($dummyData1);

        $dummyData2 = new Question();
        $dummyData2->setQuestion('What is the answer of 7 x 8 ?');
        $dummyData2->setCategory('Math');
        $dummyData2->setExamples('56, 65, 49, 15, 78');
        $dummyData2->setAnswers('56');
        $dummyData2->setDate(new \DateTime());
        $dummyData2->setTeacher($teacher1);
        $manager->persist($dummyData2);

        $dummyData3 = new Question();
        $dummyData3->setQuestion('What is yes in Finnish');
        $dummyData3->setCategory('Finnish');
        $dummyData3->setExamples('kyllä, joo, ei, niin');
        $dummyData3->setAnswers('kyllä, joo');
        $dummyData3->setDate(new \DateTime());
        $dummyData3->setTeacher($teacher1);
        $manager->persist($dummyData3);

        //from here tanja
        $dummyData1 = new Question();
        $dummyData1->setQuestion('');
        $dummyData1->setCategory('Finnish');
        $dummyData1->setExamples('1, 2, 3, 4');
        $dummyData1->setAnswers('2');
        $dummyData1->setDate(new \DateTime());
        $dummyData1->setTeacher($teacher1);
        $manager->persist($dummyData1);


        //until here

        $manager->flush();
    }
}