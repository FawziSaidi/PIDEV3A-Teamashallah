<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250211154435 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE application (id INT AUTO_INCREMENT NOT NULL, id_offer INT DEFAULT NULL, id_student INT DEFAULT NULL, application_date DATE NOT NULL, status VARCHAR(255) NOT NULL, cover_letter VARCHAR(255) NOT NULL, cv_path VARCHAR(255) NOT NULL, INDEX IDX_A45BDDC1C753C60E (id_offer), INDEX IDX_A45BDDC169BE0643 (id_student), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hrmclub (id INT NOT NULL, event JSON DEFAULT NULL COMMENT \'(DC2Type:json)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hrmstage (id INT NOT NULL, company VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE offer (id INT AUTO_INCREMENT NOT NULL, id_hrm_stage INT NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, duration VARCHAR(255) NOT NULL, publication_date DATE NOT NULL, expiration_date DATE NOT NULL, type VARCHAR(255) NOT NULL, desired_skills VARCHAR(255) NOT NULL, INDEX IDX_29D6873E7C15EEAB (id_hrm_stage), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE student (id INT NOT NULL, date_of_birth DATETIME DEFAULT NULL, courses_enrolled LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', year_of_study INT DEFAULT NULL, certifications JSON DEFAULT NULL COMMENT \'(DC2Type:json)\', diplomas JSON DEFAULT NULL COMMENT \'(DC2Type:json)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE teacher (id INT NOT NULL, specialization VARCHAR(255) DEFAULT NULL, courses_taught JSON DEFAULT NULL COMMENT \'(DC2Type:json)\', years_of_experience INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, first_name VARCHAR(255) DEFAULT NULL, last_name VARCHAR(255) DEFAULT NULL, avatar VARCHAR(255) DEFAULT NULL, bio VARCHAR(255) DEFAULT NULL, phone_number VARCHAR(255) DEFAULT NULL, adress VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', discr VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE application ADD CONSTRAINT FK_A45BDDC1C753C60E FOREIGN KEY (id_offer) REFERENCES offer (id)');
        $this->addSql('ALTER TABLE application ADD CONSTRAINT FK_A45BDDC169BE0643 FOREIGN KEY (id_student) REFERENCES student (id)');
        $this->addSql('ALTER TABLE hrmclub ADD CONSTRAINT FK_9BD725A4BF396750 FOREIGN KEY (id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE hrmstage ADD CONSTRAINT FK_ADEFDD95BF396750 FOREIGN KEY (id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE offer ADD CONSTRAINT FK_29D6873E7C15EEAB FOREIGN KEY (id_hrm_stage) REFERENCES hrmstage (id)');
        $this->addSql('ALTER TABLE student ADD CONSTRAINT FK_B723AF33BF396750 FOREIGN KEY (id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE teacher ADD CONSTRAINT FK_B0F6A6D5BF396750 FOREIGN KEY (id) REFERENCES `user` (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE application DROP FOREIGN KEY FK_A45BDDC1C753C60E');
        $this->addSql('ALTER TABLE application DROP FOREIGN KEY FK_A45BDDC169BE0643');
        $this->addSql('ALTER TABLE hrmclub DROP FOREIGN KEY FK_9BD725A4BF396750');
        $this->addSql('ALTER TABLE hrmstage DROP FOREIGN KEY FK_ADEFDD95BF396750');
        $this->addSql('ALTER TABLE offer DROP FOREIGN KEY FK_29D6873E7C15EEAB');
        $this->addSql('ALTER TABLE student DROP FOREIGN KEY FK_B723AF33BF396750');
        $this->addSql('ALTER TABLE teacher DROP FOREIGN KEY FK_B0F6A6D5BF396750');
        $this->addSql('DROP TABLE application');
        $this->addSql('DROP TABLE hrmclub');
        $this->addSql('DROP TABLE hrmstage');
        $this->addSql('DROP TABLE offer');
        $this->addSql('DROP TABLE student');
        $this->addSql('DROP TABLE teacher');
        $this->addSql('DROP TABLE `user`');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
