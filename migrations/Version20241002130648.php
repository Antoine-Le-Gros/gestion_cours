<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241002130648 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE week_semester DROP CONSTRAINT fk_4f773d98c86f3b2f');
        $this->addSql('ALTER TABLE week_semester DROP CONSTRAINT fk_4f773d984a798b6f');
        $this->addSql('DROP TABLE week_semester');
        $this->addSql('ALTER TABLE week ADD semesters_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE week ADD CONSTRAINT FK_5B5A69C03A36B867 FOREIGN KEY (semesters_id) REFERENCES semester (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_5B5A69C03A36B867 ON week (semesters_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE TABLE week_semester (week_id INT NOT NULL, semester_id INT NOT NULL, PRIMARY KEY(week_id, semester_id))');
        $this->addSql('CREATE INDEX idx_4f773d984a798b6f ON week_semester (semester_id)');
        $this->addSql('CREATE INDEX idx_4f773d98c86f3b2f ON week_semester (week_id)');
        $this->addSql('ALTER TABLE week_semester ADD CONSTRAINT fk_4f773d98c86f3b2f FOREIGN KEY (week_id) REFERENCES week (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE week_semester ADD CONSTRAINT fk_4f773d984a798b6f FOREIGN KEY (semester_id) REFERENCES semester (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE week DROP CONSTRAINT FK_5B5A69C03A36B867');
        $this->addSql('DROP INDEX IDX_5B5A69C03A36B867');
        $this->addSql('ALTER TABLE week DROP semesters_id');
    }
}
