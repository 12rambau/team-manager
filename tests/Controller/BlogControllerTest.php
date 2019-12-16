<?php

namespace App\tests\Controller;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\WebTestCase;

class BlogControllerTest extends TestCase
{
    public function firstTest()
    {
        $this->assertEquals(0,0);
    }
    /*public function testIndex()
    {
        $client = static::createClient();

        $client->request('GET', '/blog/index');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

    }*/
}