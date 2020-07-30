<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200727102242 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE asset CHANGE template_id template_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE internal_template ADD template_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE internal_template ADD CONSTRAINT FK_58C2925B5DA0FB8 FOREIGN KEY (template_id) REFERENCES template (id)');
        $this->addSql('CREATE INDEX IDX_58C2925B5DA0FB8 ON internal_template (template_id)');
        $this->addSql('ALTER TABLE nav CHANGE keyname keyname VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE nav_link CHANGE page_id page_id INT DEFAULT NULL, CHANGE nav_id nav_id INT DEFAULT NULL, CHANGE sub_nav_id sub_nav_id INT DEFAULT NULL, CHANGE title title VARCHAR(255) DEFAULT NULL, CHANGE link link VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE page CHANGE template_id template_id INT DEFAULT NULL, CHANGE slug slug VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE sub_nav CHANGE parent_id parent_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE template DROP FOREIGN KEY FK_97601F8330B54F3D');
        $this->addSql('DROP INDEX UNIQ_97601F8330B54F3D ON template');
        $this->addSql('ALTER TABLE template DROP internal_template_id');
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL, CHANGE reset_token reset_token VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE asset CHANGE template_id template_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE internal_template DROP FOREIGN KEY FK_58C2925B5DA0FB8');
        $this->addSql('DROP INDEX IDX_58C2925B5DA0FB8 ON internal_template');
        $this->addSql('ALTER TABLE internal_template DROP template_id');
        $this->addSql('ALTER TABLE nav CHANGE keyname keyname VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE nav_link CHANGE page_id page_id INT DEFAULT NULL, CHANGE nav_id nav_id INT DEFAULT NULL, CHANGE sub_nav_id sub_nav_id INT DEFAULT NULL, CHANGE title title VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE link link VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE page CHANGE template_id template_id INT DEFAULT NULL, CHANGE slug slug VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE sub_nav CHANGE parent_id parent_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE template ADD internal_template_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE template ADD CONSTRAINT FK_97601F8330B54F3D FOREIGN KEY (internal_template_id) REFERENCES internal_template (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_97601F8330B54F3D ON template (internal_template_id)');
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`, CHANGE reset_token reset_token VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
    }
}
