<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180915064509 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TABLE subject (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, year VARCHAR(2) NOT NULL)');
        $this->addSql('CREATE TABLE subject_student (subject_id INTEGER NOT NULL, student_id INTEGER NOT NULL, PRIMARY KEY(subject_id, student_id))');
        $this->addSql('CREATE INDEX IDX_12A1039123EDC87 ON subject_student (subject_id)');
        $this->addSql('CREATE INDEX IDX_12A10391CB944F1A ON subject_student (student_id)');
        $this->addSql('CREATE TABLE task (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, subject_id INTEGER NOT NULL, title VARCHAR(255) NOT NULL, description CLOB NOT NULL, dead_line_at DATE NOT NULL)');
        $this->addSql('CREATE INDEX IDX_527EDB2523EDC87 ON task (subject_id)');
        $this->addSql('CREATE TABLE task_student (task_id INTEGER NOT NULL, student_id INTEGER NOT NULL, PRIMARY KEY(task_id, student_id))');
        $this->addSql('CREATE INDEX IDX_A8C285D98DB60186 ON task_student (task_id)');
        $this->addSql('CREATE INDEX IDX_A8C285D9CB944F1A ON task_student (student_id)');
        $this->addSql('CREATE TABLE student (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, username VARCHAR(64) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, roles CLOB NOT NULL --(DC2Type:array)
        )');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B723AF33F85E0677 ON student (username)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B723AF33E7927C74 ON student (email)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP TABLE subject');
        $this->addSql('DROP TABLE subject_student');
        $this->addSql('DROP TABLE task');
        $this->addSql('DROP TABLE task_student');
        $this->addSql('DROP TABLE student');
    }
}
