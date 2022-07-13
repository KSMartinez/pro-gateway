<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220713122123 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE message_status (id INT AUTO_INCREMENT NOT NULL, status VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE message ADD message_status_id INT NOT NULL');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F14E068B FOREIGN KEY (message_status_id) REFERENCES message_status (id)');
        $this->addSql('CREATE INDEX IDX_B6BD307F14E068B ON message (message_status_id)');
        $this->addSql('ALTER TABLE user ADD updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F14E068B');
        $this->addSql('DROP TABLE message_status');
        $this->addSql('DROP INDEX IDX_B6BD307F14E068B ON message');
        $this->addSql('ALTER TABLE message DROP message_status_id');
        $this->addSql('ALTER TABLE user DROP updated_at');
    }

    public function isTransactional(): bool
    {
        return false;
    }

}
