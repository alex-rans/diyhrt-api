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
    name: 'scrape:wiki',
    description: 'Add a short description for your command',
)]
class ScrapeWikiCommand extends Command
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
        //$this->addArgument('url', InputArgument::OPTIONAL, 'url to the page of products');
        $this->addOption('suppliers', 's', InputOption::VALUE_NONE, 'option to scrape suppliers');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        if($input->getOption('suppliers')){
            $this->wikiScraper->scrapeSuppliers();
            return Command::SUCCESS;
        }
        $choices = $io->choice('Which products would you like to scrape?', $this->types->getChoices(), multiSelect: true);
        $productsArray = $this->wikiScraper->getProducts($choices);
        print_r($productsArray);
        $addToDb = $io->confirm('Do you want to add this data to the database?');

        if($addToDb){
            $this->wikiScraper->insertProductsIntoDatabase($productsArray);
            $io->success("Operation finished.\n");
            return Command::SUCCESS;
        }
        return Command::SUCCESS;

    }
}
