<?php

namespace App\Command;

use App\Service\OfferAlertService;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:events:alert', description: 'Command that goes through the saved search of users to alert them with notifications one day before the event',)]
class EventsAlertCommand extends Command
{
    public function __construct(private OfferAlertService $offerAlertService, private LoggerInterface $logger)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
    }

    /**
     * @throws Exception
     */
    //todo Test this command
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->logger->info("Started checking for saved searches for new offers created since last search");
        $this->offerAlertService->alertUsersWithSavedSearchForNewOffers();
        $this->logger->info("The checking has ended and email notifications have been created.");
        return Command::SUCCESS;
    }
}
