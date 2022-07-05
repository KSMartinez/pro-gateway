<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220705100826 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE offer_contact (id INT AUTO_INCREMENT NOT NULL, offer_id INT NOT NULL, email VARCHAR(255) DEFAULT NULL, phone VARCHAR(255) DEFAULT NULL, INDEX IDX_ED6A203153C674EE (offer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE offer_contact ADD CONSTRAINT FK_ED6A203153C674EE FOREIGN KEY (offer_id) REFERENCES offer (id)');
        $this->addSql('ALTER TABLE offer ADD url_candidature VARCHAR(255) DEFAULT NULL, ADD is_accessible_for_disabled TINYINT(1) NOT NULL, CHANGE url url_company VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE offer_contact');
        $this->addSql('ALTER TABLE offer ADD url VARCHAR(255) DEFAULT NULL, DROP url_company, DROP url_candidature, DROP is_accessible_for_disabled');
    }

    public function isTransactional(): bool
    {
        return false;
    }
}
