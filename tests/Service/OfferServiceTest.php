<?php

namespace App\Tests\Service;

use App\Entity\Offer;
use App\Entity\OfferStatus;
use App\Factory\OfferFactory;
use App\Service\OfferService;
use Doctrine\Persistence\ObjectManager;
use Exception;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Zenstruck\Foundry\Proxy;
use function PHPUnit\Framework\assertEquals;
use function Zenstruck\Foundry\factory;

class OfferServiceTest extends WebTestCase
{

    /**
     * @var ObjectManager
     */
    private ObjectManager $entityManager;
    /**
     * @var ContainerInterface
     */
    private ContainerInterface $c;

    private KernelBrowser $client;

    private OfferService $service;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        //self::bootKernel();
        $this->client = static::createClient();
        $this->c = self::$kernel->getContainer();
        $this->entityManager = $this->c->get('doctrine')
                                       ->getManager();
        $this->service = $this->c->get(OfferService::class);

    }

    /**
     * @throws Exception
     */
    public function testValidateMultipleOffers()
    {
        $status = factory(OfferStatus::class)->find(['label' => OfferStatus::ATTENTE_DE_VALIDATION]);
        $offers = OfferFactory::createMany(3, ['offerStatus' => $status]);
        $ids = array_map(function (Proxy $item) {
            /** @var Offer $object */
            $object = $item->object();
            return $object->getId();
        }, $offers);

        //test
        $this->service->validateMultipleOffers($ids);

        foreach ($ids as $id) {

            /** @var Offer $offer */
            $offer = OfferFactory::find($id)->object();
            assertEquals(OfferStatus::PUBLIEE, $offer->getOfferStatus()->getLabel());
        }
    }

    /* public function testSetFulfilled()
     {

     }

     public function testReactivateOffer()
     {

     }

     public function testDeleteMultipleOffers()
     {

     }

     public function testArchiveOffer()
     {

     }

     public function testUpdateLogo()
     {

     }

     public function testDeleteOffer()
     {

     }

     public function testValidateOffer()
     {

     }

     public function testRefuseOffer()
     {

     }

     public function testSetValidationStatus()
     {

     }

     public function testCreateNewOffer()
     {

     }*/
}
