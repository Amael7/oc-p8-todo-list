<?php

namespace App\Manager;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserManager
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    public function __construct(UserRepository $userRepository, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher)
    {
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
        $this->passwordHasher = $passwordHasher;
    }

    /**
     * Handle users list recovery from database.
     *
     * @return void
     */
    public function handleListAction()
    {
        return $this->userRepository->findAll();
    }

    /**
     * Handle user creation or edition in database.
     *
     * @param User   $user
     * @param bool   $persist
     * @param string $password
     *
     * @return void
     */
    public function handleCreateOrUpdate(User $user, bool $persist = true, string $password = null)
    {
        if (null !== $user->getPassword()) {
            $password = $this->passwordHasher->hashPassword($user, $user->getPassword());
        }
        $user->setPassword($password);
        if ($persist) {
            $this->entityManager->persist($user);
        }
        $this->entityManager->flush();
    }
}