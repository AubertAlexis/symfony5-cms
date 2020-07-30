<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200730094410 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE seo (id INT AUTO_INCREMENT NOT NULL, meta_title VARCHAR(255) DEFAULT NULL, meta_description LONGTEXT DEFAULT NULL, meta_keywords VARCHAR(255) DEFAULT NULL, no_index TINYINT(1) DEFAULT NULL, no_follow TINYINT(1) DEFAULT NULL, hide_on_sitemap TINYINT(1) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE home_page ADD seo_id INT DEFAULT NULL, CHANGE title title VARCHAR(255) DEFAULT NULL, CHANGE about_title about_title VARCHAR(255) DEFAULT NULL, CHANGE about_image_size about_image_size INT DEFAULT NULL, CHANGE about_image_name about_image_name VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE home_page ADD CONSTRAINT FK_352C07EF97E3DD86 FOREIGN KEY (seo_id) REFERENCES seo (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_352C07EF97E3DD86 ON home_page (seo_id)');
        $this->addSql('ALTER TABLE page ADD seo_id INT DEFAULT NULL, CHANGE template_id template_id INT DEFAULT NULL, CHANGE internal_template_id internal_template_id INT DEFAULT NULL, CHANGE article_template_id article_template_id INT DEFAULT NULL, CHANGE slug slug VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE page ADD CONSTRAINT FK_140AB62097E3DD86 FOREIGN KEY (seo_id) REFERENCES seo (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_140AB62097E3DD86 ON page (seo_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE home_page DROP FOREIGN KEY FK_352C07EF97E3DD86');
        $this->addSql('ALTER TABLE page DROP FOREIGN KEY FK_140AB62097E3DD86');
        $this->addSql('DROP TABLE seo');
        $this->addSql('DROP INDEX UNIQ_352C07EF97E3DD86 ON home_page');
        $this->addSql('ALTER TABLE home_page DROP seo_id, CHANGE title title VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE about_title about_title VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE about_image_size about_image_size INT DEFAULT NULL, CHANGE about_image_name about_image_name VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('DROP INDEX UNIQ_140AB62097E3DD86 ON page');
        $this->addSql('ALTER TABLE page DROP seo_id, CHANGE template_id template_id INT DEFAULT NULL, CHANGE internal_template_id internal_template_id INT DEFAULT NULL, CHANGE article_template_id article_template_id INT DEFAULT NULL, CHANGE slug slug VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
    }
}
