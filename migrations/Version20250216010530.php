<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250216010530 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE event (id INT AUTO_INCREMENT NOT NULL, hrm_club_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, details VARCHAR(255) NOT NULL, location VARCHAR(255) NOT NULL, available_seats INT NOT NULL, date DATETIME NOT NULL, poster VARCHAR(255) NOT NULL, INDEX IDX_3BAE0AA7AE73C1E1 (hrm_club_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reservation (id INT AUTO_INCREMENT NOT NULL, event_id INT NOT NULL, student_id INT DEFAULT NULL, reservation_status VARCHAR(255) NOT NULL, number_of_seats INT NOT NULL, INDEX IDX_42C8495571F7E88B (event_id), INDEX IDX_42C84955CB944F1A (student_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA7AE73C1E1 FOREIGN KEY (hrm_club_id) REFERENCES hrmclub (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C8495571F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955CB944F1A FOREIGN KEY (student_id) REFERENCES student (id)');
        $this->addSql('ALTER TABLE application DROP FOREIGN KEY FK_A45BDDC1C753C60E');
        $this->addSql('ALTER TABLE application ADD CONSTRAINT FK_A45BDDC1C753C60E FOREIGN KEY (id_offer) REFERENCES offer (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA7AE73C1E1');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C8495571F7E88B');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955CB944F1A');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE reservation');
        $this->addSql('ALTER TABLE application DROP FOREIGN KEY FK_A45BDDC1C753C60E');
        $this->addSql('ALTER TABLE application ADD CONSTRAINT FK_A45BDDC1C753C60E FOREIGN KEY (id_offer) REFERENCES offer (id)');
    }
}
