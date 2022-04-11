<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220411154840 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE saved_offer_search (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, domain_id INT DEFAULT NULL, type_of_contract_id INT DEFAULT NULL, url VARCHAR(255) NOT NULL, name_of_search VARCHAR(255) NOT NULL, title VARCHAR(255) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, city VARCHAR(255) DEFAULT NULL, country VARCHAR(255) DEFAULT NULL, min_salary INT DEFAULT NULL, max_salary INT DEFAULT NULL, company_name VARCHAR(255) DEFAULT NULL, is_active TINYINT(1) NOT NULL, INDEX IDX_E003B3A0A76ED395 (user_id), INDEX IDX_E003B3A0115F0EE5 (domain_id), INDEX IDX_E003B3A0F339FA63 (type_of_contract_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE saved_offer_search ADD CONSTRAINT FK_E003B3A0A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE saved_offer_search ADD CONSTRAINT FK_E003B3A0115F0EE5 FOREIGN KEY (domain_id) REFERENCES domain (id)');
        $this->addSql('ALTER TABLE saved_offer_search ADD CONSTRAINT FK_E003B3A0F339FA63 FOREIGN KEY (type_of_contract_id) REFERENCES type_of_contract (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE saved_offer_search');
    }
}
