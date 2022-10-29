<?php

namespace App\tests\Repository;

use App\Entity\Task;
use App\Repository\TaskRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TaskRepositoryTest extends KernelTestCase
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
    $this->repository = $this->entityManager->getRepository(Task::class);
  }

  public function testTaskFixturesNumber()
  {
    $tasks = static::getContainer()->get(TaskRepository::class)->count([]);
    $this->assertSame(10, $tasks);
  }

  public function testSave()
  {
    $task = new Task();
    $task->setTitle('title1');
    $task->setContent('Content1');
    $this->repository->save($task);
    $this->entityManager->flush($task);

    $task = $this->repository->findOneBy(['title' => 'title1']);
    $this->assertSame('title1', $task->getTitle());
  }

  public function testRemove()
  {
    $task = $this->repository->findOneBy(['title' => 'tache 1']);
    $this->repository->remove($task);
    $this->entityManager->flush($task);

    $task = $this->repository->findOneBy(['title' => 'tache 1']);
    $this->assertSame(null, $task);
  }

  protected function tearDown(): void
  {
    parent::tearDown();
    // doing this is recommended to avoid memory leaks
    $this->entityManager->close();
    $this->entityManager = null;
  }
}