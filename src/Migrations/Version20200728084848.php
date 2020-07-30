<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200728084848 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE internal_template CHANGE template_id template_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE page ADD internal_template_id INT DEFAULT NULL, CHANGE template_id template_id INT DEFAULT NULL, CHANGE slug slug VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE page ADD CONSTRAINT FK_140AB62030B54F3D FOREIGN KEY (internal_template_id) REFERENCES internal_template (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_140AB62030B54F3D ON page (internal_template_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE internal_template CHANGE template_id template_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE page DROP FOREIGN KEY FK_140AB62030B54F3D');
        $this->addSql('DROP INDEX UNIQ_140AB62030B54F3D ON page');
        $this->addSql('ALTER TABLE page DROP internal_template_id, CHANGE template_id template_id INT DEFAULT NULL, CHANGE slug slug VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
    }
}
