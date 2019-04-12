<?php

namespace App\tests\Controller;

use PHPUnit\Framework\WebTestCase;

class BlogControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $client->request('GET', '/blog/index');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

    }
}