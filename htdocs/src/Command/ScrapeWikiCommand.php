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
        $this->addArgument('url', InputArgument::OPTIONAL, 'Argument description');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $url = $input->getArgument('url');

        if (!$url) {
            $io->note(sprintf("You didn't pass the url"));
        }
        $this->wikiScraper->getProducts($url);

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return Command::SUCCESS;
    }
}
