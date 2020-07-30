<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200729104443 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE article_template CHANGE template_id template_id INT DEFAULT NULL, CHANGE image_name image_name VARCHAR(255) DEFAULT NULL, CHANGE image_size image_size INT DEFAULT NULL');
        $this->addSql('ALTER TABLE internal_template CHANGE template_id template_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE page CHANGE template_id template_id INT DEFAULT NULL, CHANGE internal_template_id internal_template_id INT DEFAULT NULL, CHANGE article_template_id article_template_id INT DEFAULT NULL, CHANGE slug slug VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE article_template CHANGE template_id template_id INT DEFAULT NULL, CHANGE image_name image_name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE image_size image_size INT NOT NULL');
        $this->addSql('ALTER TABLE internal_template CHANGE template_id template_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE page CHANGE template_id template_id INT DEFAULT NULL, CHANGE internal_template_id internal_template_id INT DEFAULT NULL, CHANGE article_template_id article_template_id INT DEFAULT NULL, CHANGE slug slug VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
    }
}
