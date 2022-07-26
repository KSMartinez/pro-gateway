<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220726080642 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event CHANGE updated_at updated_at DATETIME DEFAULT NULL, CHANGE starting_hour starting_hour TIME DEFAULT NULL, CHANGE ending_hour ending_hour TIME DEFAULT NULL, CHANGE image image_path VARCHAR(255) DEFAULT NULL, CHANGE handicapes adapted_to_handicapped TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE `group` ADD is_public TINYINT(1) DEFAULT NULL, ADD is_institutional TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE news CHANGE category_id category_id INT DEFAULT NULL, CHANGE updated_at updated_at DATETIME DEFAULT NULL, CHANGE image image_path VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event CHANGE updated_at updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE starting_hour starting_hour VARCHAR(255) DEFAULT NULL, CHANGE ending_hour ending_hour VARCHAR(255) DEFAULT NULL, CHANGE image_path image VARCHAR(255) DEFAULT NULL, CHANGE adapted_to_handicapped handicapes TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE `group` DROP is_public, DROP is_institutional');
        $this->addSql('ALTER TABLE news CHANGE category_id category_id INT NOT NULL, CHANGE updated_at updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE image_path image VARCHAR(255) DEFAULT NULL');
    }

    public function isTransactional(): bool
    {
        return false;
    }
}
