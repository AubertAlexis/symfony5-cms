<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200911120431 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE contact_template (id INT AUTO_INCREMENT NOT NULL, template_id INT DEFAULT NULL, page_id INT DEFAULT NULL, created_at DATETIME NOT NULL, INDEX IDX_D4583145DA0FB8 (template_id), UNIQUE INDEX UNIQ_D458314C4663E4 (page_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE contact_template ADD CONSTRAINT FK_D4583145DA0FB8 FOREIGN KEY (template_id) REFERENCES template (id)');
        $this->addSql('ALTER TABLE contact_template ADD CONSTRAINT FK_D458314C4663E4 FOREIGN KEY (page_id) REFERENCES page (id)');
        $this->addSql('ALTER TABLE contact ADD contact_template_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE contact ADD CONSTRAINT FK_4C62E6386B76B006 FOREIGN KEY (contact_template_id) REFERENCES contact_template (id)');
        $this->addSql('CREATE INDEX IDX_4C62E6386B76B006 ON contact (contact_template_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE contact DROP FOREIGN KEY FK_4C62E6386B76B006');
        $this->addSql('DROP TABLE contact_template');
        $this->addSql('DROP INDEX IDX_4C62E6386B76B006 ON contact');
        $this->addSql('ALTER TABLE contact DROP contact_template_id');
    }
}
