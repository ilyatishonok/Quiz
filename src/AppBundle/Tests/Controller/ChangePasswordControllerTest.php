<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ChangePasswordControllerTest extends WebTestCase
{
    public function testChangepassword()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/changePassword');
    }

}
