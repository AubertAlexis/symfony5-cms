<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200727094944 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE template ADD internal_template_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE template ADD CONSTRAINT FK_97601F8330B54F3D FOREIGN KEY (internal_template_id) REFERENCES internal_template (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_97601F8330B54F3D ON template (internal_template_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE template DROP FOREIGN KEY FK_97601F8330B54F3D');
        $this->addSql('DROP INDEX UNIQ_97601F8330B54F3D ON template');
        $this->addSql('ALTER TABLE template DROP internal_template_id');
    }
}
