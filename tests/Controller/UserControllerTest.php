<?php

namespace App\tests\Controller;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
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
   * Test Redirection to login route for visitors trying to access pages that require authenticated status.
   */
  public function testAccessiblePagesAsNotAuthenticated()
  {
    $routes = [
      ['user_list'],
      ['user_create'],
    ];

    $this->client->loginUser($this->user);

    foreach ($routes as $route) {
      $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate($route[0]));
      $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }
    $id = $this->userRepository->findOneByEmail('user1@hotmail.com')->getId();
    $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('user_edit', ['id' => $id]));
    $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
  }

  /**
   * Test Redirection for authenticated users trying to access pages that require Admin status.
   */
  public function testAccessiblePagesAsUsersAuthenticated()
  {
    $routes = [
      ['user_list'],
      ['user_create'],
    ];

    $this->client->loginUser($this->user);

    foreach ($routes as $route) {
      $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate($route[0]));
      $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }
    $id = $this->userRepository->findOneByEmail('user1@hotmail.com')->getId();
    $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('user_edit', ['id' => $id]));
    $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
  }

  /**
   * Test Redirection for authenticated admin trying to access pages that require Admin status.
   */
  public function testAccessiblePagesAsAdminAuthenticated()
  {
    $routes = [
      ['user_list'],
      ['user_create'],
    ];

    $this->user = $this->userRepository->findOneByEmail('admin1@hotmail.com');
    $this->client->loginUser($this->user);

    foreach ($routes as $route) {
      $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate($route[0]));
      $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }
    $id = $this->userRepository->findOneByEmail('user1@hotmail.com')->getId();
    $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('user_edit', ['id' => $id]));
    $this->assertResponseStatusCodeSame(Response::HTTP_OK);
  }

  // /**
  //  * Test valid user list when admin.
  //  */
  // public function testAdminUserListAction(): void
  // {
  //   $this->user = $this->userRepository->findOneByEmail('admin1@hotmail.com');
  //   $this->client->loginUser($this->user);
  //   $crawler = $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('user_list'));
  //   $this->assertSame(1, $crawler->filter('a:contains("Créer un utilisateur")')->count());
  //   $this->assertSelectorTextSame('h1', 'Liste des utilisateurs');
  //   $this->assertSelectorExists('table');
  //   $this->assertSame(1, $crawler->filter('th:contains("Nom d\'utilisateur")')->count());
  //   $this->assertSame(1, $crawler->filter('th:contains("Adresse d\'utilisateur")')->count());
  //   $this->assertSame(1, $crawler->filter('th:contains("Actions")')->count());
  //   $this->assertGreaterThanOrEqual(1, $crawler->filter('a:contains("Edit")')->count());
  // }

  // /**
  //  * Test validity of create user link.
  //  */
  // public function testValidCreateUserLinkUsersPage(): void
  // {
  //   $this->user = $this->userRepository->findOneByEmail('admin1@hotmail.com');
  //   $this->client->loginUser($this->user);
  //   $crawler = $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('user_list'));
  //   $link = $crawler->selectLink('Créer un utilisateur')->link();
  //   $crawler = $this->client->click($link);
  //   $this->assertSelectorTextSame('h1', 'Créer un utilisateur');
  // }

  // /**
  //  * Test new valid user creation.
  //  */
  // public function testValidUserCreationByAdmin(): void
  // {
  //   $this->user = $this->userRepository->findOneByEmail('admin1@hotmail.com');
  //   $this->client->loginUser($this->user);
  //   $crawler = $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('user_create'));

  //   $form = $crawler->selectButton('Ajouter')->form();
  //   $form['user[username]'] = 'newUserTest';
  //   $form['user[password][first]'] = 'newPassword';
  //   $form['user[password][second]'] = 'newPassword';
  //   $form['user[email]'] = 'emailTest@hotmail.com';
  //   $form['user[roles]']->select('ROLE_USER');
  //   $this->client->submit($form);

  //   $this->assertResponseStatusCodeSame(302);
  //   $crawler = $this->client->followRedirect();
  //   $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());
  //   $this->assertSelectorTextSame('h1', 'Liste des utilisateurs');
  //   $this->assertSame(1, $crawler->filter('td:contains("newUserTest")')->count());
  //   $this->assertSame(1, $crawler->filter('td:contains("emailTest@hotmail.com")')->count());
  //   $this->assertSame(12, $crawler->filter('td:contains("User")')->count());
  // }

  // /**
  //  * Test new invalid user creation.
  //  */
  // public function testInvalidUserCreationByAdmin(): void
  // {
  //   $this->user = $this->userRepository->findOneByEmail('admin1@hotmail.com');
  //   $this->client->loginUser($this->user);
  //   $crawler = $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('user_create'));

  //   $form = $crawler->selectButton('Ajouter')->form();
  //   $form['user[username]'] = 'invalidUser';
  //   $form['user[password][first]'] = 'newPassword';
  //   $form['user[password][second]'] = 'invalidPassword';
  //   $form['user[email]'] = 'email@test.com';
  //   $form['user[roles]']->select('ROLE_USER');
  //   $this->client->submit($form);
  //   $this->assertSelectorTextSame('h1', 'Créer un utilisateur');
  //   $this->assertSelectorTextSame('li', 'Les deux mots de passe doivent correspondre.');
  // }

  // /**
  //  * Test validity of edit user link.
  //  */
  // public function testValidEditUserLinkUsersPage(): void
  // {
  //   $this->user = $this->userRepository->findOneByEmail('admin1@hotmail.com');
  //   $this->client->loginUser($this->user);
  //   $crawler = $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('user_list'));
  //   $link = $crawler->selectLink('Edit')->link();
  //   $crawler = $this->client->click($link);
  //   $this->assertSame(1, $crawler->filter('h1:contains("Modifier")')->count());
  // }

  // /**
  //  * Test user edition page for authenticated admin.
  //  */
  // public function testUserEditionPage(): void
  // {
  //   $id = $this->userRepository->findOneByEmail('user1@hotmail.com')->getId();
  //   $this->user = $this->userRepository->findOneByEmail('admin1@hotmail.com');
  //   $this->client->loginUser($this->user);
  //   $crawler = $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('user_edit', ['id' => $id]));

  //   $this->assertSelectorExists('form');
  //   $this->assertCount(5, $crawler->filter('label'));
  //   $this->assertCount(5, $crawler->filter('input'));
  //   $this->assertSame(1, $crawler->filter('input[value="user1"]')->count());
  //   $this->assertSame(1, $crawler->filter('input[value="user1@hotmail.com"]')->count());
  //   $this->assertCount(1, $crawler->filter('select'));
  //   $this->assertSelectorTextSame('button', 'Modifier');
  // }

  // /**
  //  * Test valid user edition.
  //  */
  // public function testValidUserEdition(): void
  // {
  //   $id = $this->userRepository->findOneByEmail('user1@hotmail.com')->getId();
  //   $this->user = $this->userRepository->findOneByEmail('admin1@hotmail.com');
  //   $this->client->loginUser($this->user);
  //   $crawler = $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('user_edit', ['id' => $id]));

  //   $form = $crawler->selectButton('Modifier')->form();
  //   $form['user[username]'] = 'user1Update';
  //   $form['user[email]'] = 'user1Update@hotmail.com';
  //   $form['user[password][first]'] = 'passwordUpdate';
  //   $form['user[password][second]'] = 'passwordUpdate';
  //   $form['user[roles]']->select('ROLE_ADMIN');
  //   $this->client->submit($form);

  //   $this->assertResponseRedirects('/users');
  //   $crawler = $this->client->followRedirect();
  //   $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());
  //   $this->assertSame(2, $crawler->filter('td:contains("user1Update")')->count());
  //   $this->assertSame(1, $crawler->filter('td:contains("user1Update@hotmail.com")')->count());
  //   $this->assertSame(2, $crawler->filter('td:contains("Admin")')->count());
  // }

  // /**
  //  * Test invalid user edition.
  //  */
  // public function testInvalidUserEdition(): void
  // {
  //   $id = $this->userRepository->findOneByEmail('user1Update@hotmail.com')->getId();
  //   $this->user = $this->userRepository->findOneByEmail('admin1@hotmail.com');
  //   $this->client->loginUser($this->user);
  //   $crawler = $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('user_edit', ['id' => $id]));

  //   $form = $crawler->selectButton('Modifier')->form();
  //   $form['user[password][first]'] = 'password';
  //   $form['user[password][second]'] = 'invalidPassword';
  //   $form['user[roles]']->select('ROLE_USER');
  //   $this->client->submit($form);

  //   $this->assertSame(1, $crawler->filter('h1:contains("Modifier")')->count());
  //   $this->assertSelectorTextSame('li', 'Les deux mots de passe doivent correspondre.');
  // }

  // /**
  //  * Test 404 error response.
  //  */
  // public function testUnexistingUserAction(): void
  // {
  //   $this->user = $this->userRepository->findOneByEmail('admin1@hotmail.com');
  //   $this->client->loginUser($this->user);
  //   $crawler = $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('user_edit', ['id' => 163]));
  //   $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
  // }
}