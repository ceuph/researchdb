<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190724011602 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE document_property CHANGE value value LONGTEXT NOT NULL');
        $this->addSql('ALTER TABLE document CHANGE month_created month_created INT DEFAULT NULL, CHANGE day_created day_created INT DEFAULT NULL');
        $this->addSql('ALTER TABLE document_attachment CHANGE document_id document_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE document_author CHANGE last_name last_name VARCHAR(255) DEFAULT NULL, CHANGE first_name first_name VARCHAR(255) DEFAULT NULL, CHANGE middle_name middle_name VARCHAR(255) DEFAULT NULL, CHANGE mi mi VARCHAR(255) DEFAULT NULL, CHANGE prefix prefix VARCHAR(255) DEFAULT NULL, CHANGE suffix suffix VARCHAR(255) DEFAULT NULL, CHANGE display_name display_name VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE document CHANGE month_created month_created INT DEFAULT NULL, CHANGE day_created day_created INT DEFAULT NULL');
        $this->addSql('ALTER TABLE document_attachment CHANGE document_id document_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE document_author CHANGE last_name last_name VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE first_name first_name VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE middle_name middle_name VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE mi mi VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE prefix prefix VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE suffix suffix VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE display_name display_name VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE document_property CHANGE value value VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci');
    }
}
