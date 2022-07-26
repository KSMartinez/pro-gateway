<?php

namespace App\Command;

use App\Service\NexusAPIService;
use Exception;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name       : 'app:nexus:api:dispatch_candidatures',
    description: 'Calls the Nexus API (referred as the Intermediate API) for ReseauPro',
)]
class NexusApiDispatchCandidatureCommand extends Command
{

    protected string $name = 'app:nexus:api:dispatch_candidatures';

    public function __construct(private NexusAPIService $nexusAPIService)
    {
        parent::__construct($this->name);
    }

    protected function configure(): void
    {
        $this
            ->addArgument('candidature_id', InputArgument::REQUIRED,
                          'Data to send to Nexus API as a part of the request');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @return int
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $candidatureId = $input->getArgument('candidature_id');

        if (!$candidatureId) {
            throw new Exception('Candidature ID cannot be null');
        }

        $this->nexusAPIService->dispatchCandidatureToNexus($candidatureId);



        return Command::SUCCESS;
    }
}
