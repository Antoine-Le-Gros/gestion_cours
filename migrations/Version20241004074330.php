<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241004074330 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE affectation_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE course_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE course_title_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE external_hour_record_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE hourly_volume_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE module_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE semester_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE type_course_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "user_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE week_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE year_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE affectation (id INT NOT NULL, course_id INT DEFAULT NULL, teacher_id INT DEFAULT NULL, number_group_taken INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_F4DD61D3591CC992 ON affectation (course_id)');
        $this->addSql('CREATE INDEX IDX_F4DD61D341807E1D ON affectation (teacher_id)');
        $this->addSql('CREATE TABLE course (id INT NOT NULL, course_title_id INT NOT NULL, type_course_id INT NOT NULL, saesupport VARCHAR(50) DEFAULT NULL, group_max_number INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_169E6FB9A32BEAEB ON course (course_title_id)');
        $this->addSql('CREATE INDEX IDX_169E6FB9EDDA8882 ON course (type_course_id)');
        $this->addSql('CREATE TABLE course_title (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE external_hour_record (id INT NOT NULL, teacher_id INT NOT NULL, year_id INT NOT NULL, hours DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_6291630041807E1D ON external_hour_record (teacher_id)');
        $this->addSql('CREATE INDEX IDX_6291630040C1FEA7 ON external_hour_record (year_id)');
        $this->addSql('CREATE TABLE hourly_volume (id INT NOT NULL, course_id INT NOT NULL, week_id INT NOT NULL, volume DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_A8061AF4591CC992 ON hourly_volume (course_id)');
        $this->addSql('CREATE INDEX IDX_A8061AF4C86F3B2F ON hourly_volume (week_id)');
        $this->addSql('CREATE TABLE module (id INT NOT NULL, semester_id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_C2426284A798B6F ON module (semester_id)');
        $this->addSql('CREATE TABLE module_course_title (module_id INT NOT NULL, course_title_id INT NOT NULL, PRIMARY KEY(module_id, course_title_id))');
        $this->addSql('CREATE INDEX IDX_AACEE771AFC2B591 ON module_course_title (module_id)');
        $this->addSql('CREATE INDEX IDX_AACEE771A32BEAEB ON module_course_title (course_title_id)');
        $this->addSql('CREATE TABLE semester (id INT NOT NULL, year_id INT NOT NULL, number INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_F7388EED40C1FEA7 ON semester (year_id)');
        $this->addSql('CREATE TABLE type_course (id INT NOT NULL, name VARCHAR(50) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, is_active BOOLEAN NOT NULL, login VARCHAR(50) NOT NULL, hours_max INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL ON "user" (email)');
        $this->addSql('CREATE TABLE week (id INT NOT NULL, semesters_id INT DEFAULT NULL, number INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_5B5A69C03A36B867 ON week (semesters_id)');
        $this->addSql('CREATE TABLE year (id INT NOT NULL, name VARCHAR(9) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE messenger_messages (id BIGSERIAL NOT NULL, body TEXT NOT NULL, headers TEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, available_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, delivered_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
        $this->addSql('COMMENT ON COLUMN messenger_messages.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN messenger_messages.available_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN messenger_messages.delivered_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE OR REPLACE FUNCTION notify_messenger_messages() RETURNS TRIGGER AS $$
            BEGIN
                PERFORM pg_notify(\'messenger_messages\', NEW.queue_name::text);
                RETURN NEW;
            END;
        $$ LANGUAGE plpgsql;');
        $this->addSql('DROP TRIGGER IF EXISTS notify_trigger ON messenger_messages;');
        $this->addSql('CREATE TRIGGER notify_trigger AFTER INSERT OR UPDATE ON messenger_messages FOR EACH ROW EXECUTE PROCEDURE notify_messenger_messages();');
        $this->addSql('ALTER TABLE affectation ADD CONSTRAINT FK_F4DD61D3591CC992 FOREIGN KEY (course_id) REFERENCES course (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE affectation ADD CONSTRAINT FK_F4DD61D341807E1D FOREIGN KEY (teacher_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE course ADD CONSTRAINT FK_169E6FB9A32BEAEB FOREIGN KEY (course_title_id) REFERENCES course_title (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE course ADD CONSTRAINT FK_169E6FB9EDDA8882 FOREIGN KEY (type_course_id) REFERENCES type_course (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE external_hour_record ADD CONSTRAINT FK_6291630041807E1D FOREIGN KEY (teacher_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE external_hour_record ADD CONSTRAINT FK_6291630040C1FEA7 FOREIGN KEY (year_id) REFERENCES year (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE hourly_volume ADD CONSTRAINT FK_A8061AF4591CC992 FOREIGN KEY (course_id) REFERENCES course (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE hourly_volume ADD CONSTRAINT FK_A8061AF4C86F3B2F FOREIGN KEY (week_id) REFERENCES week (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE module ADD CONSTRAINT FK_C2426284A798B6F FOREIGN KEY (semester_id) REFERENCES semester (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE module_course_title ADD CONSTRAINT FK_AACEE771AFC2B591 FOREIGN KEY (module_id) REFERENCES module (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE module_course_title ADD CONSTRAINT FK_AACEE771A32BEAEB FOREIGN KEY (course_title_id) REFERENCES course_title (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE semester ADD CONSTRAINT FK_F7388EED40C1FEA7 FOREIGN KEY (year_id) REFERENCES year (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE week ADD CONSTRAINT FK_5B5A69C03A36B867 FOREIGN KEY (semesters_id) REFERENCES semester (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE affectation_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE course_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE course_title_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE external_hour_record_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE hourly_volume_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE module_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE semester_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE type_course_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE "user_id_seq" CASCADE');
        $this->addSql('DROP SEQUENCE week_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE year_id_seq CASCADE');
        $this->addSql('ALTER TABLE affectation DROP CONSTRAINT FK_F4DD61D3591CC992');
        $this->addSql('ALTER TABLE affectation DROP CONSTRAINT FK_F4DD61D341807E1D');
        $this->addSql('ALTER TABLE course DROP CONSTRAINT FK_169E6FB9A32BEAEB');
        $this->addSql('ALTER TABLE course DROP CONSTRAINT FK_169E6FB9EDDA8882');
        $this->addSql('ALTER TABLE external_hour_record DROP CONSTRAINT FK_6291630041807E1D');
        $this->addSql('ALTER TABLE external_hour_record DROP CONSTRAINT FK_6291630040C1FEA7');
        $this->addSql('ALTER TABLE hourly_volume DROP CONSTRAINT FK_A8061AF4591CC992');
        $this->addSql('ALTER TABLE hourly_volume DROP CONSTRAINT FK_A8061AF4C86F3B2F');
        $this->addSql('ALTER TABLE module DROP CONSTRAINT FK_C2426284A798B6F');
        $this->addSql('ALTER TABLE module_course_title DROP CONSTRAINT FK_AACEE771AFC2B591');
        $this->addSql('ALTER TABLE module_course_title DROP CONSTRAINT FK_AACEE771A32BEAEB');
        $this->addSql('ALTER TABLE semester DROP CONSTRAINT FK_F7388EED40C1FEA7');
        $this->addSql('ALTER TABLE week DROP CONSTRAINT FK_5B5A69C03A36B867');
        $this->addSql('DROP TABLE affectation');
        $this->addSql('DROP TABLE course');
        $this->addSql('DROP TABLE course_title');
        $this->addSql('DROP TABLE external_hour_record');
        $this->addSql('DROP TABLE hourly_volume');
        $this->addSql('DROP TABLE module');
        $this->addSql('DROP TABLE module_course_title');
        $this->addSql('DROP TABLE semester');
        $this->addSql('DROP TABLE type_course');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE week');
        $this->addSql('DROP TABLE year');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
