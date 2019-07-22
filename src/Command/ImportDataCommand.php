<?php

namespace App\Command;

use Doctrine\ORM\EntityManagerInterface;
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
use Symfony\Component\DependencyInjection\ContainerInterface;

class ImportDataCommand extends Command
{
    protected static $defaultName = 'app:import-data';
    private $container;
    private $entityManager;

    public function __construct(?string $name = null, EntityManagerInterface $entityManager)
    {
        parent::__construct($name);
        $this->entityManager = $entityManager;
    }

    protected function configure()
    {
        $this
            ->setDescription('Read excel file and import to database. The excel file should have the following ' .
            'columns in the same order "subject, body, year, month, day, author, filename, remarks. Authors should be in ' .
            '"<firstname> <mi>. <lastname>" format and delimeted by semicolon ";". Filename should not include file extension. ' .
            'File should be in PDF format."')
            ->addArgument('excel_path', InputArgument::REQUIRED, 'Excel file to read')
            ->addArgument('source_directory', InputArgument::OPTIONAL, 'Directory containing PDF files')
            ->addArgument('destination_directory', InputArgument::OPTIONAL, 'Public directory for PDF files')
            ->addOption('skip', 's', InputOption::VALUE_OPTIONAL, 'Number of rows to skip', 0);
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /* @var $em \Doctrine\ORM\EntityManagerInterface */
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
            $remarks = $spreadsheet->getActiveSheet()->getCell('G' . $x)->getValue();
            $abstract = $spreadsheet->getActiveSheet()->getCell('H' . $x)->getValue();
            $fulltext = $spreadsheet->getActiveSheet()->getCell('I' . $x)->getValue();

            if (strlen(trim($subject)) == 0) {
                break;
            }

            $document = new Document();
            $document->setSubject($subject);
            $document->setBody($body);
            $document->setYearCreated($year);
            $document->setMonthCreated(strlen(trim($month)) > 0 ?$month : null);
            $document->setDayCreated(strlen(trim($day)) > 0 ? $day : null);
            $document->setRemarks(strlen(trim($remarks)) > 0 ? $remarks : null);
            $this->addAttachment(
                $document,
                $input->getArgument('source_directory') . DIRECTORY_SEPARATOR . $abstract . '.pdf',
                DocumentAttachment::TYPE_ABSTRACT,
                $input->getArgument('destination_directory')
            );
            $this->addAttachment(
                $document,
                $input->getArgument('source_directory') . DIRECTORY_SEPARATOR . $fulltext . '.pdf',
                DocumentAttachment::TYPE_FULLTEXT,
                $input->getArgument('destination_directory')
            );

            if (strlen(trim($author)) > 0) {
                $authors = explode(';', $author);
                foreach ($authors as $author_value) {
                    $documentAuthor = new DocumentAuthor($author_value);
                    $documentAuthor->setDisplayName($author_value);
                    $document->addDocumentAuthor($documentAuthor);
                    $this->entityManager->persist($documentAuthor);
                }
            }
            $this->entityManager->persist($document);
            $x++;
        }
        $this->entityManager->flush();
    }

    private function addAttachment(Document $document, $sourceFile, $type, $destinationDirectory)
    {
        if (file_exists($sourceFile)) {
            $destination_file_name = uniqid() . '.pdf';
            $destination_file = $destinationDirectory . DIRECTORY_SEPARATOR . $destination_file_name;
            copy($sourceFile, $destination_file);
            $documentAttachment = new DocumentAttachment();
            $documentAttachment->setPath($destination_file_name);
            $documentAttachment->setType($type);
            $document->addDocumentAttachment($documentAttachment);
            $this->entityManager->persist($documentAttachment);
        }
    }
}
