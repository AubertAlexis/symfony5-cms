<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200727085649 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE internal_template (id INT AUTO_INCREMENT NOT NULL, content TEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE template (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, keyname VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE asset DROP FOREIGN KEY FK_2AF5A5CC4663E4');
        $this->addSql('DROP INDEX IDX_2AF5A5CC4663E4 ON asset');
        $this->addSql('ALTER TABLE asset ADD template_id INT DEFAULT NULL, DROP page_id');
        $this->addSql('ALTER TABLE asset ADD CONSTRAINT FK_2AF5A5C5DA0FB8 FOREIGN KEY (template_id) REFERENCES template (id)');
        $this->addSql('CREATE INDEX IDX_2AF5A5C5DA0FB8 ON asset (template_id)');
        $this->addSql('ALTER TABLE nav CHANGE keyname keyname VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE nav_link CHANGE page_id page_id INT DEFAULT NULL, CHANGE nav_id nav_id INT DEFAULT NULL, CHANGE title title VARCHAR(255) DEFAULT NULL, CHANGE link link VARCHAR(255) DEFAULT NULL, CHANGE sub_nav_id sub_nav_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE page ADD template_id INT DEFAULT NULL, DROP content, CHANGE slug slug VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE page ADD CONSTRAINT FK_140AB6205DA0FB8 FOREIGN KEY (template_id) REFERENCES template (id)');
        $this->addSql('CREATE INDEX IDX_140AB6205DA0FB8 ON page (template_id)');
        $this->addSql('ALTER TABLE sub_nav CHANGE parent_id parent_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL, CHANGE reset_token reset_token VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE asset DROP FOREIGN KEY FK_2AF5A5C5DA0FB8');
        $this->addSql('ALTER TABLE page DROP FOREIGN KEY FK_140AB6205DA0FB8');
        $this->addSql('DROP TABLE internal_template');
        $this->addSql('DROP TABLE template');
        $this->addSql('DROP INDEX IDX_2AF5A5C5DA0FB8 ON asset');
        $this->addSql('ALTER TABLE asset ADD page_id INT DEFAULT NULL, DROP template_id');
        $this->addSql('ALTER TABLE asset ADD CONSTRAINT FK_2AF5A5CC4663E4 FOREIGN KEY (page_id) REFERENCES page (id)');
        $this->addSql('CREATE INDEX IDX_2AF5A5CC4663E4 ON asset (page_id)');
        $this->addSql('ALTER TABLE nav CHANGE keyname keyname VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE nav_link CHANGE page_id page_id INT DEFAULT NULL, CHANGE nav_id nav_id INT DEFAULT NULL, CHANGE sub_nav_id sub_nav_id INT DEFAULT NULL, CHANGE title title VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE link link VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('DROP INDEX IDX_140AB6205DA0FB8 ON page');
        $this->addSql('ALTER TABLE page ADD content TEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, DROP template_id, CHANGE slug slug VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE sub_nav CHANGE parent_id parent_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`, CHANGE reset_token reset_token VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
    }
}
