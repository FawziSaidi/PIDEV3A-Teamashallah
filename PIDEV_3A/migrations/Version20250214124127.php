<?php
declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Add new fields and modify existing fields in the course table.
 */
final class Version20250214123142 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add thumbnail field and modify existing fields in the course table.';
    }

    public function up(Schema $schema): void
    {
        // Add the thumbnail field and modify existing fields
        $this->addSql('ALTER TABLE course 
            ADD thumbnail VARCHAR(255) DEFAULT NULL, 
            CHANGE teacher teacher VARCHAR(255) NOT NULL, 
            CHANGE university university VARCHAR(255) NOT NULL, 
            CHANGE progress progress DOUBLE PRECISION DEFAULT NULL, 
            CHANGE certificat certificat TINYINT(1) DEFAULT NULL, 
            CHANGE lesson_number lesson_number INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // Remove the thumbnail field and revert changes to existing fields
        $this->addSql('ALTER TABLE course 
            DROP thumbnail, 
            CHANGE teacher teacher VARCHAR(255) DEFAULT NULL, 
            CHANGE university university VARCHAR(255) DEFAULT NULL, 
            CHANGE progress progress DOUBLE PRECISION DEFAULT NULL, 
            CHANGE certificat certificat TINYINT(1) DEFAULT NULL, 
            CHANGE lesson_number lessons_number INT NOT NULL');
    }
}