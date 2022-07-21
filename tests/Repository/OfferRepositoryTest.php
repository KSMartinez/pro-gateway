<?php

namespace App\Tests\Repository;

use App\Entity\Domain;
use App\Entity\SavedOfferSearch;
use App\Entity\TypeOfContract;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class OfferRepositoryTest extends KernelTestCase
{

    // private ContainerInterface $c;
    protected function setUp(): void
    {
        self::bootKernel();
        //   $this->c = self::$kernel->getContainer();
    }

    //todo Finish this test along with the other test

    /**
     * @return void
     */
    public function testGetNumberOfNewOffers()
    {
        $user = new User();
        $user->setEmail('test@test.com');
        $savedOfferSearch = new SavedOfferSearch();
        $savedOfferSearch->setUser($user)
                         ->setCity("Paris")
                         ->setCompanyName("Test Company")
                         ->setCountry("France")
                         ->setDescription("This is a test job for testing with a lot of tests")
                         ->setDomain((new Domain())->setLabel("Test Domain"))
                         ->setIsActive(true)
                         ->setMaxSalary(300)
                         ->setMinSalary(100)
                         ->setNameOfSearch('Test Search #1')
                         ->setTitle('This is Test Search #1 ')
                         ->setTypeOfContract((new TypeOfContract())->setLabel('Test Type'))
                         ->setUrl('url');
//        $offerRepository = $this->c->get(OfferRepository::class);
//        $offerRepository->getNumberOfNewOffers($savedOfferSearch);
    }
}
