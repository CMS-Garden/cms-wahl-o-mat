<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170728172848 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE cms_cms_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE cms (cms_id INT NOT NULL, name VARCHAR(256) NOT NULL, homepage VARCHAR(2048) NOT NULL, description TEXT NOT NULL, PRIMARY KEY(cms_id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_AC8F99075E237E06 ON cms (name)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_AC8F9907CB24D5EE ON cms (homepage)');
        $this->addSql('COMMENT ON COLUMN cms.description IS \'(DC2Type:array)\'');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE cms_cms_id_seq CASCADE');
        $this->addSql('DROP TABLE cms');
    }
}
