<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250217193025 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reply ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reply ADD CONSTRAINT FK_FDA8C6E0A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('CREATE INDEX IDX_FDA8C6E0A76ED395 ON reply (user_id)');
        $this->addSql('ALTER TABLE thread ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE thread ADD CONSTRAINT FK_31204C83A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('CREATE INDEX IDX_31204C83A76ED395 ON thread (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reply DROP FOREIGN KEY FK_FDA8C6E0A76ED395');
        $this->addSql('DROP INDEX IDX_FDA8C6E0A76ED395 ON reply');
        $this->addSql('ALTER TABLE reply DROP user_id');
        $this->addSql('ALTER TABLE thread DROP FOREIGN KEY FK_31204C83A76ED395');
        $this->addSql('DROP INDEX IDX_31204C83A76ED395 ON thread');
        $this->addSql('ALTER TABLE thread DROP user_id');
    }
}
