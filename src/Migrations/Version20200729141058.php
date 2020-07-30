<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200729141058 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE home_page (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) DEFAULT NULL, about_title VARCHAR(255) DEFAULT NULL, about_image VARCHAR(255) DEFAULT NULL, about_text LONGTEXT DEFAULT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE article_template CHANGE template_id template_id INT DEFAULT NULL, CHANGE image_name image_name VARCHAR(255) DEFAULT NULL, CHANGE image_size image_size INT DEFAULT NULL');
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

        $this->addSql('DROP TABLE home_page');
        $this->addSql('ALTER TABLE article_template CHANGE template_id template_id INT DEFAULT NULL, CHANGE image_name image_name VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE image_size image_size INT DEFAULT NULL');
        $this->addSql('ALTER TABLE asset CHANGE internal_template_id internal_template_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE internal_template CHANGE template_id template_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE nav CHANGE keyname keyname VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE nav_link CHANGE page_id page_id INT DEFAULT NULL, CHANGE nav_id nav_id INT DEFAULT NULL, CHANGE sub_nav_id sub_nav_id INT DEFAULT NULL, CHANGE title title VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE link link VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE page CHANGE template_id template_id INT DEFAULT NULL, CHANGE internal_template_id internal_template_id INT DEFAULT NULL, CHANGE article_template_id article_template_id INT DEFAULT NULL, CHANGE slug slug VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE sub_nav CHANGE parent_id parent_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`, CHANGE reset_token reset_token VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
    }
}
