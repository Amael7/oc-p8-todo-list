<?php

namespace App\tests\Entity;

use App\Entity\Task;
use App\Entity\User;
use App\Tests\Tools\AssertHasErrors;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserTest extends KernelTestCase
{
  use AssertHasErrors;
  
  /**
   * Create a valid user entity for test.
   */
  public function getEntity(): User
  {
      $user = new User();
      $user->setEmail('email@hotmail.com');
      $user->setUsername('Username');
      $user->setPassword('password');

      return $user;
  }

  /**
   * Check valid user entity.
   */
  public function testValidEntity(): void
  {
    $this->assertHasErrors($this->getEntity());
  }

  /**
   * Check invalid user entity.
   */
  public function testInvalidEntity(): void
  {
      $user = $this->getEntity();
      $user->setEmail('invalidUserEmail.fr');
      $user->setUsername('');
      $this->assertHasErrors($user, 2);
  }

  /**
   * Assert User unicity with email.
   */
  public function testInvalidUniqueEmail(): void
  {
      $user = $this->getEntity();
      $user->setEmail('user2@hotmail.com');
      $this->assertHasErrors($user, 1);
  }

  public function testAddRemoveTask()
  {
      $user = $this->getEntity();
      for ($i = 1; $i <= 5; ++$i) {
          $task = new Task();
          $task->setTitle('task'.$i)
              ->setContent('content'.$i)
              ->setAuthor($user);
          $user->addTask($task);
      }
      $tasks = $user->getTasks();
      $this->assertSame(5, \count($tasks));

      $user->removeTask($tasks[0]);
      $user->removeTask($tasks[2]);
      $this->assertSame(3, \count($tasks));
  }
}