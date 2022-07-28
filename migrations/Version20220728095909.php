<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220728095909 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event CHANGE max_number_of_participants max_number_of_participants VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE offer ADD created_at DATETIME DEFAULT NULL, CHANGE updated_at updated_at DATETIME DEFAULT NULL, CHANGE image image_path VARCHAR(255) DEFAULT NULL, CHANGE is_accessible_for_disabled accessible_for_disabled TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event CHANGE max_number_of_participants max_number_of_participants INT DEFAULT NULL');
        $this->addSql('ALTER TABLE offer DROP created_at, CHANGE updated_at updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE accessible_for_disabled is_accessible_for_disabled TINYINT(1) NOT NULL, CHANGE image_path image VARCHAR(255) DEFAULT NULL');
    }

    public function isTransactional(): bool
    {
        return false;
    }

}
