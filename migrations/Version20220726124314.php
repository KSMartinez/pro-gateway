<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220726124314 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE education_composante (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE education_domain (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE education_speciality (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE `group` ADD education_domain_id INT DEFAULT NULL, ADD education_composante_id INT DEFAULT NULL, ADD education_speciality_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE `group` ADD CONSTRAINT FK_6DC044C585B3211F FOREIGN KEY (education_domain_id) REFERENCES education_domain (id)');
        $this->addSql('ALTER TABLE `group` ADD CONSTRAINT FK_6DC044C57371ED0E FOREIGN KEY (education_composante_id) REFERENCES education_composante (id)');
        $this->addSql('ALTER TABLE `group` ADD CONSTRAINT FK_6DC044C5E4391474 FOREIGN KEY (education_speciality_id) REFERENCES education_speciality (id)');
        $this->addSql('CREATE INDEX IDX_6DC044C585B3211F ON `group` (education_domain_id)');
        $this->addSql('CREATE INDEX IDX_6DC044C57371ED0E ON `group` (education_composante_id)');
        $this->addSql('CREATE INDEX IDX_6DC044C5E4391474 ON `group` (education_speciality_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `group` DROP FOREIGN KEY FK_6DC044C57371ED0E');
        $this->addSql('ALTER TABLE `group` DROP FOREIGN KEY FK_6DC044C585B3211F');
        $this->addSql('ALTER TABLE `group` DROP FOREIGN KEY FK_6DC044C5E4391474');
        $this->addSql('DROP TABLE education_composante');
        $this->addSql('DROP TABLE education_domain');
        $this->addSql('DROP TABLE education_speciality');
        $this->addSql('DROP INDEX IDX_6DC044C585B3211F ON `group`');
        $this->addSql('DROP INDEX IDX_6DC044C57371ED0E ON `group`');
        $this->addSql('DROP INDEX IDX_6DC044C5E4391474 ON `group`');
        $this->addSql('ALTER TABLE `group` DROP education_domain_id, DROP education_composante_id, DROP education_speciality_id');
    }
    
    public function isTransactional(): bool
    {
        return false;
    }
}
