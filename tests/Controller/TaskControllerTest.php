<?php

namespace App\tests\Controller;

use App\Entity\Task;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TaskControllerTest extends WebTestCase
{
  private $client = null;

  public function setUp(): void
  {
    $this->client = static::createClient();
    $this->userRepository = $this->client->getContainer()->get('doctrine.orm.entity_manager')->getRepository(User::class);
    $this->user = $this->userRepository->findOneByEmail('user2@hotmail.com');
    $this->urlGenerator = $this->client->getContainer()->get('router.default');
  }

  /**
   * Test access to restricted pages related to tasks for authenticated user
   */
  public function testRestrictedPageAccessAuthenticated(): void
  {
    $routes = [
        ['task_todo_list'],
        ['task_done_list'],
        ['task_create'],
    ];

    $this->client->loginUser($this->user);

    foreach ($routes as $route) {
      $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate($route[0]));
      $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }
  }

  /**
   * Test integration of to do task list page for authenticated user
   */
  public function testIntegrationToDoTaskListActionAuthenticated(): void
  {
    $this->client->loginUser($this->user);
    $crawler = $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate("task_todo_list"));

    $this->assertSame(1, $crawler->filter('a:contains("Se déconnecter")')->count());
    $this->assertSame(1, $crawler->filter('a:contains("Créer une tâche")')->count());

    $this->assertSelectorExists('.caption');
    $this->assertSelectorExists('.thumbnail h4 a');
    $this->assertSame(1, $crawler->filter('.thumbnail button:contains("Supprimer")')->count());
    $this->assertSelectorExists('.glyphicon-remove');
    $this->assertSame(8, $crawler->filter('.thumbnail button:contains("Marquer comme faite")')->count());
    $this->assertSelectorNotExists('.glyphicon-ok');
    $this->assertSame(0, $crawler->filter('.thumbnail button:contains("Marquer non terminée")')->count());
  }

  /**
   * Test integration of done task list page for authenticated user
   */
  public function testIntegrationDoneTaskListActionAuthenticated(): void
  {
    $this->client->loginUser($this->user);
    $crawler = $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate("task_done_list"));

    $this->assertSame(1, $crawler->filter('a:contains("Se déconnecter")')->count());
    $this->assertSame(1, $crawler->filter('a:contains("Créer une tâche")')->count());

    $this->assertSelectorExists('.caption');
    $this->assertSelectorExists('.thumbnail h4 a');
    $this->assertSame(1, $crawler->filter('.thumbnail button:contains("Supprimer")')->count());
    $this->assertSelectorExists('.glyphicon-ok');
    $this->assertSame(0, $crawler->filter('.thumbnail button:contains("Marquer comme faite")')->count());
    $this->assertSelectorNotExists('.glyphicon-remove');
    $this->assertSame(2, $crawler->filter('.thumbnail button:contains("Marquer non terminée")')->count());
  }

  /**
   * Test new task creation
   */
  public function testTaskCreation(): void
  {
    $this->client->loginUser($this->user);
    $crawler = $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate("task_create"));

    $form = $crawler->selectButton('Ajouter')->form();
    $form['task[title]'] = 'New Task Test';
    $form['task[content]'] = 'New content Test';
    $this->client->submit($form);

    $this->assertResponseRedirects('/tasks');
    $crawler = $this->client->followRedirect();
    $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());
    $this->assertSame(1, $crawler->filter('h4 a:contains("New Task Test")')->count());
    $this->assertSame(1, $crawler->filter('p:contains("New content Test")')->count());
    $this->assertSame(2, $crawler->filter('h6:contains("Auteur: user2")')->count());
  }

  /**
   * Test validity of edit task link
   */
  public function testValidEditTaskLinkTasksPage(): void
  {
    $this->client->loginUser($this->user);
    $crawler = $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate("task_todo_list"));
    $link = $crawler->selectLink('tache 2')->link();
    $crawler = $this->client->click($link);
    $this->assertSame(1, $crawler->filter('input[value="tache 2"]')->count());
  }

  /**
   * Test integration of task edition page for authenticated user
   */
  public function testIntegrationTaskEditionPage(): void
  {
    $this->client->loginUser($this->user);
    $crawler = $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate("task_edit", ['id' => 1]));
    $this->assertSame(1, $crawler->filter('a:contains("Se déconnecter")')->count());
    $this->assertSelectorExists('form');
    $this->assertSame(1, $crawler->filter('label:contains("Title")')->count());
    $this->assertSame(1, $crawler->filter('label:contains("Content")')->count());
    $this->assertSame(1, $crawler->filter('input[value="tache 1"]')->count());
    $this->assertSelectorExists('button', 'Modifier');
    $this->assertInputValueNotSame('task[title]', '');
  }

  /**
   * Test new task edition
   */
  public function testTaskEdition(): void
  {
    $this->client->loginUser($this->user);
    $crawler = $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate("task_edit", ['id' => 1]));

    $form = $crawler->selectButton('Modifier')->form();
    $form['task[title]'] = 'updated title';
    $form['task[content]'] = 'updated content';
    $this->client->submit($form);

    $this->assertResponseRedirects('/tasks');
    $crawler = $this->client->followRedirect();
    $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());
    $this->assertSame(1, $crawler->filter('h4 a:contains("updated title")')->count());
    $this->assertSame(1, $crawler->filter('p:contains("updated content")')->count());
  }

  /**
   * Test toggle action - set task1 is_done to true
   */
  public function testToggleActionSetIsDone(): void
  {
    $this->client->loginUser($this->user);
    $crawler = $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate("task_toggle", ['id' => 7]));
    $this->assertResponseRedirects('/tasks');
    $crawler = $this->client->followRedirect();
    $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());
    $this->assertSame(0, $crawler->filter('h4 a:contains("tache 7")')->count());
  }

  /**
   * Test toggle action - set task3 is_done to false
   */
  public function testToggleActionSetIsNotDone():void
  {
    $this->client->loginUser($this->user);
    $crawler = $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate("task_toggle", ['id' => 7]));
    $this->assertResponseRedirects('/tasks');
    $crawler = $this->client->followRedirect();
    $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());
    $this->assertSame(1, $crawler->filter('h4 a:contains("tache 7")')->count());
  }

  /**
   * Test allowed delete action by author
   */
  public function testDeleteActionByAuthor(): void
  {
    $this->client->loginUser($this->user);
    $crawler = $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate("task_delete", ['id' => 6]));
    
    $this->assertResponseRedirects('/tasks');
    $crawler = $this->client->followRedirect();
    $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());
    $this->assertSame(0, $crawler->filter('h4 a:contains("tache 6")')->count());
  }

  /**
   * Test allowed delete action by not author
   */
  public function testDeleteActionByNotAuthor(): void
  {
    $this->client->loginUser($this->user);
    $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate("task_delete", ['id' => 47]));
    $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
  }
  
  /**
   * Test allowed delete action by admin
   */
  public function testDeleteActionByAdmin(): void
  {
    $this->user = $this->userRepository->findOneByEmail('admin1@hotmail.com');
    $this->client->loginUser($this->user);
    $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate("task_delete", ['id' => 2]));
    $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
  }

  /**
   * Test allowed delete action by not admin
   */
  public function testDeleteActionByNotAdmin(): void
  {
    $this->user = $this->userRepository->findOneByEmail('user3@hotmail.com');
    $this->client->loginUser($this->user);
    $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate("task_delete", ['id' => 3]));
    $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
  }

  /**
   * Test 404 error response.
   */
  public function testUnexistingTaskAction(): void
  {
    $this->client->loginUser($this->user);
    $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('task_edit', ['id' => 163]));
    $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
  }
}