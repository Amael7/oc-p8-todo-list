<?php

namespace App\tests\Controller;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LoginControllerTest extends WebTestCase
{
  private $client = null;

  public function setUp(): void
  {
    $this->client = static::createClient();
    $this->userRepository = $this->client->getContainer()->get('doctrine.orm.entity_manager')->getRepository(User::class);
    $this->user = $this->userRepository->findOneByEmail('user1@hotmail.com');
    $this->urlGenerator = $this->client->getContainer()->get('router.default');
  }

  /**
   * Test login page not authenticated user.
   */
  public function testLoginPage(): void
  {
    $crawler = $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('login'));
    $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    $this->assertSelectorExists('form');
    $this->assertSame(1, $crawler->filter('html:contains("Nom d\'utilisateur :")')->count());
    $this->assertSame(1, $crawler->filter('html:contains("Mot de passe :")')->count());
    $this->assertCount(3, $crawler->filter('input'));
    $this->assertSelectorTextSame('button', 'Se connecter');
  }

  
}