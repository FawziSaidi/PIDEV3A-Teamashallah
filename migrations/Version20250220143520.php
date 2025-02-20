<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250220143520 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE application (id INT AUTO_INCREMENT NOT NULL, id_offer INT DEFAULT NULL, id_student INT DEFAULT NULL, application_date DATE NOT NULL, status VARCHAR(255) NOT NULL, cover_letter VARCHAR(255) NOT NULL, cv_path VARCHAR(255) NOT NULL, INDEX IDX_A45BDDC1C753C60E (id_offer), INDEX IDX_A45BDDC169BE0643 (id_student), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event (id INT AUTO_INCREMENT NOT NULL, hrm_club_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, details VARCHAR(255) NOT NULL, location VARCHAR(255) NOT NULL, available_seats INT NOT NULL, date DATETIME DEFAULT NULL, poster VARCHAR(255) NOT NULL, INDEX IDX_3BAE0AA7AE73C1E1 (hrm_club_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE forum (id INT AUTO_INCREMENT NOT NULL, admin_id INT DEFAULT NULL, title_forum VARCHAR(255) NOT NULL, description_forum VARCHAR(255) NOT NULL, category VARCHAR(255) NOT NULL, created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_852BBECD642B8210 (admin_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hrmclub (id INT NOT NULL, club VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hrmstage (id INT NOT NULL, company VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE offer (id INT AUTO_INCREMENT NOT NULL, id_hrm_stage INT NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, duration VARCHAR(255) NOT NULL, publication_date DATE NOT NULL, expiration_date DATE NOT NULL, type VARCHAR(255) NOT NULL, desired_skills VARCHAR(255) NOT NULL, poster VARCHAR(255) NOT NULL, INDEX IDX_29D6873E7C15EEAB (id_hrm_stage), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reply (id INT AUTO_INCREMENT NOT NULL, thread_id INT NOT NULL, user_id INT DEFAULT NULL, reply_content VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_FDA8C6E0E2904019 (thread_id), INDEX IDX_FDA8C6E0A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reservation (id INT AUTO_INCREMENT NOT NULL, event_id INT NOT NULL, student_id INT DEFAULT NULL, reservation_status VARCHAR(255) NOT NULL, number_of_seats INT NOT NULL, INDEX IDX_42C8495571F7E88B (event_id), INDEX IDX_42C84955CB944F1A (student_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE student (id INT NOT NULL, date_of_birth DATETIME DEFAULT NULL, courses_enrolled LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', year_of_study INT DEFAULT NULL, certifications JSON DEFAULT NULL, diplomas JSON DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE teacher (id INT NOT NULL, specialization VARCHAR(255) DEFAULT NULL, courses_taught JSON DEFAULT NULL, years_of_experience INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE thread (id INT AUTO_INCREMENT NOT NULL, forum_id INT NOT NULL, user_id INT DEFAULT NULL, thread_content VARCHAR(790) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', likes INT DEFAULT NULL, INDEX IDX_31204C8329CCBAD0 (forum_id), INDEX IDX_31204C83A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, first_name VARCHAR(255) DEFAULT NULL, last_name VARCHAR(255) DEFAULT NULL, avatar VARCHAR(255) DEFAULT NULL, bio VARCHAR(255) DEFAULT NULL, phone_number VARCHAR(255) DEFAULT NULL, adress VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', discr VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE application ADD CONSTRAINT FK_A45BDDC1C753C60E FOREIGN KEY (id_offer) REFERENCES offer (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE application ADD CONSTRAINT FK_A45BDDC169BE0643 FOREIGN KEY (id_student) REFERENCES student (id)');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA7AE73C1E1 FOREIGN KEY (hrm_club_id) REFERENCES hrmclub (id)');
        $this->addSql('ALTER TABLE forum ADD CONSTRAINT FK_852BBECD642B8210 FOREIGN KEY (admin_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE hrmclub ADD CONSTRAINT FK_9BD725A4BF396750 FOREIGN KEY (id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE hrmstage ADD CONSTRAINT FK_ADEFDD95BF396750 FOREIGN KEY (id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE offer ADD CONSTRAINT FK_29D6873E7C15EEAB FOREIGN KEY (id_hrm_stage) REFERENCES hrmstage (id)');
        $this->addSql('ALTER TABLE reply ADD CONSTRAINT FK_FDA8C6E0E2904019 FOREIGN KEY (thread_id) REFERENCES thread (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reply ADD CONSTRAINT FK_FDA8C6E0A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C8495571F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955CB944F1A FOREIGN KEY (student_id) REFERENCES student (id)');
        $this->addSql('ALTER TABLE student ADD CONSTRAINT FK_B723AF33BF396750 FOREIGN KEY (id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE teacher ADD CONSTRAINT FK_B0F6A6D5BF396750 FOREIGN KEY (id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE thread ADD CONSTRAINT FK_31204C8329CCBAD0 FOREIGN KEY (forum_id) REFERENCES forum (id)');
        $this->addSql('ALTER TABLE thread ADD CONSTRAINT FK_31204C83A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE application DROP FOREIGN KEY FK_A45BDDC1C753C60E');
        $this->addSql('ALTER TABLE application DROP FOREIGN KEY FK_A45BDDC169BE0643');
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA7AE73C1E1');
        $this->addSql('ALTER TABLE forum DROP FOREIGN KEY FK_852BBECD642B8210');
        $this->addSql('ALTER TABLE hrmclub DROP FOREIGN KEY FK_9BD725A4BF396750');
        $this->addSql('ALTER TABLE hrmstage DROP FOREIGN KEY FK_ADEFDD95BF396750');
        $this->addSql('ALTER TABLE offer DROP FOREIGN KEY FK_29D6873E7C15EEAB');
        $this->addSql('ALTER TABLE reply DROP FOREIGN KEY FK_FDA8C6E0E2904019');
        $this->addSql('ALTER TABLE reply DROP FOREIGN KEY FK_FDA8C6E0A76ED395');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C8495571F7E88B');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955CB944F1A');
        $this->addSql('ALTER TABLE student DROP FOREIGN KEY FK_B723AF33BF396750');
        $this->addSql('ALTER TABLE teacher DROP FOREIGN KEY FK_B0F6A6D5BF396750');
        $this->addSql('ALTER TABLE thread DROP FOREIGN KEY FK_31204C8329CCBAD0');
        $this->addSql('ALTER TABLE thread DROP FOREIGN KEY FK_31204C83A76ED395');
        $this->addSql('DROP TABLE application');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE forum');
        $this->addSql('DROP TABLE hrmclub');
        $this->addSql('DROP TABLE hrmstage');
        $this->addSql('DROP TABLE offer');
        $this->addSql('DROP TABLE reply');
        $this->addSql('DROP TABLE reservation');
        $this->addSql('DROP TABLE student');
        $this->addSql('DROP TABLE teacher');
        $this->addSql('DROP TABLE thread');
        $this->addSql('DROP TABLE `user`');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
