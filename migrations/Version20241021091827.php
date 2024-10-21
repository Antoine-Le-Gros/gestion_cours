<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241021091827 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE tag_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE tag (id INT NOT NULL, name VARCHAR(50) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE tag_course_title (tag_id INT NOT NULL, course_title_id INT NOT NULL, PRIMARY KEY(tag_id, course_title_id))');
        $this->addSql('CREATE INDEX IDX_628B5481BAD26311 ON tag_course_title (tag_id)');
        $this->addSql('CREATE INDEX IDX_628B5481A32BEAEB ON tag_course_title (course_title_id)');
        $this->addSql('ALTER TABLE tag_course_title ADD CONSTRAINT FK_628B5481BAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE tag_course_title ADD CONSTRAINT FK_628B5481A32BEAEB FOREIGN KEY (course_title_id) REFERENCES course_title (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE tag_id_seq CASCADE');
        $this->addSql('ALTER TABLE tag_course_title DROP CONSTRAINT FK_628B5481BAD26311');
        $this->addSql('ALTER TABLE tag_course_title DROP CONSTRAINT FK_628B5481A32BEAEB');
        $this->addSql('DROP TABLE tag');
        $this->addSql('DROP TABLE tag_course_title');
    }
}
