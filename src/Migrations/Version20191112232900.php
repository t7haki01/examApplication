<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191112232900 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE exam (id INT AUTO_INCREMENT NOT NULL, teacher_id INT NOT NULL, date DATETIME NOT NULL, question_ids VARCHAR(255) NOT NULL, is_published TINYINT(1) NOT NULL, exam_title VARCHAR(150) NOT NULL, available_students VARCHAR(100) NOT NULL, INDEX IDX_38BBA6C641807E1D (teacher_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE exam_result (id INT AUTO_INCREMENT NOT NULL, student_id INT NOT NULL, exam_id INT DEFAULT NULL, score VARCHAR(50) NOT NULL, INDEX IDX_D8599799CB944F1A (student_id), INDEX IDX_D8599799578D5E91 (exam_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE result (id INT AUTO_INCREMENT NOT NULL, question_id INT NOT NULL, exam_id INT NOT NULL, student_id INT NOT NULL, student_answer VARCHAR(255) DEFAULT NULL, date DATETIME NOT NULL, is_correct TINYINT(1) NOT NULL, INDEX IDX_136AC1131E27F6BF (question_id), INDEX IDX_136AC113578D5E91 (exam_id), INDEX IDX_136AC113CB944F1A (student_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE student (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE teacher (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, student_id INT DEFAULT NULL, teacher_id INT DEFAULT NULL, firstname VARCHAR(50) DEFAULT NULL, lastname VARCHAR(50) DEFAULT NULL, username VARCHAR(100) NOT NULL, password VARCHAR(255) NOT NULL, email VARCHAR(100) NOT NULL, is_teacher TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_8D93D649CB944F1A (student_id), UNIQUE INDEX UNIQ_8D93D64941807E1D (teacher_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE exam ADD CONSTRAINT FK_38BBA6C641807E1D FOREIGN KEY (teacher_id) REFERENCES teacher (id)');
        $this->addSql('ALTER TABLE exam_result ADD CONSTRAINT FK_D8599799CB944F1A FOREIGN KEY (student_id) REFERENCES student (id)');
        $this->addSql('ALTER TABLE exam_result ADD CONSTRAINT FK_D8599799578D5E91 FOREIGN KEY (exam_id) REFERENCES exam (id)');
        $this->addSql('ALTER TABLE result ADD CONSTRAINT FK_136AC1131E27F6BF FOREIGN KEY (question_id) REFERENCES question (id)');
        $this->addSql('ALTER TABLE result ADD CONSTRAINT FK_136AC113578D5E91 FOREIGN KEY (exam_id) REFERENCES exam (id)');
        $this->addSql('ALTER TABLE result ADD CONSTRAINT FK_136AC113CB944F1A FOREIGN KEY (student_id) REFERENCES student (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649CB944F1A FOREIGN KEY (student_id) REFERENCES student (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64941807E1D FOREIGN KEY (teacher_id) REFERENCES teacher (id)');
        $this->addSql('ALTER TABLE question ADD teacher_id INT NOT NULL, CHANGE examples examples VARCHAR(255) DEFAULT NULL, CHANGE answers answers VARCHAR(255) NOT NULL, CHANGE date date DATETIME NOT NULL');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494E41807E1D FOREIGN KEY (teacher_id) REFERENCES teacher (id)');
        $this->addSql('CREATE INDEX IDX_B6F7494E41807E1D ON question (teacher_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE exam_result DROP FOREIGN KEY FK_D8599799578D5E91');
        $this->addSql('ALTER TABLE result DROP FOREIGN KEY FK_136AC113578D5E91');
        $this->addSql('ALTER TABLE exam_result DROP FOREIGN KEY FK_D8599799CB944F1A');
        $this->addSql('ALTER TABLE result DROP FOREIGN KEY FK_136AC113CB944F1A');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649CB944F1A');
        $this->addSql('ALTER TABLE exam DROP FOREIGN KEY FK_38BBA6C641807E1D');
        $this->addSql('ALTER TABLE question DROP FOREIGN KEY FK_B6F7494E41807E1D');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64941807E1D');
        $this->addSql('DROP TABLE exam');
        $this->addSql('DROP TABLE exam_result');
        $this->addSql('DROP TABLE result');
        $this->addSql('DROP TABLE student');
        $this->addSql('DROP TABLE teacher');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP INDEX IDX_B6F7494E41807E1D ON question');
        $this->addSql('ALTER TABLE question DROP teacher_id, CHANGE examples examples LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:array)\', CHANGE answers answers LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:array)\', CHANGE date date DATETIME DEFAULT NULL');
    }
}
