<?php

namespace App\tests\Repository;

use App\Repository\TaskRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TaskRepositoryTest extends KernelTestCase
{
  public function testTaskFixturesNumber()
  {
    $tasks = static::getContainer()->get(TaskRepository::class)->count([]);
    $this->assertSame(10, $tasks);
  }
}