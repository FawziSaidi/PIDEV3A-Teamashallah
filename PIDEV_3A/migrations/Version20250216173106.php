<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250216173106 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE course ADD preferred_previous_knowledge JSON DEFAULT NULL COMMENT \'(DC2Type:json)\', CHANGE title title VARCHAR(255) NOT NULL, CHANGE description description LONGTEXT DEFAULT NULL, CHANGE about about LONGTEXT NOT NULL, CHANGE name name VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE course DROP preferred_previous_knowledge, CHANGE title title VARCHAR(255) DEFAULT NULL, CHANGE about about LONGTEXT DEFAULT NULL, CHANGE name name VARCHAR(255) NOT NULL, CHANGE description description LONGTEXT NOT NULL');
    }
}
