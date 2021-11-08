<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211108174550 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE formule DROP FOREIGN KEY FK_605C9C98E7A1254A');
        $this->addSql('DROP INDEX IDX_605C9C98E7A1254A ON formule');
        $this->addSql('ALTER TABLE formule ADD description LONGTEXT NOT NULL, DROP contact_id');
        $this->addSql('ALTER TABLE support DROP FOREIGN KEY FK_8004EBA5E7A1254A');
        $this->addSql('DROP INDEX IDX_8004EBA5E7A1254A ON support');
        $this->addSql('ALTER TABLE support DROP contact_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE formule ADD contact_id INT DEFAULT NULL, DROP description');
        $this->addSql('ALTER TABLE formule ADD CONSTRAINT FK_605C9C98E7A1254A FOREIGN KEY (contact_id) REFERENCES contact (id)');
        $this->addSql('CREATE INDEX IDX_605C9C98E7A1254A ON formule (contact_id)');
        $this->addSql('ALTER TABLE support ADD contact_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE support ADD CONSTRAINT FK_8004EBA5E7A1254A FOREIGN KEY (contact_id) REFERENCES contact (id)');
        $this->addSql('CREATE INDEX IDX_8004EBA5E7A1254A ON support (contact_id)');
    }
}
