<?php

namespace App\DataFixtures;

use App\Entity\Task;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class TaskFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * Load task fixtures.
     *
     * @param ObjectManager $manager
     *
     * @return void
     */
    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create('fr_FR');

        for ($i = 1; $i <= 10; ++$i) {
            $task = new Task();
            $task->setTitle('tache '.$i)
                ->setContent($faker->text(mt_rand(50, 150)));
            if ($i == 3) {
                $task->setIsDone(true);
            }
            if ($i == 4) {
                $task->setAuthor($this->getReference('user1'));
            }
            if ($i == 5) {
                $task->setAuthor($this->getReference('user2'));
                $task->setIsDone(true);
            }
            if ($i == 6) {
                $task->setAuthor($this->getReference('user2'));
            }
            $manager->persist($task);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
        ];
    }
}