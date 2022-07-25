<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220725092959 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event CHANGE updated_at updated_at DATETIME DEFAULT NULL, CHANGE image image_path VARCHAR(255) DEFAULT NULL, CHANGE handicapes adapted_to_handicapped TINYINT(1) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event CHANGE updated_at updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE image_path image VARCHAR(255) DEFAULT NULL, CHANGE adapted_to_handicapped handicapes TINYINT(1) DEFAULT NULL');
    }

    public function isTransactional(): bool
    {
        return false;
    }

}
