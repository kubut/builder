<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161225220258 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE instances (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, database_id INT NOT NULL, checklist_id INT NOT NULL, project_id INT NOT NULL, name VARCHAR(100) NOT NULL, status INT NOT NULL, branch VARCHAR(100) NOT NULL, url VARCHAR(20) NOT NULL, build_date DATETIME NOT NULL, jira_symbol VARCHAR(50) NOT NULL, INDEX IDX_7A270069A76ED395 (user_id), INDEX IDX_7A270069F0AA09DB (database_id), INDEX IDX_7A270069B16D08A7 (checklist_id), INDEX IDX_7A270069166D1F9C (project_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE instances ADD CONSTRAINT FK_7A270069A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE instances ADD CONSTRAINT FK_7A270069F0AA09DB FOREIGN KEY (database_id) REFERENCES database_instances (id)');
        $this->addSql('ALTER TABLE instances ADD CONSTRAINT FK_7A270069B16D08A7 FOREIGN KEY (checklist_id) REFERENCES checklist (id)');
        $this->addSql('ALTER TABLE instances ADD CONSTRAINT FK_7A270069166D1F9C FOREIGN KEY (project_id) REFERENCES projects (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE instances');
    }
}
