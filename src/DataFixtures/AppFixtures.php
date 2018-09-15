<?php

namespace App\DataFixtures;

use App\Entity\Student;
use App\Entity\Subject;
use App\Entity\Task;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $this->loadStudents($manager);
        $this->loadSubjects($manager);
        $this->loadTasks($manager);
    }

    private function loadStudents(ObjectManager $manager)
    {
        $users = [
            [ 'admin', ['ROLE_ADMIN'] ],
            [ 'sgomez', ['ROLE_USER'] ],
        ];

        foreach ($users as $user) {
            $entity = new Student();
            $entity->setUsername($user[0]);
            $entity->setEmail("{$user[0]}@localhost.localdomain");
            $entity->setRoles($user[1]);
            $password = $this->encoder->encodePassword($entity, 'secret');
            $entity->setPassword($password);

            $manager->persist($entity);
            $this->addReference($user[0], $entity);
        }

        $manager->flush();
    }

    private function loadSubjects(ObjectManager $manager)
    {
        $subjects = [
            ['Programación Web', 3],
            ['Cálculo', 1],
            ['Álgebra', 1],
            ['Base de datos', 2],
            ['Física', 1],
            ['Ingeniería del Software', 2],
        ];

        foreach ($subjects as $subject) {
            $entity = new Subject();
            $entity->setName($subject[0]);
            $entity->setYear($subject[1]);

            $manager->persist($entity);
            $this->addReference("subject:{$subject[0]}", $entity);
        }

        $manager->flush();
    }

    private function loadTasks(ObjectManager $manager)
    {
        $tasks = [
            ['Programación Web', 'Práctica 1', '-2 days noon'],
            ['Programación Web', 'Práctica 2', '+3 days noon'],
            ['Programación Web', 'Práctica 3', '+6 days noon'],
            ['Física', 'Práctica 1', '-1 days noon'],
            ['Física', 'Práctica 2', '+2 days noon'],
            ['Física', 'Práctica 3', '+4 days noon'],
        ];

        foreach ($tasks as $task) {
            $entity = new Task();
            $entity->setTitle($task[1]);
            $entity->setDescription($task[1]);
            $entity->setDeadlineAt(new \DateTime($task[2]));
            $entity->setSubject($this->getReference("subject:{$task[0]}"));

            $manager->persist($entity);
        }

        $manager->flush();
    }
}
