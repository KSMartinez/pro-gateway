<?php

namespace App\Command;

use Exception;
use Psr\Log\LoggerInterface;
use App\Service\EventAlertService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;   

#[AsCommand(name: 'app:events:alertAdmins', description: 'Command that alert admins with notifications one day before the end of the event')]
class EventsAlertAdminsCommand extends Command   
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
        $this->logger->info("Send an alert to the admins one day before the end of events");
        $this->eventAlertService->notificationOneDayBeforeTheEndOfEvents();     
        $this->logger->info("The checking has ended and email notifications have been created.");
        return Command::SUCCESS;
    }
}
