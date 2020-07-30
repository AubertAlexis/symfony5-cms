<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200728145137 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE article_template (id INT AUTO_INCREMENT NOT NULL, template_id INT DEFAULT NULL, content TEXT DEFAULT NULL, INDEX IDX_C288EBE85DA0FB8 (template_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE article_template ADD CONSTRAINT FK_C288EBE85DA0FB8 FOREIGN KEY (template_id) REFERENCES template (id)');
        $this->addSql('ALTER TABLE internal_template CHANGE template_id template_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE page ADD article_template_id INT DEFAULT NULL, CHANGE template_id template_id INT DEFAULT NULL, CHANGE internal_template_id internal_template_id INT DEFAULT NULL, CHANGE slug slug VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE page ADD CONSTRAINT FK_140AB6209F5965DF FOREIGN KEY (article_template_id) REFERENCES article_template (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_140AB6209F5965DF ON page (article_template_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE page DROP FOREIGN KEY FK_140AB6209F5965DF');
        $this->addSql('DROP TABLE article_template');
        $this->addSql('ALTER TABLE internal_template CHANGE template_id template_id INT DEFAULT NULL');
        $this->addSql('DROP INDEX UNIQ_140AB6209F5965DF ON page');
        $this->addSql('ALTER TABLE page DROP article_template_id, CHANGE template_id template_id INT DEFAULT NULL, CHANGE internal_template_id internal_template_id INT DEFAULT NULL, CHANGE slug slug VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
    }
}
