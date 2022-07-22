<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220721183626 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE news ADD visibility VARCHAR(255) DEFAULT \'private\' COMMENT \'private or public\', ADD published TINYINT(1) DEFAULT 0 COMMENT \'Is published if the request has been approved by the admin\', ADD admin_validation_request VARCHAR(255) DEFAULT NULL, ADD publish_in_my_name TINYINT(1) DEFAULT 0, ADD want_to_publish TINYINT(1) DEFAULT 0 COMMENT \'Would like to publish\', ADD created_at DATETIME DEFAULT NULL, DROP is_public, CHANGE published_at published_at DATETIME NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE news ADD is_public TINYINT(1) NOT NULL, DROP visibility, DROP published, DROP admin_validation_request, DROP publish_in_my_name, DROP want_to_publish, DROP created_at, CHANGE published_at published_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
    }

    public function isTransactional(): bool
    {
        return false;
    }
}
