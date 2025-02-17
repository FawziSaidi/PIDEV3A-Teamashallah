<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250217192543 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE forum (id INT AUTO_INCREMENT NOT NULL, admin_id INT DEFAULT NULL, title_forum VARCHAR(255) NOT NULL, description_forum VARCHAR(255) NOT NULL, category VARCHAR(255) NOT NULL, created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_852BBECD642B8210 (admin_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reply (id INT AUTO_INCREMENT NOT NULL, thread_id INT NOT NULL, reply_content VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_FDA8C6E0E2904019 (thread_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE thread (id INT AUTO_INCREMENT NOT NULL, forum_id INT NOT NULL, thread_content VARCHAR(790) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', likes INT DEFAULT NULL, INDEX IDX_31204C8329CCBAD0 (forum_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE forum ADD CONSTRAINT FK_852BBECD642B8210 FOREIGN KEY (admin_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE reply ADD CONSTRAINT FK_FDA8C6E0E2904019 FOREIGN KEY (thread_id) REFERENCES thread (id)');
        $this->addSql('ALTER TABLE thread ADD CONSTRAINT FK_31204C8329CCBAD0 FOREIGN KEY (forum_id) REFERENCES forum (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE forum DROP FOREIGN KEY FK_852BBECD642B8210');
        $this->addSql('ALTER TABLE reply DROP FOREIGN KEY FK_FDA8C6E0E2904019');
        $this->addSql('ALTER TABLE thread DROP FOREIGN KEY FK_31204C8329CCBAD0');
        $this->addSql('DROP TABLE forum');
        $this->addSql('DROP TABLE reply');
        $this->addSql('DROP TABLE thread');
    }
}
