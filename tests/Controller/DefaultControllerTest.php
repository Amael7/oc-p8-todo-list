<?php

namespace App\tests\Controller;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
  private $client = null;

  public function setUp(): void
  {
    $this->client = static::createClient();
    $this->userRepository = $this->client->getContainer()->get('doctrine.orm.entity_manager')->getRepository(User::class);
    $this->user = $this->userRepository->findOneByEmail('user1@hotmail.com');
    $this->urlGenerator = $this->client->getContainer()->get('router.default');
  }

  public function createCrawlerHomepageAsAuthenticated()
  {
    $this->client->loginUser($this->user);
    $crawler = $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('homepage'));
    return $crawler;
  }
    
  /**
   * Check homepage when not authenticated as login button
   */
  public function testHomepageAsNotAuthenticated(): void
  {
    $crawler = $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('homepage'));
    $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    $this->assertSame(1, $crawler->filter('html:contains("Bienvenue sur Todo List, l\'application vous permettant de gérer l\'ensemble de vos tâches sans effort !")')->count());
    $this->assertSelectorExists('a', 'Se déconnecter');
  }

  /**
   * Check homepage when authenticated as logout button
   */
  public function testHomepageAsAuthenticated(): void
  {
    $crawler = $this->createCrawlerHomepageAsAuthenticated();
    $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    $this->assertSame(1, $crawler->filter('html:contains("Bienvenue sur Todo List, l\'application vous permettant de gérer l\'ensemble de vos tâches sans effort !")')->count());
    $this->assertSelectorExists('a', 'Se connecter');
  }

  /**
   * Check task creation link
   */
  public function testValidTaskCreationLink(): void
  {
    $crawler = $this->createCrawlerHomepageAsAuthenticated();
    $link = $crawler->selectLink('Créer une nouvelle tâche')->link();
    $crawler = $this->client->click($link);
    $this->assertSelectorExists('form');
    $this->assertSelectorTextSame('button', 'Ajouter');
  }

  /**
   * Test validity of to do task list link
   */
  public function testValidToDoTaskListLink(): void
  {
    $crawler = $this->createCrawlerHomepageAsAuthenticated();
    $link = $crawler->selectLink('Consulter la liste des tâches à faire')->link();
    $crawler = $this->client->click($link);
    $this->assertSame(8, $crawler->filter('.thumbnail')->count());
    $this->assertSame(8, $crawler->filter('.glyphicon-remove')->count());
    $this->assertSelectorNotExists('.glyphicon-ok');
  }

  /**
   * Test validity of done task list link
   */
  public function testValidIsDoneTaskListLink(): void
  {
    $crawler = $this->createCrawlerHomepageAsAuthenticated();
    $link = $crawler->selectLink('Consulter la liste des tâches terminées')->link();
    $crawler = $this->client->click($link);
    $this->assertSame(2, $crawler->filter('.thumbnail')->count());
    $this->assertSame(2, $crawler->filter('.glyphicon-ok')->count());
    $this->assertSelectorNotExists('.glyphicon-remove');
  }

  /**
   * Test validity of create user link
   */
  public function testValidCreateUserLink(): void
  {
    $this->user = $this->userRepository->findOneByEmail('admin1@hotmail.com');
    $crawler = $this->createCrawlerHomepageAsAuthenticated();
    $link = $crawler->selectLink('Créer un utilisateur')->link();
    $crawler = $this->client->click($link);
    $this->assertSelectorTextSame('h1', 'Créer un utilisateur');
  }

  /**
   * Test validity of logout link
  */
  public function testValidLogoutLink()
  {
    $crawler = $this->createCrawlerHomepageAsAuthenticated();
    $link = $crawler->selectLink('Se déconnecter')->link();
    $crawler = $this->client->click($link);
    $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
    $this->assertSelectorExists('a', 'Se connecter');
  }
}