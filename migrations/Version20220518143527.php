<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220518143527 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE education (id INT AUTO_INCREMENT NOT NULL, cv_id INT DEFAULT NULL, diploma VARCHAR(255) DEFAULT NULL, study_level VARCHAR(255) DEFAULT NULL, domain VARCHAR(255) DEFAULT NULL, school VARCHAR(255) DEFAULT NULL, start_month DATE DEFAULT NULL, end_month DATE DEFAULT NULL, start_year DATE DEFAULT NULL, end_year DATE DEFAULT NULL, current_school VARCHAR(255) NOT NULL, INDEX IDX_DB0A5ED2CFE419E2 (cv_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE experience (id INT AUTO_INCREMENT NOT NULL, cv_id INT DEFAULT NULL, description LONGTEXT DEFAULT NULL, jobname VARCHAR(255) NOT NULL, company VARCHAR(255) DEFAULT NULL, category VARCHAR(255) DEFAULT NULL, country VARCHAR(255) DEFAULT NULL, city VARCHAR(255) DEFAULT NULL, start_month DATE DEFAULT NULL, start_year DATE DEFAULT NULL, end_month DATE DEFAULT NULL, end_year DATE DEFAULT NULL, current_job VARCHAR(255) DEFAULT NULL, INDEX IDX_590C103CFE419E2 (cv_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE offer_status (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE skill (id INT AUTO_INCREMENT NOT NULL, cv_id INT DEFAULT NULL, skill1 VARCHAR(255) NOT NULL, skill2 VARCHAR(255) DEFAULT NULL, skill3 VARCHAR(255) DEFAULT NULL, skill4 VARCHAR(255) DEFAULT NULL, skill5 VARCHAR(255) DEFAULT NULL, complementary_skill VARCHAR(255) DEFAULT NULL, INDEX IDX_5E3DE477CFE419E2 (cv_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE education ADD CONSTRAINT FK_DB0A5ED2CFE419E2 FOREIGN KEY (cv_id) REFERENCES cv (id)');
        $this->addSql('ALTER TABLE experience ADD CONSTRAINT FK_590C103CFE419E2 FOREIGN KEY (cv_id) REFERENCES cv (id)');
        $this->addSql('ALTER TABLE skill ADD CONSTRAINT FK_5E3DE477CFE419E2 FOREIGN KEY (cv_id) REFERENCES cv (id)');
        $this->addSql('ALTER TABLE cv DROP experience, DROP education, DROP skills');
        $this->addSql('ALTER TABLE offer ADD created_by_user_id INT DEFAULT NULL, ADD offer_status_id INT NOT NULL, ADD views INT DEFAULT NULL, ADD number_of_candidatures INT DEFAULT NULL, ADD experience VARCHAR(255) DEFAULT NULL, ADD logo_link VARCHAR(255) DEFAULT NULL, ADD date_modified DATE NOT NULL, DROP is_valid');
        $this->addSql('ALTER TABLE offer ADD CONSTRAINT FK_29D6873E7D182D95 FOREIGN KEY (created_by_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE offer ADD CONSTRAINT FK_29D6873EC8327D79 FOREIGN KEY (offer_status_id) REFERENCES offer_status (id)');
        $this->addSql('CREATE INDEX IDX_29D6873E7D182D95 ON offer (created_by_user_id)');
        $this->addSql('CREATE INDEX IDX_29D6873EC8327D79 ON offer (offer_status_id)');
        $this->addSql('ALTER TABLE user ADD charte_signed TINYINT(1) NOT NULL, ADD birthday DATE DEFAULT NULL, ADD telephone VARCHAR(255) DEFAULT NULL, ADD firstname VARCHAR(255) DEFAULT NULL, ADD surname VARCHAR(255) NOT NULL, ADD image_link VARCHAR(255) DEFAULT NULL, ADD profil_title VARCHAR(255) DEFAULT NULL, ADD use_firstname VARCHAR(255) DEFAULT NULL, ADD use_surname VARCHAR(255) DEFAULT NULL, ADD profil_description LONGTEXT DEFAULT NULL, ADD birthday_is_public TINYINT(1) NOT NULL, ADD address VARCHAR(255) DEFAULT NULL, ADD city VARCHAR(255) DEFAULT NULL, ADD country VARCHAR(255) NOT NULL, ADD city_and_country_is_public TINYINT(1) NOT NULL, ADD mail_is_public TINYINT(1) DEFAULT NULL, ADD telephone_is_public TINYINT(1) DEFAULT NULL, ADD address_is_public TINYINT(1) DEFAULT NULL, ADD datas_visible_for_all_members TINYINT(1) DEFAULT NULL, ADD datas_visible_for_annuaire TINYINT(1) DEFAULT NULL, ADD datas_public TINYINT(1) DEFAULT NULL, ADD datas_all_private TINYINT(1) DEFAULT NULL, ADD news_letter_notification TINYINT(1) DEFAULT NULL, ADD rejected_charte TINYINT(1) DEFAULT NULL, ADD available_to_work TINYINT(1) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE offer DROP FOREIGN KEY FK_29D6873EC8327D79');
        $this->addSql('DROP TABLE education');
        $this->addSql('DROP TABLE experience');
        $this->addSql('DROP TABLE offer_status');
        $this->addSql('DROP TABLE skill');
        $this->addSql('ALTER TABLE cv ADD experience VARCHAR(255) DEFAULT NULL, ADD education VARCHAR(255) DEFAULT NULL, ADD skills VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE offer DROP FOREIGN KEY FK_29D6873E7D182D95');
        $this->addSql('DROP INDEX IDX_29D6873E7D182D95 ON offer');
        $this->addSql('DROP INDEX IDX_29D6873EC8327D79 ON offer');
        $this->addSql('ALTER TABLE offer ADD is_valid TINYINT(1) NOT NULL, DROP created_by_user_id, DROP offer_status_id, DROP views, DROP number_of_candidatures, DROP experience, DROP logo_link, DROP date_modified');
        $this->addSql('ALTER TABLE user DROP charte_signed, DROP birthday, DROP telephone, DROP firstname, DROP surname, DROP image_link, DROP profil_title, DROP use_firstname, DROP use_surname, DROP profil_description, DROP birthday_is_public, DROP address, DROP city, DROP country, DROP city_and_country_is_public, DROP mail_is_public, DROP telephone_is_public, DROP address_is_public, DROP datas_visible_for_all_members, DROP datas_visible_for_annuaire, DROP datas_public, DROP datas_all_private, DROP news_letter_notification, DROP rejected_charte, DROP available_to_work');
    }

    public function isTransactional(): bool
    {
        return false;
    }
}
