<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    /**
     * @var UserPasswordHasherInterface
     */
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    /**
     * Load user fixtures to database.
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 10; ++$i) {
            $user = new User();
            $user->setEmail('user'.$i.'@hotmail.com')
                ->setUsername('user'.$i)
                ->setPassword($this->passwordHasher->hashPassword($user, 'password'));

            $manager->persist($user);
            $this->addReference('user'.$i, $user);
        }

        $admin = new User();
        $admin->setEmail('admin1@hotmail.com')
            ->setUsername('admin1')
            ->setPassword($this->passwordHasher->hashPassword($user, 'password'))
            ->setRoles(['ROLE_ADMIN']);

        $manager->persist($admin);
        $this->addReference('admin'.$i, $admin);

        $manager->flush();
    }
}