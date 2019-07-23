<?php

namespace App\Command;

use App\Entity\DocumentProperty;
use App\Form\DocumentType;
use Doctrine\ORM\EntityManagerInterface;
use PhpParser\Comment\Doc;
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
            $conference = $spreadsheet->getActiveSheet()->getCell('J' . $x)->getValue();
            $presentation = $spreadsheet->getActiveSheet()->getCell('K' . $x)->getValue();
            $publication = $spreadsheet->getActiveSheet()->getCell('L' . $x)->getValue();
            $exhibition = $spreadsheet->getActiveSheet()->getCell('M' . $x)->getValue();
            $international = $spreadsheet->getActiveSheet()->getCell('N' . $x)->getValue();
            $patent = $spreadsheet->getActiveSheet()->getCell('O' . $x)->getValue();
            $location = $spreadsheet->getActiveSheet()->getCell('P' . $x)->getValue();
            $schoolyear = $spreadsheet->getActiveSheet()->getCell('Q' . $x)->getValue();
            $award = $spreadsheet->getActiveSheet()->getCell('R' . $x)->getValue();
            $awardbody = $spreadsheet->getActiveSheet()->getCell('S' . $x)->getValue();
            $natprod = $spreadsheet->getActiveSheet()->getCell('T' . $x)->getValue();


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

            $this->addProperty($document, DocumentProperty::PROPERTY_CONFERENCE, DocumentProperty::TYPE_TEXT, $conference);
            $this->addProperty($document, DocumentProperty::PROPERTY_PRESENTATION, DocumentProperty::TYPE_TEXT, $presentation);
            $this->addProperty($document, DocumentProperty::PROPERTY_PUBLICATION, DocumentProperty::TYPE_TEXT, $publication);
            $this->addProperty($document, DocumentProperty::PROPERTY_EXHIBITION, DocumentProperty::TYPE_TEXT, $exhibition);
            $this->addProperty($document, DocumentProperty::PROPERTY_INTERNATIONAL, DocumentProperty::TYPE_BOOL, $international);
            $this->addProperty($document, DocumentProperty::PROPERTY_PATENT, DocumentProperty::TYPE_NUMBER, $patent);
            $this->addProperty($document, DocumentProperty::PROPERTY_LOCATION, DocumentProperty::TYPE_TEXT, $location);
            $this->addProperty($document, DocumentProperty::PROPERTY_SCHOOL_YEAR, DocumentProperty::TYPE_TEXT, $schoolyear);
            $this->addProperty($document, DocumentProperty::PROPERTY_AWARD, DocumentProperty::TYPE_TEXT, $award);
            $this->addProperty($document, DocumentProperty::PROPERTY_AWARD_BODY, DocumentProperty::TYPE_TEXT, $awardbody);
            $this->addProperty($document, DocumentProperty::PROPERTY_NATPROD, DocumentProperty::TYPE_TEXT, $natprod);


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

    private function addProperty(Document $document, $property, $type, $value)
    {
        if (strlen(trim($value)) > 0) {
            $documentProperty = new DocumentProperty();
            $documentProperty->setName($property);
            $documentProperty->setType($type);
            $documentProperty->setValue($value);
            $document->addDocumentProperty($documentProperty);
            $this->entityManager->persist($documentProperty);
        }
    }
}
