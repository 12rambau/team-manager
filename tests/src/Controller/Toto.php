<?php

namespace App\tests\Controller;

use App\Entity\User;
use LogicException;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Guard\Token\PostAuthenticationGuardToken;

// TODO need further investigation but cannot be loaded in the controller tests
class Toto extends WebTestCase
{
    /**
     * @var KernelBrowser
     */
    protected $client = null;

    /**
     * @var EntityManager
     */
    protected $em;

    protected function setUp()
    {
        $this->client = static::createClient();
        $this->em = self::$container->get('doctrine')->getManager();
    }

    protected function logIn(string $username='root', string $firewall='main')
    {
        $session = $this->client->getContainer()->get('session');
        
        $user = $this->em->getRepository(User::class)->findOneByUsername($username);

        $this->assertFalse($user == null);
        if ($user == false)
            throw new LogicException('there is not such user as '.$username);

        $token = new PostAuthenticationGuardToken($user, $firewall, $user->getRoles());
        $session->set('_security_'.$firewall, serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $this->client->getCookieJar()->set($cookie);
    }
}
