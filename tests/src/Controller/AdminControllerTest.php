<?php

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AdminControllerTest extends WebTestCase
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

    public function testLogin()
    {
        $crawler = $this->client->request('GET', '/' . $this->locale . '/admin/login');

        //assert page
        $this->assertResponseIsSuccessful();

        $this->assertSelectorTextContains('button[type="submit"]', 'Sign in');

        //assert form name
        $form = $crawler->selectButton('Sign in')->form();
        //if (!$form->getName()){
        //$this->assertEquals($form->getName(), 'contact');
        $this->markTestIncomplete('GetName() is only available in 5.0');
        //}

        //asert parts of the form
        $this->assertSelectorExists('#username');
        $this->assertSelectorExists('#password');
    }

    /**
     * @dataProvider loginFormProvider
     */
    public function testLoginForm(string $username, string $plainPassword, int $nbErrors)
    {
        $crawler = $this->client->request('GET', '/' . $this->locale . '/admin/login');

        $form = $crawler->selectButton('Sign in')->form();

        $crawler = $this->client->submit($form, [
            'username'  => $username,
            'password'  => $plainPassword,
        ]);

        if (!$nbErrors) {
            //DO the registration
            //$this->assertResponseRedirects('admin/en_US/');
            $this->markTestIncomplete("check that the registration is performed");
        } else {
            $this->markTestIncomplete("don't know how to trigger errors");
            //$this->assertSelectorExists('#errors');
        }
    }

    /**
     * @return array [username, plainPassword, $nbErrors]
     */
    public function loginFormProvider()
    {
        return [
            ['root', 'root', 0],    //fully qualified
            ['', 'root', 1],        //no-username
            ['root', '', 1],        //no-password
            ['', '', 2],            //nothing
            ['root1', 'root1', 1]   //wrong credential
        ];
    }
}
