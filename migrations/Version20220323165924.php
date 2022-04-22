<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220323165924 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE domain (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE offer (id INT AUTO_INCREMENT NOT NULL, domain_id INT DEFAULT NULL, type_of_contract_id INT NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, city VARCHAR(255) DEFAULT NULL, country VARCHAR(255) DEFAULT NULL, date_posted DATE NOT NULL, publish_duration INT DEFAULT NULL, min_salary INT DEFAULT NULL, max_salary INT DEFAULT NULL, company_name VARCHAR(255) DEFAULT NULL, is_direct TINYINT(1) NOT NULL, is_valid TINYINT(1) NOT NULL, is_public TINYINT(1) NOT NULL, posted_by VARCHAR(255) DEFAULT NULL, is_of_partner TINYINT(1) NOT NULL, INDEX IDX_29D6873E115F0EE5 (domain_id), INDEX IDX_29D6873EF339FA63 (type_of_contract_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_of_contract (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE offer ADD CONSTRAINT FK_29D6873E115F0EE5 FOREIGN KEY (domain_id) REFERENCES domain (id)');
        $this->addSql('ALTER TABLE offer ADD CONSTRAINT FK_29D6873EF339FA63 FOREIGN KEY (type_of_contract_id) REFERENCES type_of_contract (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE offer DROP FOREIGN KEY FK_29D6873E115F0EE5');
        $this->addSql('ALTER TABLE offer DROP FOREIGN KEY FK_29D6873EF339FA63');
        $this->addSql('DROP TABLE domain');
        $this->addSql('DROP TABLE offer');
        $this->addSql('DROP TABLE type_of_contract');
    }

    public function isTransactional(): bool
    {
        return false;
    }
}
