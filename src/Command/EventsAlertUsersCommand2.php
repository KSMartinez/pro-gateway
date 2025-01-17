<?php

namespace App\Command;

use Exception;
use Psr\Log\LoggerInterface;
use App\Service\EventAlertService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:events:alertUsers2', description: 'Command that alert users with notifications one week before the event')]
class EventsAlertUsersCommand2 extends Command
{
    public function __construct(private EventAlertService $eventAlertService, private LoggerInterface $logger)
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
        $this->logger->info("Send an alert to the users one day before the event");
        $this->eventAlertService->alertUsersOneWeekBeforeTheEvent();   
        $this->logger->info("The checking has ended and email notifications have been created.");
        return Command::SUCCESS;
    }
}
