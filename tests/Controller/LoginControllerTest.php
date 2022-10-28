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

  /**
   * Test login with valid credentials.
   */
  public function testLoginValidCredentials(): void
  {
    $crawler = $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('login'));

    $form = $crawler->selectButton('Se connecter')->form();
    $form['_username'] = 'user1';
    $form['_password'] = 'password';
    $this->client->submit($form);

    $crawler = $this->client->followRedirect();
    $this->assertSame(1, $crawler->filter('html:contains("Bienvenue sur Todo List, l\'application vous permettant de gérer l\'ensemble de vos tâches sans effort !")')->count());
    $this->assertSelectorExists('a', 'Créer une nouvelle tâche');
    $this->assertSelectorExists('a', 'Consulter la liste des tâches à faire');
    $this->assertSelectorExists('a', 'Consulter la liste des tâches terminées');
  }

  /**
   * Test login with invalid credentials.
   */
  public function testLoginInvalidCredentials(): void
  {
    $crawler = $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('login'));
    $form = $crawler->selectButton('Se connecter')->form();
    $form['_username'] = 'user77';
    $form['_password'] = 'monument';
    $this->client->submit($form);
    $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
    $crawler = $this->client->followRedirect();
    $this->assertSelectorExists('.alert.alert-danger', 'Invalid credentials.');
    $this->assertSelectorTextSame('button', 'Se connecter');
  }
}