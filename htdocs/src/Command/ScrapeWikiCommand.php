<?php

namespace App\Command;

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

    /**
     * @param WikiScraper $wikiScraper
     */
    public function __construct(WikiScraper $wikiScraper)
    {
        $this->wikiScraper = $wikiScraper;
        parent::__construct();
    }


    protected function configure(): void
    {
        $this->addArgument('url', InputArgument::OPTIONAL, 'url to the page of products');
        $this->addOption('suppliers', 's', InputOption::VALUE_NONE, 'option to scrape suppliers');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $url = $input->getArgument('url');
        if($input->getOption('suppliers')){
            $this->wikiScraper->scrapeSuppliers();
            return Command::SUCCESS;
        };

        if (!$url) {
            $io->error(sprintf("You didn't pass the url"));
            return Command::FAILURE;
        }

        $productsArray = $this->wikiScraper->getProducts($url);
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
