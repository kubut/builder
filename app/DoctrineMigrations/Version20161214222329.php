<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161214222329 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE database_instances (id INT AUTO_INCREMENT NOT NULL, project_id INT NOT NULL, name VARCHAR(20) NOT NULL, comment VARCHAR(255) NOT NULL, status INT NOT NULL, INDEX IDX_9719C5E0166D1F9C (project_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE database_instances ADD CONSTRAINT FK_9719C5E0166D1F9C FOREIGN KEY (project_id) REFERENCES projects (id)');
        $this->addSql('DROP TABLE `databases`');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE `databases` (id INT AUTO_INCREMENT NOT NULL, project_id INT NOT NULL, name VARCHAR(20) NOT NULL COLLATE utf8_unicode_ci, comment VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, status INT NOT NULL, INDEX IDX_C71191C2166D1F9C (project_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE `databases` ADD CONSTRAINT FK_C71191C2166D1F9C FOREIGN KEY (project_id) REFERENCES projects (id)');
        $this->addSql('DROP TABLE database_instances');
    }
}
