<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170724155059 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE feature_feature_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE feature (feature_id INT NOT NULL, name VARCHAR(256) NOT NULL, title TEXT NOT NULL, description TEXT NOT NULL, value VARCHAR(256) NOT NULL, PRIMARY KEY(feature_id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1FD775665E237E06 ON feature (name)');
        $this->addSql('COMMENT ON COLUMN feature.title IS \'(DC2Type:array)\'');
        $this->addSql('COMMENT ON COLUMN feature.description IS \'(DC2Type:array)\'');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE feature_feature_id_seq CASCADE');
        $this->addSql('DROP TABLE feature');
    }
}
