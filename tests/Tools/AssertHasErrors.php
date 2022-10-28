<?php

namespace App\Tests\Tools;

trait AssertHasErrors
{
  public function assertHasErrors($entity, int $number = 0)
  {
      self::bootKernel();
      $errors = static::getContainer()->get('validator')->validate($entity);
    $messages = [];
    /** @var ConstraintViolation $error */
    foreach ($errors as $error) {
        $messages[] = $error->getPropertyPath().' => '.$error->getMessage();
    }
      $this->assertCount($number, $errors, implode(', ', $messages));
  }
}