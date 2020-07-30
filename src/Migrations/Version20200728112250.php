<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200728112250 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE asset DROP FOREIGN KEY FK_2AF5A5C5DA0FB8');
        $this->addSql('DROP INDEX IDX_2AF5A5C5DA0FB8 ON asset');
        $this->addSql('ALTER TABLE asset ADD internal_template_id INT DEFAULT NULL, DROP template_id');
        $this->addSql('ALTER TABLE asset ADD CONSTRAINT FK_2AF5A5C30B54F3D FOREIGN KEY (internal_template_id) REFERENCES internal_template (id)');
        $this->addSql('CREATE INDEX IDX_2AF5A5C30B54F3D ON asset (internal_template_id)');
        $this->addSql('ALTER TABLE internal_template CHANGE template_id template_id INT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE asset DROP FOREIGN KEY FK_2AF5A5C30B54F3D');
        $this->addSql('DROP INDEX IDX_2AF5A5C30B54F3D ON asset');
        $this->addSql('ALTER TABLE asset ADD template_id INT DEFAULT NULL, DROP internal_template_id');
        $this->addSql('ALTER TABLE asset ADD CONSTRAINT FK_2AF5A5C5DA0FB8 FOREIGN KEY (template_id) REFERENCES template (id)');
        $this->addSql('CREATE INDEX IDX_2AF5A5C5DA0FB8 ON asset (template_id)');
        $this->addSql('ALTER TABLE internal_template CHANGE template_id template_id INT DEFAULT NULL');
    }
}
