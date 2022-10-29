<?php

namespace App\tests\Repository;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserRepositoryTest extends KernelTestCase
{
  /**
   * @var \Doctrine\ORM\EntityManager
   */
  private $entityManager;
  private $repository;


  protected function setUp(): void
  {
    $kernel = self::bootKernel();
    $this->entityManager = $kernel->getContainer()
      ->get('doctrine')
      ->getManager();
    $this->repository = $this->entityManager->getRepository(User::class);
  }

  public function testUserFixturesNumber()
  {
    $tasks = static::getContainer()->get(UserRepository::class)->count([]);
    $this->assertSame(11, $tasks);
  }

  public function testSearchByName()
  {
    $user = $this->repository->findOneBy(['username' => 'user1']);
    $this->assertSame('user1', $user->getUsername());
  }

  public function testSave()
  {
    $user = new User();
    $user->setUsername('testUser');
    $user->setPassword('password');
    $user->setEmail('test@gmail.com');
    $this->repository->save($user);
    $this->entityManager->flush($user);

    $user = $this->repository->findOneBy(['username' => 'testUser']);
    $this->assertSame('testUser', $user->getUsername());
  }

  public function testRemove()
  {
    $user = $this->repository->findOneBy(['username' => 'user1']);
    $this->repository->remove($user);
    $this->entityManager->flush($user);

    $user = $this->repository->findOneBy(['username' => 'user1']);
    $this->assertSame(null, $user);
  }
  
  protected function tearDown(): void
  {
    parent::tearDown();
    // doing this is recommended to avoid memory leaks
    $this->entityManager->close();
    $this->entityManager = null;
  }
}