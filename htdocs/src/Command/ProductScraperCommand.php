<?php

namespace App\Command;

use App\Service\ProductScraper;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'scrape:products',
    description: 'Add a short description for your command',
)]
class ProductScraperCommand extends Command
{
    private ProductScraper $productScraper;

    /**
     * @param ProductScraper $productScraper
     */
    public function __construct(ProductScraper $productScraper)
    {
        $this->productScraper = $productScraper;
        parent::__construct();
    }


    protected function configure(): void
    {
//        $this
//            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
//            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
//        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        //$this->productScraper->test(4);
        $choices = $io->choice('Which products would you like to scrape?', $this->productScraper->getChoices(), multiSelect: true);
        $updatedList = $this->productScraper->getPriceData($choices);
        $io->text('updated products:');
        print_r($updatedList);
        $io->success('Products successfully scraped.');
        return Command::SUCCESS;
    }
}
