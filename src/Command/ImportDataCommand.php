<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Entity\Document;
use App\Entity\DocumentAttachment;
use App\Entity\DocumentAuthor;

class ImportDataCommand extends Command
{
    protected static $defaultName = 'app:import-data';

    protected function configure()
    {
        $this
            ->setDescription('Read excel file and import to database. The excel file should have the following ' .
            'columns in the same order "subject, body, year, month, day, author, filename, remarks. Authors should be in ' .
            '"<firstname> <mi>. <lastname>" format and delimeted by semicolon ";". Filename should not include file extension. ' .
            'File should be in PDF format."')
            ->addArgument('excel_path', InputArgument::REQUIRED, 'Excel file to read')
            ->addArgument('directory', InputArgument::OPTIONAL, 'Directory containing PDF files')
            ->addOption('skip', 's', InputOption::VALUE_OPTIONAL, 'Number of rows to skip', 0);
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $spreadsheet = IOFactory::load($input->getArgument('excel_path'));

        $x = $input->getOption('skip') + 1;

        while (true) {
            $subject = $spreadsheet->getActiveSheet()->getCell('A' . $x)->getValue();
            $body = $spreadsheet->getActiveSheet()->getCell('B' . $x)->getValue();
            $year = $spreadsheet->getActiveSheet()->getCell('C' . $x)->getValue();
            $month = $spreadsheet->getActiveSheet()->getCell('D' . $x)->getValue();
            $day = $spreadsheet->getActiveSheet()->getCell('E' . $x)->getValue();
            $author = $spreadsheet->getActiveSheet()->getCell('F' . $x)->getValue();
            $file = $spreadsheet->getActiveSheet()->getCell('G' . $x)->getValue();
            $remarks = $spreadsheet->getActiveSheet()->getCell('H' . $x)->getValue();

            $io->text($subject);
            $x++;
            if (strlen(trim($subject)) == 0) {
                break;
            }
        }
    }
}
