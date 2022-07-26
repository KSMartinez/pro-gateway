<?php

namespace App\Command;

use App\Service\OfferService;
use Exception;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

#[AsCommand(
    name       : 'app:nexus:api:get_offers',
    description: 'Calls the Nexus API (referred as the Intermediate API) for ReseauPro for getting new Offers',
)]
class GetOffersFromNexus extends Command
{


    protected string $name = 'app:nexus:api:get_offers';

    public function __construct(private OfferService $offerService)
    {
        parent::__construct($this->name);
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @return int
     * @throws Exception
     * @throws TransportExceptionInterface
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        $offers = $this->offerService->pollNexusForNewOffers();
        $this->offerService->saveOffersFromNexus($offers);
        return Command::SUCCESS;
    }
}