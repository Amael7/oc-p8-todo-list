<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
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
                ->setPassword($this->encoder->encodePassword($user, 'password'));

            $manager->persist($user);
            $this->addReference('user'.$i, $user);
        }

        $admin = new User();
        $admin->setEmail('admin1@hotmail.com')
            ->setUsername('admin1')
            ->setPassword($this->encoder->encodePassword($user, 'password'))
            ->setRoles(['ROLE_ADMIN']);

        $manager->persist($admin);
        $this->addReference('admin'.$i, $admin);

        $manager->flush();
    }
}