<?php

namespace App\Command;

use App\Service\GenerateService;
use App\Service\Types;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'generate:table',
    description: 'Add a short description for your command',
)]
class GenerateTableCommand extends Command
{
    private GenerateService $generateService;
    private Types $types;

    /**
     * @param GenerateService $generateService
     */
    public function __construct(GenerateService $generateService, Types $types)
    {
        $this->generateService = $generateService;
        $this->types = $types;
        parent::__construct();
    }


    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        //$this->productScraper->test(4);
        $choices = $io->choice('Which products type would you like to generate a table for?', $this->types->getChoices(), multiSelect: true);
        print_r($this->generateService->GenerateTable($choices));

        return Command::SUCCESS;
    }
}
