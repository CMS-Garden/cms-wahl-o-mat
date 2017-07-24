<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170724155145 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP SEQUENCE feature_feature_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE features_feature_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE features (feature_id INT NOT NULL, name VARCHAR(256) NOT NULL, title TEXT NOT NULL, description TEXT NOT NULL, value VARCHAR(256) NOT NULL, PRIMARY KEY(feature_id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_BFC0DC135E237E06 ON features (name)');
        $this->addSql('COMMENT ON COLUMN features.title IS \'(DC2Type:array)\'');
        $this->addSql('COMMENT ON COLUMN features.description IS \'(DC2Type:array)\'');
        $this->addSql('DROP TABLE feature');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE features_feature_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE feature_feature_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE feature (feature_id INT NOT NULL, name VARCHAR(256) NOT NULL, title TEXT NOT NULL, description TEXT NOT NULL, value VARCHAR(256) NOT NULL, PRIMARY KEY(feature_id))');
        $this->addSql('CREATE UNIQUE INDEX uniq_1fd775665e237e06 ON feature (name)');
        $this->addSql('COMMENT ON COLUMN feature.title IS \'(DC2Type:array)\'');
        $this->addSql('COMMENT ON COLUMN feature.description IS \'(DC2Type:array)\'');
        $this->addSql('DROP TABLE features');
    }
}
