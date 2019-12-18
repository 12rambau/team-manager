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
        $this->client->request('GET', '/' . $this->locale . '/contact-information');

        //check if the page appears without errors 
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        //assert form name
        $crawler = $this->client->request('GET', '/' . $this->locale . '/contact-information');
        $form = $crawler->selectButton('submit')->form();
        //if (!$form->getName()){
            //$this->assertEquals($form->getName(), 'contact');
           $this->markTestIncomplete('GetName() is only available in 5.0');
       //}

    }

    /**
     * @dataProvider CnntactFormProvider
     */
    public function testContactForm(string $subject, string $name, string $email, string $message, int $nbErrors)
    {
        $name = 'contact';

        $crawler = $this->client->request('GET', '/' . $this->locale . '/contact-information');
        $form = $crawler->selectButton('submit')->form();
        
        $crawler = $this->client->submit($form, [
            $name . '[subject]' => $subject,
            $name . '[name]'    => $name,
            $name . '[email]'   => $email,
            $name . '[message]' => $message
        ]);

        $this->assertTrue($crawler->filter('[id ^='.$name.'_][id $=_errors]')->count() == $nbErrors);

        //check that the form is cleaned if valid
        if (!$nbErrors){
            $this->assertInputValueEquals('#'.$name.'_subject', '');
            $this->assertInputValueEquals('#'.$name.'_name', '');
            $this->assertInputValueEquals('#'.$name.'_email', '');
            $this->assertInputValueEquals('#'.$name.'_message', '');
        }
    }

    /**
     * provider of values for the contactTest
     * 
     * @return array [subject, name, email, message, nbErrors]
     */
    public function CnntactFormProvider()
    {
        return [
            ['subject', 'toto', 'toto@domaine.com', 'Team-manager Rocks!', 0],  //correct
            ['', 'toto', 'toto@domaine.com', 'Team-manager Rocks!', 1],         //no-subject
            ['subject', '', 'toto@domaine.com', 'Team-manager Rocks!', 1],      // no-name
            ['subject', 'toto', '', 'Team-manager Rocks!', 1],                  // no-email
            ['subject', 'toto', 'not.a.email', 'Team-manager Rocks!', 1],       // not-email
            ['subject', 'toto', 'toto@domaine.com', '', 1],                     // no-message
            ['', 'toto', '', 'Team-manager Rocks!', 2]                          // 2 missings
        ];
    }

}
