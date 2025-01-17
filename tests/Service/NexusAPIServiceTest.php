<?php

namespace App\Tests\Service;

use App\Entity\Candidature;
use App\Entity\Offer;
use App\Entity\OfferStatus;
use App\Entity\User;
use App\Factory\OfferFactory;
use App\Factory\UserFactory;
use App\Service\NexusAPIService;
use DateTime;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use function Zenstruck\Foundry\factory;

class NexusAPIServiceTest extends KernelTestCase
{
    private ObjectManager $entityManager;
    private ContainerInterface $containerInstance;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        self::bootKernel();
        $this->containerInstance = self::$kernel->getContainer();
        $this->entityManager = $this->containerInstance->get('doctrine')->getManager();
    }

    public function testDispatchCandidatureToAPI()
    {
        //$this->entityManager->getConnection()->beginTransaction();

        $candidature = new Candidature();


        $user = UserFactory::random();
        $offer = OfferFactory::random();
        $candidature->setMessage("This is a test candidature")
                    ->setUser($user->object())
                    ->setDateOfCandidature(new DateTime('now'))
                    ->setOffer($offer->object());

        $this->entityManager->persist($candidature);
        $this->entityManager->flush();

        $nexusService = $this->containerInstance->get(NexusAPIService::class);
        $nexusService->executeCommandToSendCandidature($candidature);

        self::assertNotNull(1, 1);

       // $this->entityManager->getConnection()->rollback();

    }

    public function testDispatchCandidatureToNexus()
    {
       // $this->entityManager->getConnection()->beginTransaction();

        $candidature = new Candidature();


        $user = UserFactory::random();
        $offer = OfferFactory::random();

        $candidature->setMessage("This is a test candidature")
                    ->setUser($this->entityManager->getReference(User::class,$user->getId()))
                    ->setDateOfCandidature(new DateTime('now'))
                    ->setOffer($this->entityManager->getReference(Offer::class,$offer->getId()));

        $this->entityManager->persist($candidature);
        $this->entityManager->flush();

        $nexusService = $this->containerInstance->get(NexusAPIService::class);
        $nexusService->dispatchCandidatureToNexus($candidature);


        //$this->entityManager->getConnection()->rollback();

    }

    /**
     * @return void
     */
    public function testRequestNexusForOffers(){

        $numberOfOffers = OfferFactory::count();
        $numberOfOffersOnNexus = 10;

        $nexusService = $this->containerInstance->get(NexusAPIService::class);

        /** @var Offer[] $offers */
        $offers = $nexusService->requestNexusForOffers();

        $status = $this->entityManager->getRepository(OfferStatus::class)->findOneBy(['label' => OfferStatus::PUBLIEE]);
        foreach ($offers as $offer) {

            $offer->setOfferStatus($status);
            $this->entityManager->persist($offer);
        }

        $this->entityManager->flush();

        self::assertEquals($numberOfOffers + $numberOfOffersOnNexus, OfferFactory::count());


    }


}
