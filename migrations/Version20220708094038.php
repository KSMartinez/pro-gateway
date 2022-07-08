<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220708094038 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE offer DROP FOREIGN KEY FK_29D6873EA4445594');
        $this->addSql('CREATE TABLE offer_category (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('DROP TABLE type_of_offer');
        $this->addSql('DROP INDEX IDX_29D6873EA4445594 ON offer');
        $this->addSql('ALTER TABLE offer CHANGE type_of_offer_id offer_category_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE offer ADD CONSTRAINT FK_29D6873E936421EC FOREIGN KEY (offer_category_id) REFERENCES offer_category (id)');
        $this->addSql('CREATE INDEX IDX_29D6873E936421EC ON offer (offer_category_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE offer DROP FOREIGN KEY FK_29D6873E936421EC');
        $this->addSql('CREATE TABLE type_of_offer (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('DROP TABLE offer_category');
        $this->addSql('DROP INDEX IDX_29D6873E936421EC ON offer');
        $this->addSql('ALTER TABLE offer CHANGE offer_category_id type_of_offer_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE offer ADD CONSTRAINT FK_29D6873EA4445594 FOREIGN KEY (type_of_offer_id) REFERENCES type_of_offer (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_29D6873EA4445594 ON offer (type_of_offer_id)');
    }

    public function isTransactional(): bool
    {
        return false;
    }
}
