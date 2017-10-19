<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AdminControllerTest extends WebTestCase
{
    public function testShowadminpage()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/admin/page');
    }

    public function testShowquizcreation()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/create/quiz');
    }

    public function testShowquestingcreation()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/create/question');
    }

}
