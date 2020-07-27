<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200721143410 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE asset CHANGE page_id page_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE nav CHANGE keyname keyname VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE nav_link DROP FOREIGN KEY FK_FD52DA9A426B3D5A');
        $this->addSql('ALTER TABLE nav_link CHANGE page_id page_id INT DEFAULT NULL, CHANGE nav_id nav_id INT DEFAULT NULL, CHANGE sub_nav_id sub_nav_id INT DEFAULT NULL, CHANGE title title VARCHAR(255) DEFAULT NULL, CHANGE link link VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE nav_link ADD CONSTRAINT FK_FD52DA9A426B3D5A FOREIGN KEY (sub_nav_id) REFERENCES sub_nav (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE page CHANGE slug slug VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE sub_nav CHANGE parent_id parent_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL, CHANGE reset_token reset_token VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE asset CHANGE page_id page_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE nav CHANGE keyname keyname VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE nav_link DROP FOREIGN KEY FK_FD52DA9A426B3D5A');
        $this->addSql('ALTER TABLE nav_link CHANGE page_id page_id INT DEFAULT NULL, CHANGE nav_id nav_id INT DEFAULT NULL, CHANGE sub_nav_id sub_nav_id INT DEFAULT NULL, CHANGE title title VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE link link VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE nav_link ADD CONSTRAINT FK_FD52DA9A426B3D5A FOREIGN KEY (sub_nav_id) REFERENCES sub_nav (id)');
        $this->addSql('ALTER TABLE page CHANGE slug slug VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE sub_nav CHANGE parent_id parent_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`, CHANGE reset_token reset_token VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
    }
}
