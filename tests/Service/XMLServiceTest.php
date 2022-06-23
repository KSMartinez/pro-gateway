<?php

namespace App\Tests\Service;

use App\Factory\OfferFactory;
use App\Service\XMLParseService;
use Doctrine\Persistence\ObjectManager;
use Exception;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Zenstruck\Foundry\Proxy;

class XMLServiceTest extends KernelTestCase
{

    /**
     * @var ContainerInterface
     */
    private ContainerInterface $c;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        self::bootKernel();
        $this->c = self::$kernel->getContainer();
        $this->entityManager = $this->c->get('doctrine')
                                       ->getManager();
    }


    /**
     * @throws Exception
     */
    public function testParseXML()
    {
        /** @var XMLParseService $xmlService */
        $xmlService = $this->c->get(XMLParseService::class);
        $xmlService->parseXML();


    }
}
