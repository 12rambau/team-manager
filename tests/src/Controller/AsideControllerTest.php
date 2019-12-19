<?php

use App\Entity\Event;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AsideControllerTest extends WebTestCase
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
    
    public function testEventView()
    {
        $event = $this->em->getRepository(Event::class)->findOneBy([]);

        $crawler = $this->client->Request('GET', '/'.$this->locale.'/aside/events/'. $event->getSlug());

        //assert succes
        $this->assertResponseIsSuccessful();

        //assert redirction 
        $link = $crawler->selectLink('All other events')->link();
        $this->assertEquals($link->getUri(), 'http://localhost/en_US/event/index');
        
    }

    /**
     * @dataProvider EventProvider
     *
     */
    public function testEvent(Event $event, int $nbTr)
    {

        $crawler = $this->client->Request('GET', '/'.$this->locale.'/aside/events/'. $event->getSlug());

        $crawler->filter('#events-aside > tr')->count();
        $this->assertEquals($crawler->filter('#events-aside > tr')->count(), $nbTr);

    }

    /**
     * return a set of event for the testEvent function 
     * 
     * @return array [Event, nbTr]
     */
    public function EventProvider(){
        //not a test so override setUp
        $this->setUp();

        return [
            [$this->em->getRepository(Event::class)->findOneBy([],['start' => 'asc']), 4],  //last-event
            [$this->em->getRepository(Event::class)->findOneBy([],['start' => 'desc']), 4],  //first-event
            [$this->em->getRepository(Event::class)->findBy([],['start' => 'desc'],1,6)[0], 6]  //middle-event
        ];
    }
}