<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AppControllerTest extends WebTestCase
{

    public function testLegacyBookindex(): void
    {
        $client = static::createClient();
           
        // test e.g. the profile page
        $client->request('GET', '/booking/1');
        $this->assertResponseIsSuccessful();
    }
    /**
     * @group legacy
     */
    public function testNumber(): void
    {
        $client = static::createClient();
   
        // test e.g. the profile page
        $client->request('GET', '/lucky/number');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Your lucky number is');

    }


    /**
     * @group legacy
     */
    public function testSomething(): void
    {
        $client = static::createClient();
   
        // test e.g. the profile page
        $client->request('GET', '/lucky/myAction');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Your lucky number is');
     
    }
}
