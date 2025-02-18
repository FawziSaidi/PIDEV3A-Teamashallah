<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250216233321 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE course ADD custom_university VARCHAR(255) DEFAULT NULL, ADD is_paid TINYINT(1) NOT NULL, CHANGE price price NUMERIC(10, 2) DEFAULT NULL, CHANGE date_created date_created DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE progress progress DOUBLE PRECISION DEFAULT NULL, CHANGE category category VARCHAR(255) DEFAULT NULL, CHANGE start_date start_date DATE NOT NULL, CHANGE end_date end_date DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE preferred_previous_knowledge preferred_previous_knowledge JSON DEFAULT NULL COMMENT \'(DC2Type:json)\'');
        $this->addSql('ALTER TABLE lesson ADD name VARCHAR(255) NOT NULL, ADD content LONGTEXT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE course DROP custom_university, DROP is_paid, CHANGE price price NUMERIC(10, 2) NOT NULL, CHANGE date_created date_created DATETIME NOT NULL, CHANGE start_date start_date DATETIME DEFAULT NULL, CHANGE end_date end_date DATETIME DEFAULT NULL, CHANGE progress progress INT NOT NULL, CHANGE preferred_previous_knowledge preferred_previous_knowledge LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', CHANGE category category VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE lesson DROP name, DROP content');
    }
}
