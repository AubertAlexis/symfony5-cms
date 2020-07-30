<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200729102101 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE article_template ADD image_name VARCHAR(255) NOT NULL, ADD image_size INT NOT NULL, ADD updated_at DATETIME NOT NULL, CHANGE template_id template_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE asset CHANGE internal_template_id internal_template_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE internal_template CHANGE template_id template_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE nav CHANGE keyname keyname VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE nav_link CHANGE page_id page_id INT DEFAULT NULL, CHANGE nav_id nav_id INT DEFAULT NULL, CHANGE sub_nav_id sub_nav_id INT DEFAULT NULL, CHANGE title title VARCHAR(255) DEFAULT NULL, CHANGE link link VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE page CHANGE template_id template_id INT DEFAULT NULL, CHANGE internal_template_id internal_template_id INT DEFAULT NULL, CHANGE article_template_id article_template_id INT DEFAULT NULL, CHANGE slug slug VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE sub_nav CHANGE parent_id parent_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL, CHANGE reset_token reset_token VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE article_template DROP image_name, DROP image_size, DROP updated_at, CHANGE template_id template_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE asset CHANGE internal_template_id internal_template_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE internal_template CHANGE template_id template_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE nav CHANGE keyname keyname VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE nav_link CHANGE page_id page_id INT DEFAULT NULL, CHANGE nav_id nav_id INT DEFAULT NULL, CHANGE sub_nav_id sub_nav_id INT DEFAULT NULL, CHANGE title title VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE link link VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE page CHANGE template_id template_id INT DEFAULT NULL, CHANGE internal_template_id internal_template_id INT DEFAULT NULL, CHANGE article_template_id article_template_id INT DEFAULT NULL, CHANGE slug slug VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE sub_nav CHANGE parent_id parent_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`, CHANGE reset_token reset_token VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
    }
}
