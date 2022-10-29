<?php

namespace App\tests\Entity;

use App\Entity\Task;
use DateTimeImmutable;
use App\Tests\Tools\AssertHasErrors;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TaskTest extends KernelTestCase
{
  use AssertHasErrors;
  
  /**
   * Create a valid task entity for test.
   */
  public function getEntity(): Task
  {
    $task = new Task();
    $task->setTitle('title');
    $task->setContent('content');
    return $task;
  }

  /**
   * Check valid task entity.
   */
  public function testValidEntity(): void
  {
    $this->assertHasErrors($this->getEntity());
  }

  /**
   * Check invalid blank task entity.
   */
  public function testInvalidBlankEntity(): void
  {
    $task = $this->getEntity();
    $task->setTitle('');
    $task->setContent('');
    $this->assertHasErrors($task, 2);
  }

  /**
   * Check toggle task entity.
   */
  public function testToggleDone(): void
  {
    $task = $this->getEntity();
    $task->toggle(true);
    $this->assertTrue($task->isIsDone());
  }

  /**
   * Check toggle task entity.
   */
  public function testToggleFalse(): void
  {
    $task = $this->getEntity();
    $task->toggle(false);
    $this->assertFalse($task->isIsDone());
  }

  /**
   * Check toggle task entity.
   */
  public function testSetIsDone(): void
  {
    $task = $this->getEntity();
    $task->SetIsDone(true);
    $this->assertTrue($task->isIsDone());
  }

  /**
   * Check toggle task entity.
   */
  public function testCreatedAt(): void
  {
    $task = $this->getEntity();
    $task->setCreatedAt(new \DateTimeImmutable());
    $this->assertInstanceOf(DateTimeImmutable::class, $task->getCreatedAt());
  }
}