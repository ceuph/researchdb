<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ImportDataCommand extends Command
{
    protected static $defaultName = 'app:import-data';

    protected function configure()
    {
        $this
            ->setDescription('Read excel file and import to database. The excel file should have the following ' .
            'columns in the same order "subject, body, remarks, filename. Filename should not include extension. File should be ' .
            'in PDF format."')
            ->addArgument('excel_path', InputArgument::REQUIRED, 'Excel file to read')
            ->addArgument('directory', InputArgument::OPTIONAL, 'Directory containing PDF files')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $excel_path = $input->getArgument('excel_path');

        if ($excel_path) {
            $io->note(sprintf('You passed an argument: %s', $excel_path));
        }
    }
}
