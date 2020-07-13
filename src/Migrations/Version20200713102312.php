<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200713102312 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE nav (id INT AUTO_INCREMENT NOT NULL, keyname VARCHAR(255) DEFAULT NULL, title VARCHAR(255) NOT NULL, main TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE nav_link (id INT AUTO_INCREMENT NOT NULL, page_id INT DEFAULT NULL, nav_id INT NOT NULL, title VARCHAR(255) NOT NULL, internal TINYINT(1) NOT NULL, link VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_FD52DA9AC4663E4 (page_id), INDEX IDX_FD52DA9AF03A7216 (nav_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE nav_link ADD CONSTRAINT FK_FD52DA9AC4663E4 FOREIGN KEY (page_id) REFERENCES page (id)');
        $this->addSql('ALTER TABLE nav_link ADD CONSTRAINT FK_FD52DA9AF03A7216 FOREIGN KEY (nav_id) REFERENCES nav (id)');
        $this->addSql('ALTER TABLE asset CHANGE page_id page_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE page CHANGE slug slug VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL, CHANGE reset_token reset_token VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE nav_link DROP FOREIGN KEY FK_FD52DA9AF03A7216');
        $this->addSql('DROP TABLE nav');
        $this->addSql('DROP TABLE nav_link');
        $this->addSql('ALTER TABLE asset CHANGE page_id page_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE page CHANGE slug slug VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`, CHANGE reset_token reset_token VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
    }
}
