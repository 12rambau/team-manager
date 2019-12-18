<?php

namespace App\tests\Controller;

use App\Entity\User;
use LogicException;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Guard\Token\PostAuthenticationGuardToken;

class DefaultControllerTest extends WebTestCase
{
    /**
     * @var KernelBrowser
     */
    protected $client = null;

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var string The locale used for the routing
     */
    protected $locale = 'en_US';

    protected function setUp()
    {
        $this->client = static::createClient();
        $this->em = self::$container->get('doctrine')->getManager();
    }

    protected function logIn(string $username = 'root', string $firewall = 'main')
    {
        $session = $this->client->getContainer()->get('session');

        $user = $this->em->getRepository(User::class)->findOneByUsername($username);

        $this->assertFalse($user == null);
        if ($user == false)
            throw new LogicException('there is not such user as ' . $username);

        $token = new PostAuthenticationGuardToken($user, $firewall, $user->getRoles());
        $session->set('_security_' . $firewall, serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $this->client->getCookieJar()->set($cookie);
    }

    public function testHome()
    {
        /************************
         **   test anonymous   **
         ***********************/

        $this->client = static::createClient();
        $this->client->request('GET', '/');

        //check if the appears without errors
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertSelectorTextContains('#first_button', 'Log in');


        /************************
         **   test Root        **
         ***********************/

        $this->setUp();
        $this->logIn();
        $this->client->request('GET', '/');

        //check if the appears without errors
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertSelectorTextContains('#first_button', 'Get Started');
    }

    public function testcontact()
    {
        /*******************
         *  route          *
         ******************/
        $this->client = static::createClient();
        $this->client->request('GET', '/' . $this->locale . '/contact-information');

        //check if the page appears without errors 
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        /*******************
         *  form           *
         ******************/

         /*
        //submit appropriate form 
        //$this->client->enableProfiler();
        $crawler = $this->client->request('GET', '/' . $this->locale . '/contact-information');
        $form = $crawler->selectButton('submit')->form();
        $name = 'contact';
        //$this->assertEquals($form->getName(), $name); GetName() is only available in 5.0

        $this->client->submit($form, [
            $name . '[subject]'    => 'Subject',
            $name . '[name]' => 'Pierrick',
            $name . '[email]' => 'pierrick@domaine.com',
            $name . '[message]' => 'Team-Manager Rocks !'
        ]);

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode()); //check return to contact
        $this->assertSelectorTextContains($name.'_subject','');//check empty fields 
        $this->assertSelectorTextContains($name.'_name','');
        $this->assertSelectorTextContains($name.'_email','');
        $this->assertSelectorTextContains($name.'_message','');

        */

    }
}
