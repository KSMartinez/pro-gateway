<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220531134012 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE level_of_education (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sector_of_offer (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE offer ADD sector_id INT DEFAULT NULL, ADD level_of_education_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE offer ADD CONSTRAINT FK_29D6873EDE95C867 FOREIGN KEY (sector_id) REFERENCES sector_of_offer (id)');
        $this->addSql('ALTER TABLE offer ADD CONSTRAINT FK_29D6873E4CE2C845 FOREIGN KEY (level_of_education_id) REFERENCES level_of_education (id)');
        $this->addSql('CREATE INDEX IDX_29D6873EDE95C867 ON offer (sector_id)');
        $this->addSql('CREATE INDEX IDX_29D6873E4CE2C845 ON offer (level_of_education_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE offer DROP FOREIGN KEY FK_29D6873E4CE2C845');
        $this->addSql('ALTER TABLE offer DROP FOREIGN KEY FK_29D6873EDE95C867');
        $this->addSql('DROP TABLE level_of_education');
        $this->addSql('DROP TABLE sector_of_offer');
        $this->addSql('DROP INDEX IDX_29D6873EDE95C867 ON offer');
        $this->addSql('DROP INDEX IDX_29D6873E4CE2C845 ON offer');
        $this->addSql('ALTER TABLE offer DROP sector_id, DROP level_of_education_id');
    }

    public function isTransactional(): bool
    {
        return false;
    }
}
