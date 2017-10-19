<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class QuizControllerTest extends WebTestCase
{
    public function testShowquiz()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', 'quiz');
    }

}
