<?php

namespace App\Tests\Service;

use App\Entity\Domain;
use App\Entity\LevelOfEducation;
use App\Entity\Offer;
use App\Entity\OfferStatus;
use App\Entity\SectorOfOffer;
use App\Entity\TypeOfContract;
use App\Entity\User;
use App\Factory\OfferFactory;
use App\Repository\OfferRepository;
use App\Service\OfferService;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Persistence\ObjectManager;
use Exception;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Zenstruck\Foundry\Proxy;
use function PHPUnit\Framework\assertEmpty;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertNotContains;
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
    private OfferRepository $repo;

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
        $this->repo = $this->entityManager->getRepository(Offer::class);

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
            $offer = OfferFactory::find($id)
                                 ->object();
            assertEquals(OfferStatus::PUBLIEE, $offer->getOfferStatus()
                                                     ->getLabel());
        }
    }

    /**
     * @throws Exception
     */
    public function testSetFulfilled()
    {
        $status = factory(OfferStatus::class)->find(['label' => OfferStatus::PUBLIEE]);
        $offer = OfferFactory::createOne(['offerStatus' => $status]);

        $offer = $this->repo->find($offer->getId());
        $this->service->setFulfilled($offer);

        $offer = $this->repo->find($offer->getId());
        assertEquals(OfferStatus::POURVUE, $offer->getOfferStatus()
                                                 ->getLabel());


    }

    /**
     * @throws Exception
     */
    public function testReactivateOffer()
    {
        $status = factory(OfferStatus::class)->find(['label' => OfferStatus::ARCHIVEE]);
        $offer = OfferFactory::createOne(['offerStatus' => $status]);

        $offer = $this->repo->find($offer->getId());
        //login
        $user = $this->entityManager->getRepository(User::class)
                                    ->findOneBy(['email' => 'fakeuser@email.com']);
        $this->client->loginUser($user);
        $this->service->reactivateOffer($offer);

        $offer = $this->repo->find($offer->getId());
        assertEquals(OfferStatus::ATTENTE_DE_VALIDATION, $offer->getOfferStatus()
                                                               ->getLabel());
        assertEquals((new DateTime('now'))->format('d/m/Y'), $offer->getDatePosted()
                                                                   ->format('d/m/Y'));
    }

    /**
     * @throws Exception
     */
    public function testDeleteMultipleOffers()
    {
        $status = factory(OfferStatus::class)->find(['label' => OfferStatus::PUBLIEE]);
        $offers = OfferFactory::createMany(3, ['offerStatus' => $status]);
        $ids = array_map(function (Proxy $item) {
            /** @var Offer $object */
            $object = $item->object();
            return $object->getId();
        }, $offers);

        //test
        $this->service->deleteMultipleOffers($ids);

        foreach ($ids as $id) {

            /** @var Offer $offer */
            $offer = OfferFactory::find($id)
                                 ->object();
            assertEquals(OfferStatus::SUPPRIMEE, $offer->getOfferStatus()
                                                       ->getLabel());
        }

    }

    /**
     * @throws Exception
     */
    public function testArchiveOffer()
    {
        $status = factory(OfferStatus::class)->find(['label' => OfferStatus::PUBLIEE]);
        $offer = OfferFactory::createOne(['offerStatus' => $status]);

        $offer = $this->repo->find($offer->getId());
        $this->service->archiveOffer($offer);

        $offer = $this->repo->find($offer->getId());
        assertEquals(OfferStatus::ARCHIVEE, $offer->getOfferStatus()
                                                  ->getLabel());

    }

    /**
     * @throws Exception
     */
    public function testUpdateLogo()
    {
        $status = factory(OfferStatus::class)->find(['label' => OfferStatus::PUBLIEE]);
        $offer = OfferFactory::createOne(['offerStatus' => $status]);

        $offer = $this->repo->find($offer->getId());
        $offer->setLogoFile(null);
        $this->service->updateLogo($offer);

        $offer = $this->repo->find($offer->getId());
        assertEmpty($offer->getLogoLink());
    }

    /**
     * @throws Exception
     */
    public function testDeleteOffer()
    {

        $status = factory(OfferStatus::class)->find(['label' => OfferStatus::PUBLIEE]);
        $offer = OfferFactory::createOne(['offerStatus' => $status]);

        $offer = $this->repo->find($offer->getId());
        $this->service->deleteOffer($offer);

        $offer = $this->repo->find($offer->getId());
        assertEquals(OfferStatus::SUPPRIMEE, $offer->getOfferStatus()
                                                   ->getLabel());
    }

    /**
     * @throws Exception
     */
    public function testValidateOffer()
    {
        $status = factory(OfferStatus::class)->find(['label' => OfferStatus::ATTENTE_DE_VALIDATION]);
        $offer = OfferFactory::createOne(['offerStatus' => $status]);

        $offer = $this->repo->find($offer->getId());
        $this->service->validateOffer($offer);

        $offer = $this->repo->find($offer->getId());
        assertEquals(OfferStatus::PUBLIEE, $offer->getOfferStatus()
                                                 ->getLabel());
    }

    /**
     * @throws Exception
     */
    public function testRefuseOffer()
    {
        $status = factory(OfferStatus::class)->find(['label' => OfferStatus::ATTENTE_DE_VALIDATION]);
        $offer = OfferFactory::createOne(['offerStatus' => $status]);

        $offer = $this->repo->find($offer->getId());
        $this->service->refuseOffer($offer);

        $offer = $this->repo->find($offer->getId());
        assertEquals(OfferStatus::REFUSE, $offer->getOfferStatus()
                                                ->getLabel());
    }

    /**
     * @throws Exception
     */
    public function testSetValidationStatus()
    {
        $status = factory(OfferStatus::class)->find(['label' => OfferStatus::ATTENTE_DE_VALIDATION]);
        $offer = OfferFactory::createOne(['offerStatus' => $status]);

        $offer = $this->repo->find($offer->getId());
        //login
        $user = $this->entityManager->getRepository(User::class)
                                    ->findOneBy(['email' => 'fakeuser@email.com']);
        $this->client->loginUser($user);
        $this->service->reactivateOffer($offer);

        $offer = $this->repo->find($offer->getId());
        assertEquals(OfferStatus::ATTENTE_DE_VALIDATION, $offer->getOfferStatus()
                                                               ->getLabel());
        $user->setRoles([User::ROLE_ADMIN]);
        $this->service->reactivateOffer($offer);
        assertEquals(OfferStatus::PUBLIEE, $offer->getOfferStatus()
                                                               ->getLabel());
    }

    /**
     * @throws Exception
     */
    public function testCreateNewOffer()
    {
        /** @var Offer $offer */
        $offer = OfferFactory::new()->withoutPersisting()->create()->object();
        $user = $this->entityManager->getRepository(User::class)
                                    ->findOneBy(['email' => 'fakeuser@email.com']);
        $this->client->loginUser($user);
        //set all associations manually with entityManager
        $offer->setDomain($this->entityManager->getReference(Domain::class,$offer->getDomain()->getId()));
        $offer->setTypeOfContract($this->entityManager->getReference(TypeOfContract::class, $offer->getTypeOfContract()->getId()));
        $offer->setOfferStatus($this->entityManager->getReference(OfferStatus::class, $offer->getOfferStatus()->getId()));
        $offer->setSector($this->entityManager->getReference(SectorOfOffer::class, $offer->getSector()->getId()));
        $offer->setCreatedByUser($user);
        $offer->setLevelOfEducation(new ArrayCollection([$this->entityManager->getReference(LevelOfEducation::class, $offer->getLevelOfEducation()->first()->getId())]));

        //login


        $offer = $this->service->createNewOffer($offer);

        self::assertNotNull($offer->getId());
    }
}
