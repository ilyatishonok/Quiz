<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegistrationControllerTest extends WebTestCase
{
    public function testRegister()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/register');
    }

    public function testCheckemail()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/checkEmail');
    }

    public function testConfirm()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/confirm');
    }

    public function testConfirmed()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/confirmed');
    }

}
