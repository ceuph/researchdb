<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190716131851 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TABLE document (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, subject VARCHAR(255) NOT NULL, body CLOB NOT NULL, year_created INTEGER NOT NULL, month_created INTEGER DEFAULT NULL, day_created INTEGER DEFAULT NULL, date_created DATETIME NOT NULL, date_modified DATETIME NOT NULL, created_by VARCHAR(255) NOT NULL, updated_by VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE document_author (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, document_id INTEGER NOT NULL, last_name VARCHAR(255) DEFAULT NULL, first_name VARCHAR(255) DEFAULT NULL, middle_name VARCHAR(255) DEFAULT NULL, mi VARCHAR(255) DEFAULT NULL, prefix VARCHAR(255) DEFAULT NULL, suffix VARCHAR(255) DEFAULT NULL, display_name VARCHAR(255) DEFAULT NULL)');
        $this->addSql('CREATE INDEX IDX_3CD69AEC33F7837 ON document_author (document_id)');
        $this->addSql('CREATE TABLE document_property (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, document_id INTEGER NOT NULL, name VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, value VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE INDEX IDX_433ED87C33F7837 ON document_property (document_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP TABLE document');
        $this->addSql('DROP TABLE document_author');
        $this->addSql('DROP TABLE document_property');
    }
}
