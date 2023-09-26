<?php

namespace App\Command;

use App\Service\Types;
use App\Service\WikiScraper;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'generate:units',
    description: 'Add a short description for your command',
)]
class CalculateUnitsCommand extends Command
{
    private WikiScraper $wikiScraper;
    private Types $types;

    /**
     * @param WikiScraper $wikiScraper
     * @param Types $types
     */
    public function __construct(WikiScraper $wikiScraper, Types $types)
    {
        $this->wikiScraper = $wikiScraper;
        $this->types = $types;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $this->wikiScraper->CalculateUnits();

        return Command::SUCCESS;
    }
}
