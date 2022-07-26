<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220726115647 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE group_category (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE offer_draft (id INT AUTO_INCREMENT NOT NULL, domain_id INT DEFAULT NULL, type_of_contract_id INT DEFAULT NULL, created_by_user_id INT DEFAULT NULL, offer_status_id INT NOT NULL, offer_category_id INT DEFAULT NULL, sector_id INT DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, city VARCHAR(255) DEFAULT NULL, country VARCHAR(255) DEFAULT NULL, date_posted DATE DEFAULT NULL, publish_duration INT DEFAULT NULL, min_salary INT DEFAULT NULL, max_salary INT DEFAULT NULL, company_name VARCHAR(255) DEFAULT NULL, is_direct TINYINT(1) DEFAULT NULL, is_public TINYINT(1) DEFAULT NULL, posted_by VARCHAR(255) DEFAULT NULL, is_of_partner TINYINT(1) DEFAULT NULL, views INT DEFAULT NULL, number_of_candidatures INT DEFAULT NULL, experience VARCHAR(255) DEFAULT NULL, logo_link VARCHAR(255) DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, offer_id VARCHAR(255) NOT NULL, date_modified DATETIME DEFAULT NULL, url_company VARCHAR(255) DEFAULT NULL, url_candidature VARCHAR(255) DEFAULT NULL, is_accessible_for_disabled TINYINT(1) DEFAULT NULL, update_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_C551EB7D115F0EE5 (domain_id), INDEX IDX_C551EB7DF339FA63 (type_of_contract_id), INDEX IDX_C551EB7D7D182D95 (created_by_user_id), INDEX IDX_C551EB7DC8327D79 (offer_status_id), INDEX IDX_C551EB7D936421EC (offer_category_id), INDEX IDX_C551EB7DDE95C867 (sector_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE offer_draft_level_of_education (offer_draft_id INT NOT NULL, level_of_education_id INT NOT NULL, INDEX IDX_677AFA519B8E75B3 (offer_draft_id), INDEX IDX_677AFA514CE2C845 (level_of_education_id), PRIMARY KEY(offer_draft_id, level_of_education_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE offer_draft ADD CONSTRAINT FK_C551EB7D115F0EE5 FOREIGN KEY (domain_id) REFERENCES domain (id)');
        $this->addSql('ALTER TABLE offer_draft ADD CONSTRAINT FK_C551EB7DF339FA63 FOREIGN KEY (type_of_contract_id) REFERENCES type_of_contract (id)');
        $this->addSql('ALTER TABLE offer_draft ADD CONSTRAINT FK_C551EB7D7D182D95 FOREIGN KEY (created_by_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE offer_draft ADD CONSTRAINT FK_C551EB7DC8327D79 FOREIGN KEY (offer_status_id) REFERENCES offer_status (id)');
        $this->addSql('ALTER TABLE offer_draft ADD CONSTRAINT FK_C551EB7D936421EC FOREIGN KEY (offer_category_id) REFERENCES offer_category (id)');
        $this->addSql('ALTER TABLE offer_draft ADD CONSTRAINT FK_C551EB7DDE95C867 FOREIGN KEY (sector_id) REFERENCES sector_of_offer (id)');
        $this->addSql('ALTER TABLE offer_draft_level_of_education ADD CONSTRAINT FK_677AFA519B8E75B3 FOREIGN KEY (offer_draft_id) REFERENCES offer_draft (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE offer_draft_level_of_education ADD CONSTRAINT FK_677AFA514CE2C845 FOREIGN KEY (level_of_education_id) REFERENCES level_of_education (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE `group` ADD group_category_id INT DEFAULT NULL, ADD graduation_year DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE `group` ADD CONSTRAINT FK_6DC044C537FE8223 FOREIGN KEY (group_category_id) REFERENCES group_category (id)');
        $this->addSql('CREATE INDEX IDX_6DC044C537FE8223 ON `group` (group_category_id)');
        $this->addSql('ALTER TABLE offer_contact ADD offer_draft_id INT NOT NULL');
        $this->addSql('ALTER TABLE offer_contact ADD CONSTRAINT FK_ED6A20319B8E75B3 FOREIGN KEY (offer_draft_id) REFERENCES offer_draft (id)');
        $this->addSql('CREATE INDEX IDX_ED6A20319B8E75B3 ON offer_contact (offer_draft_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `group` DROP FOREIGN KEY FK_6DC044C537FE8223');
        $this->addSql('ALTER TABLE offer_contact DROP FOREIGN KEY FK_ED6A20319B8E75B3');
        $this->addSql('ALTER TABLE offer_draft_level_of_education DROP FOREIGN KEY FK_677AFA519B8E75B3');
        $this->addSql('DROP TABLE group_category');
        $this->addSql('DROP TABLE offer_draft');
        $this->addSql('DROP TABLE offer_draft_level_of_education');
        $this->addSql('DROP INDEX IDX_6DC044C537FE8223 ON `group`');
        $this->addSql('ALTER TABLE `group` DROP group_category_id, DROP graduation_year');
        $this->addSql('DROP INDEX IDX_ED6A20319B8E75B3 ON offer_contact');
        $this->addSql('ALTER TABLE offer_contact DROP offer_draft_id');
    }
    
    public function isTransactional(): bool
    {
        return false;
    }
}
