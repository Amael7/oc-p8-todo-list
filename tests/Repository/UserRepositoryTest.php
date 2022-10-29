<?php

namespace App\tests\Repository;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
class UserRepositoryTest extends KernelTestCase
{
  public function testUserFixturesNumber()
  {
    $users = static::getContainer()->get(UserRepository::class)->count([]);
    $this->assertSame(11, $users);
  }
}