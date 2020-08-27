<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200827090311 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE article_template (id INT AUTO_INCREMENT NOT NULL, template_id INT DEFAULT NULL, image_name VARCHAR(255) DEFAULT NULL, image_size INT DEFAULT NULL, content TEXT DEFAULT NULL, updated_at DATETIME NOT NULL, INDEX IDX_C288EBE85DA0FB8 (template_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE asset (id INT AUTO_INCREMENT NOT NULL, internal_template_id INT DEFAULT NULL, file_name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_2AF5A5C30B54F3D (internal_template_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE home_page (id INT AUTO_INCREMENT NOT NULL, seo_id INT DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, about_title VARCHAR(255) DEFAULT NULL, about_image_size INT DEFAULT NULL, about_image_name VARCHAR(255) DEFAULT NULL, about_text LONGTEXT DEFAULT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_352C07EF97E3DD86 (seo_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE internal_template (id INT AUTO_INCREMENT NOT NULL, template_id INT DEFAULT NULL, content TEXT DEFAULT NULL, INDEX IDX_58C2925B5DA0FB8 (template_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE module (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) DEFAULT NULL, keyname VARCHAR(255) DEFAULT NULL, enabled TINYINT(1) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE nav (id INT AUTO_INCREMENT NOT NULL, keyname VARCHAR(255) DEFAULT NULL, title VARCHAR(255) NOT NULL, main TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, enabled TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE nav_link (id INT AUTO_INCREMENT NOT NULL, page_id INT DEFAULT NULL, nav_id INT DEFAULT NULL, sub_nav_id INT DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, internal TINYINT(1) NOT NULL, link VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, position INT NOT NULL, INDEX IDX_FD52DA9AC4663E4 (page_id), INDEX IDX_FD52DA9AF03A7216 (nav_id), INDEX IDX_FD52DA9A426B3D5A (sub_nav_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE page (id INT AUTO_INCREMENT NOT NULL, template_id INT DEFAULT NULL, internal_template_id INT DEFAULT NULL, article_template_id INT DEFAULT NULL, seo_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, slug VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, enabled TINYINT(1) NOT NULL, INDEX IDX_140AB6205DA0FB8 (template_id), UNIQUE INDEX UNIQ_140AB62030B54F3D (internal_template_id), UNIQUE INDEX UNIQ_140AB6209F5965DF (article_template_id), UNIQUE INDEX UNIQ_140AB62097E3DD86 (seo_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE seo (id INT AUTO_INCREMENT NOT NULL, meta_title VARCHAR(255) DEFAULT NULL, meta_description LONGTEXT DEFAULT NULL, meta_keywords VARCHAR(255) DEFAULT NULL, no_index TINYINT(1) DEFAULT NULL, no_follow TINYINT(1) DEFAULT NULL, hide_on_sitemap TINYINT(1) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sub_nav (id INT AUTO_INCREMENT NOT NULL, parent_id INT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_EC56DAA6727ACA70 (parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE template (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, keyname VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, username VARCHAR(255) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, reset_token VARCHAR(255) DEFAULT NULL, locale VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE article_template ADD CONSTRAINT FK_C288EBE85DA0FB8 FOREIGN KEY (template_id) REFERENCES template (id)');
        $this->addSql('ALTER TABLE asset ADD CONSTRAINT FK_2AF5A5C30B54F3D FOREIGN KEY (internal_template_id) REFERENCES internal_template (id)');
        $this->addSql('ALTER TABLE home_page ADD CONSTRAINT FK_352C07EF97E3DD86 FOREIGN KEY (seo_id) REFERENCES seo (id)');
        $this->addSql('ALTER TABLE internal_template ADD CONSTRAINT FK_58C2925B5DA0FB8 FOREIGN KEY (template_id) REFERENCES template (id)');
        $this->addSql('ALTER TABLE nav_link ADD CONSTRAINT FK_FD52DA9AC4663E4 FOREIGN KEY (page_id) REFERENCES page (id)');
        $this->addSql('ALTER TABLE nav_link ADD CONSTRAINT FK_FD52DA9AF03A7216 FOREIGN KEY (nav_id) REFERENCES nav (id)');
        $this->addSql('ALTER TABLE nav_link ADD CONSTRAINT FK_FD52DA9A426B3D5A FOREIGN KEY (sub_nav_id) REFERENCES sub_nav (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE page ADD CONSTRAINT FK_140AB6205DA0FB8 FOREIGN KEY (template_id) REFERENCES template (id)');
        $this->addSql('ALTER TABLE page ADD CONSTRAINT FK_140AB62030B54F3D FOREIGN KEY (internal_template_id) REFERENCES internal_template (id)');
        $this->addSql('ALTER TABLE page ADD CONSTRAINT FK_140AB6209F5965DF FOREIGN KEY (article_template_id) REFERENCES article_template (id)');
        $this->addSql('ALTER TABLE page ADD CONSTRAINT FK_140AB62097E3DD86 FOREIGN KEY (seo_id) REFERENCES seo (id)');
        $this->addSql('ALTER TABLE sub_nav ADD CONSTRAINT FK_EC56DAA6727ACA70 FOREIGN KEY (parent_id) REFERENCES nav_link (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE page DROP FOREIGN KEY FK_140AB6209F5965DF');
        $this->addSql('ALTER TABLE asset DROP FOREIGN KEY FK_2AF5A5C30B54F3D');
        $this->addSql('ALTER TABLE page DROP FOREIGN KEY FK_140AB62030B54F3D');
        $this->addSql('ALTER TABLE nav_link DROP FOREIGN KEY FK_FD52DA9AF03A7216');
        $this->addSql('ALTER TABLE sub_nav DROP FOREIGN KEY FK_EC56DAA6727ACA70');
        $this->addSql('ALTER TABLE nav_link DROP FOREIGN KEY FK_FD52DA9AC4663E4');
        $this->addSql('ALTER TABLE home_page DROP FOREIGN KEY FK_352C07EF97E3DD86');
        $this->addSql('ALTER TABLE page DROP FOREIGN KEY FK_140AB62097E3DD86');
        $this->addSql('ALTER TABLE nav_link DROP FOREIGN KEY FK_FD52DA9A426B3D5A');
        $this->addSql('ALTER TABLE article_template DROP FOREIGN KEY FK_C288EBE85DA0FB8');
        $this->addSql('ALTER TABLE internal_template DROP FOREIGN KEY FK_58C2925B5DA0FB8');
        $this->addSql('ALTER TABLE page DROP FOREIGN KEY FK_140AB6205DA0FB8');
        $this->addSql('DROP TABLE article_template');
        $this->addSql('DROP TABLE asset');
        $this->addSql('DROP TABLE home_page');
        $this->addSql('DROP TABLE internal_template');
        $this->addSql('DROP TABLE module');
        $this->addSql('DROP TABLE nav');
        $this->addSql('DROP TABLE nav_link');
        $this->addSql('DROP TABLE page');
        $this->addSql('DROP TABLE seo');
        $this->addSql('DROP TABLE sub_nav');
        $this->addSql('DROP TABLE template');
        $this->addSql('DROP TABLE user');
    }
}
