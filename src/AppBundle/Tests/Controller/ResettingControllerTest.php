<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ResettingControllerTest extends WebTestCase
{
    public function testRequest()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/request');
    }

    public function testSendemail()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/sendEmail');
    }

    public function testCheckemail()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/checkEmail');
    }

    public function testReset()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/reset');
    }

}
