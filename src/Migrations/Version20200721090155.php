<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200721090155 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE sub_nav (id INT AUTO_INCREMENT NOT NULL, parent_id INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_EC56DAA6727ACA70 (parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE sub_nav ADD CONSTRAINT FK_EC56DAA6727ACA70 FOREIGN KEY (parent_id) REFERENCES nav_link (id)');
        $this->addSql('ALTER TABLE nav_link ADD sub_nav_id INT DEFAULT NULL, CHANGE page_id page_id INT DEFAULT NULL, CHANGE title title VARCHAR(255) DEFAULT NULL, CHANGE link link VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE nav_link ADD CONSTRAINT FK_FD52DA9A426B3D5A FOREIGN KEY (sub_nav_id) REFERENCES sub_nav (id)');
        $this->addSql('CREATE INDEX IDX_FD52DA9A426B3D5A ON nav_link (sub_nav_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE nav_link DROP FOREIGN KEY FK_FD52DA9A426B3D5A');
        $this->addSql('DROP TABLE sub_nav');
        $this->addSql('ALTER TABLE nav CHANGE keyname keyname VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('DROP INDEX IDX_FD52DA9A426B3D5A ON nav_link');
        $this->addSql('ALTER TABLE nav_link DROP sub_nav_id, CHANGE page_id page_id INT DEFAULT NULL, CHANGE title title VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE link link VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
    }
}
