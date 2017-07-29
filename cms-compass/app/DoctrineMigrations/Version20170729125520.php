<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170729125520 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE cms_features_cms_feature_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE cms_features (cms_feature_id INT NOT NULL, cms_id INT DEFAULT NULL, feature_id INT DEFAULT NULL, value VARCHAR(256) NOT NULL, PRIMARY KEY(cms_feature_id))');
        $this->addSql('CREATE INDEX IDX_D52E10EEBE8A7CFB ON cms_features (cms_id)');
        $this->addSql('CREATE INDEX IDX_D52E10EE60E4B879 ON cms_features (feature_id)');
        $this->addSql('ALTER TABLE cms_features ADD CONSTRAINT FK_D52E10EEBE8A7CFB FOREIGN KEY (cms_id) REFERENCES cms (cms_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE cms_features ADD CONSTRAINT FK_D52E10EE60E4B879 FOREIGN KEY (feature_id) REFERENCES features (feature_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE cms_features_cms_feature_id_seq CASCADE');
        $this->addSql('DROP TABLE cms_features');
    }
}
