<?php

namespace App\Tests\functional;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class OfferResourceTest extends ApiTestCase
{
    /**
     * Random sample test
     * @return void
     * @throws TransportExceptionInterface
     */
    public function testValidateOffer()
    {

        //$em = self::getContainer()->get('doctrine')->getManager();
        $client = self::createClient();
        $client->request('POST', '/api/offers/1/validate', [
            'json' => []
        ]);

        self::assertResponseStatusCodeSame(404);

    }

}