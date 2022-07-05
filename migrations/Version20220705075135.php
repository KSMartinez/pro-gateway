<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220705075135 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE offer_level_of_education (offer_id INT NOT NULL, level_of_education_id INT NOT NULL, INDEX IDX_2A96259253C674EE (offer_id), INDEX IDX_2A9625924CE2C845 (level_of_education_id), PRIMARY KEY(offer_id, level_of_education_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE offer_level_of_education ADD CONSTRAINT FK_2A96259253C674EE FOREIGN KEY (offer_id) REFERENCES offer (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE offer_level_of_education ADD CONSTRAINT FK_2A9625924CE2C845 FOREIGN KEY (level_of_education_id) REFERENCES level_of_education (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE offer DROP FOREIGN KEY FK_29D6873E4CE2C845');
        $this->addSql('DROP INDEX IDX_29D6873E4CE2C845 ON offer');
        $this->addSql('ALTER TABLE offer DROP level_of_education_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE offer_level_of_education');
        $this->addSql('ALTER TABLE offer ADD level_of_education_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE offer ADD CONSTRAINT FK_29D6873E4CE2C845 FOREIGN KEY (level_of_education_id) REFERENCES level_of_education (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_29D6873E4CE2C845 ON offer (level_of_education_id)');
    }

    public function isTransactional(): bool
    {
        return false;
    }
}
