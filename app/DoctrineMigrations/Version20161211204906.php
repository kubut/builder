<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161211204906 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE checklist DROP FOREIGN KEY FK_5C696D2F166D1F9C');
        $this->addSql('ALTER TABLE checklist ADD CONSTRAINT FK_5C696D2F166D1F9C FOREIGN KEY (project_id) REFERENCES projects (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE checklist DROP FOREIGN KEY FK_5C696D2F166D1F9C');
        $this->addSql('ALTER TABLE checklist ADD CONSTRAINT FK_5C696D2F166D1F9C FOREIGN KEY (project_id) REFERENCES checklist (id)');
    }
}
