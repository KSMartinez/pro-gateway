<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220608141501 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE advice (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, for_all_universities TINYINT(1) NOT NULL, university VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE candidature (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, offer_id INT NOT NULL, date_of_candidature DATE NOT NULL, message VARCHAR(255) DEFAULT NULL, extra_cvfile_path VARCHAR(255) DEFAULT NULL, INDEX IDX_E33BD3B8A76ED395 (user_id), INDEX IDX_E33BD3B853C674EE (offer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cv (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, file_link VARCHAR(255) DEFAULT NULL, updated_at DATE NOT NULL, UNIQUE INDEX UNIQ_B66FFE92A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE domain (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE education (id INT AUTO_INCREMENT NOT NULL, cv_id INT DEFAULT NULL, diploma VARCHAR(255) DEFAULT NULL, study_level VARCHAR(255) DEFAULT NULL, domain VARCHAR(255) DEFAULT NULL, school VARCHAR(255) DEFAULT NULL, start_month DATE DEFAULT NULL, end_month DATE DEFAULT NULL, start_year DATE DEFAULT NULL, end_year DATE DEFAULT NULL, current_school VARCHAR(255) NOT NULL, INDEX IDX_DB0A5ED2CFE419E2 (cv_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE email_notification (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, source_id INT DEFAULT NULL, message VARCHAR(255) NOT NULL, is_sent TINYINT(1) NOT NULL, sent_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_EA479099A76ED395 (user_id), INDEX IDX_EA479099953C1C61 (source_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE email_template (id INT AUTO_INCREMENT NOT NULL, notification_source_id INT NOT NULL, message_template VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_9C0600CA33D55B3E (notification_source_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, for_all_universities TINYINT(1) NOT NULL, university VARCHAR(255) DEFAULT NULL, is_public TINYINT(1) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', company VARCHAR(255) DEFAULT NULL, max_number_of_participants INT DEFAULT NULL, starting_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ending_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event_participant (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, event_id INT NOT NULL, registration_in_pending TINYINT(1) NOT NULL, registered_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_7C16B891A76ED395 (user_id), INDEX IDX_7C16B89171F7E88B (event_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event_question (id INT AUTO_INCREMENT NOT NULL, event_participant_id INT NOT NULL, question VARCHAR(255) NOT NULL, INDEX IDX_F01BA09E4258866A (event_participant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE experience (id INT AUTO_INCREMENT NOT NULL, cv_id INT DEFAULT NULL, description LONGTEXT DEFAULT NULL, jobname VARCHAR(255) NOT NULL, company VARCHAR(255) DEFAULT NULL, category VARCHAR(255) DEFAULT NULL, country VARCHAR(255) DEFAULT NULL, city VARCHAR(255) DEFAULT NULL, start_month DATE DEFAULT NULL, start_year DATE DEFAULT NULL, end_month DATE DEFAULT NULL, end_year DATE DEFAULT NULL, current_job VARCHAR(255) DEFAULT NULL, INDEX IDX_590C103CFE419E2 (cv_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `group` (id INT AUTO_INCREMENT NOT NULL, group_status_id INT NOT NULL, created_by_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, date_created DATE NOT NULL, description VARCHAR(255) DEFAULT NULL, INDEX IDX_6DC044C5AB445C5C (group_status_id), INDEX IDX_6DC044C5B03A8386 (created_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE group_status (id INT AUTO_INCREMENT NOT NULL, status VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE news (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, for_all_universities TINYINT(1) NOT NULL, university VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE notification (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, source_id INT DEFAULT NULL, message VARCHAR(255) NOT NULL, created_on DATE NOT NULL, is_read TINYINT(1) NOT NULL, INDEX IDX_BF5476CAA76ED395 (user_id), INDEX IDX_BF5476CA953C1C61 (source_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE notification_source (id INT AUTO_INCREMENT NOT NULL, source_label VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE offer (id INT AUTO_INCREMENT NOT NULL, domain_id INT DEFAULT NULL, type_of_contract_id INT NOT NULL, created_by_user_id INT DEFAULT NULL, offer_status_id INT NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, city VARCHAR(255) DEFAULT NULL, country VARCHAR(255) DEFAULT NULL, date_posted DATE NOT NULL, publish_duration INT DEFAULT NULL, min_salary INT DEFAULT NULL, max_salary INT DEFAULT NULL, company_name VARCHAR(255) DEFAULT NULL, is_direct TINYINT(1) NOT NULL, is_public TINYINT(1) NOT NULL, posted_by VARCHAR(255) DEFAULT NULL, is_of_partner TINYINT(1) NOT NULL, views INT DEFAULT NULL, number_of_candidatures INT DEFAULT NULL, experience VARCHAR(255) DEFAULT NULL, logo_link VARCHAR(255) DEFAULT NULL, offer_id VARCHAR(255) NOT NULL, date_modified DATE NOT NULL, INDEX IDX_29D6873E115F0EE5 (domain_id), INDEX IDX_29D6873EF339FA63 (type_of_contract_id), INDEX IDX_29D6873E7D182D95 (created_by_user_id), INDEX IDX_29D6873EC8327D79 (offer_status_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE offer_status (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE saved_offer_search (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, domain_id INT DEFAULT NULL, type_of_contract_id INT DEFAULT NULL, url VARCHAR(255) NOT NULL, name_of_search VARCHAR(255) NOT NULL, title VARCHAR(255) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, city VARCHAR(255) DEFAULT NULL, country VARCHAR(255) DEFAULT NULL, min_salary INT DEFAULT NULL, max_salary INT DEFAULT NULL, company_name VARCHAR(255) DEFAULT NULL, is_active TINYINT(1) NOT NULL, last_search DATE DEFAULT NULL, INDEX IDX_E003B3A0A76ED395 (user_id), INDEX IDX_E003B3A0115F0EE5 (domain_id), INDEX IDX_E003B3A0F339FA63 (type_of_contract_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE skill (id INT AUTO_INCREMENT NOT NULL, cv_id INT DEFAULT NULL, skill1 VARCHAR(255) NOT NULL, skill2 VARCHAR(255) DEFAULT NULL, skill3 VARCHAR(255) DEFAULT NULL, skill4 VARCHAR(255) DEFAULT NULL, skill5 VARCHAR(255) DEFAULT NULL, complementary_skill VARCHAR(255) DEFAULT NULL, INDEX IDX_5E3DE477CFE419E2 (cv_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_of_contract (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, frequency INT DEFAULT NULL, charte_signed TINYINT(1) NOT NULL, birthday DATE DEFAULT NULL, telephone VARCHAR(255) DEFAULT NULL, firstname VARCHAR(255) DEFAULT NULL, surname VARCHAR(255) NOT NULL, image_link VARCHAR(255) DEFAULT NULL, profil_title VARCHAR(255) DEFAULT NULL, use_firstname VARCHAR(255) DEFAULT NULL, use_surname VARCHAR(255) DEFAULT NULL, profil_description LONGTEXT DEFAULT NULL, birthday_is_public TINYINT(1) NOT NULL, address VARCHAR(255) DEFAULT NULL, city VARCHAR(255) DEFAULT NULL, country VARCHAR(255) NOT NULL, city_and_country_is_public TINYINT(1) NOT NULL, mail_is_public TINYINT(1) DEFAULT NULL, telephone_is_public TINYINT(1) DEFAULT NULL, address_is_public TINYINT(1) DEFAULT NULL, datas_visible_for_all_members TINYINT(1) DEFAULT NULL, datas_visible_for_annuaire TINYINT(1) DEFAULT NULL, datas_public TINYINT(1) DEFAULT NULL, datas_all_private TINYINT(1) DEFAULT NULL, news_letter_notification TINYINT(1) DEFAULT NULL, rejected_charte TINYINT(1) DEFAULT NULL, available_to_work TINYINT(1) DEFAULT NULL, company_creator TINYINT(1) DEFAULT NULL, linkedin_account VARCHAR(255) DEFAULT NULL, facebook_account VARCHAR(255) DEFAULT NULL, instagram_account VARCHAR(255) DEFAULT NULL, twitter_account VARCHAR(255) DEFAULT NULL, mentor_accept TINYINT(1) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE candidature ADD CONSTRAINT FK_E33BD3B8A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE candidature ADD CONSTRAINT FK_E33BD3B853C674EE FOREIGN KEY (offer_id) REFERENCES offer (id)');
        $this->addSql('ALTER TABLE cv ADD CONSTRAINT FK_B66FFE92A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE education ADD CONSTRAINT FK_DB0A5ED2CFE419E2 FOREIGN KEY (cv_id) REFERENCES cv (id)');
        $this->addSql('ALTER TABLE email_notification ADD CONSTRAINT FK_EA479099A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE email_notification ADD CONSTRAINT FK_EA479099953C1C61 FOREIGN KEY (source_id) REFERENCES notification_source (id)');
        $this->addSql('ALTER TABLE email_template ADD CONSTRAINT FK_9C0600CA33D55B3E FOREIGN KEY (notification_source_id) REFERENCES notification_source (id)');
        $this->addSql('ALTER TABLE event_participant ADD CONSTRAINT FK_7C16B891A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE event_participant ADD CONSTRAINT FK_7C16B89171F7E88B FOREIGN KEY (event_id) REFERENCES event (id)');
        $this->addSql('ALTER TABLE event_question ADD CONSTRAINT FK_F01BA09E4258866A FOREIGN KEY (event_participant_id) REFERENCES event_participant (id)');
        $this->addSql('ALTER TABLE experience ADD CONSTRAINT FK_590C103CFE419E2 FOREIGN KEY (cv_id) REFERENCES cv (id)');
        $this->addSql('ALTER TABLE `group` ADD CONSTRAINT FK_6DC044C5AB445C5C FOREIGN KEY (group_status_id) REFERENCES group_status (id)');
        $this->addSql('ALTER TABLE `group` ADD CONSTRAINT FK_6DC044C5B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CAA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CA953C1C61 FOREIGN KEY (source_id) REFERENCES notification_source (id)');
        $this->addSql('ALTER TABLE offer ADD CONSTRAINT FK_29D6873E115F0EE5 FOREIGN KEY (domain_id) REFERENCES domain (id)');
        $this->addSql('ALTER TABLE offer ADD CONSTRAINT FK_29D6873EF339FA63 FOREIGN KEY (type_of_contract_id) REFERENCES type_of_contract (id)');
        $this->addSql('ALTER TABLE offer ADD CONSTRAINT FK_29D6873E7D182D95 FOREIGN KEY (created_by_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE offer ADD CONSTRAINT FK_29D6873EC8327D79 FOREIGN KEY (offer_status_id) REFERENCES offer_status (id)');
        $this->addSql('ALTER TABLE saved_offer_search ADD CONSTRAINT FK_E003B3A0A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE saved_offer_search ADD CONSTRAINT FK_E003B3A0115F0EE5 FOREIGN KEY (domain_id) REFERENCES domain (id)');
        $this->addSql('ALTER TABLE saved_offer_search ADD CONSTRAINT FK_E003B3A0F339FA63 FOREIGN KEY (type_of_contract_id) REFERENCES type_of_contract (id)');
        $this->addSql('ALTER TABLE skill ADD CONSTRAINT FK_5E3DE477CFE419E2 FOREIGN KEY (cv_id) REFERENCES cv (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE education DROP FOREIGN KEY FK_DB0A5ED2CFE419E2');
        $this->addSql('ALTER TABLE experience DROP FOREIGN KEY FK_590C103CFE419E2');
        $this->addSql('ALTER TABLE skill DROP FOREIGN KEY FK_5E3DE477CFE419E2');
        $this->addSql('ALTER TABLE offer DROP FOREIGN KEY FK_29D6873E115F0EE5');
        $this->addSql('ALTER TABLE saved_offer_search DROP FOREIGN KEY FK_E003B3A0115F0EE5');
        $this->addSql('ALTER TABLE event_participant DROP FOREIGN KEY FK_7C16B89171F7E88B');
        $this->addSql('ALTER TABLE event_question DROP FOREIGN KEY FK_F01BA09E4258866A');
        $this->addSql('ALTER TABLE `group` DROP FOREIGN KEY FK_6DC044C5AB445C5C');
        $this->addSql('ALTER TABLE email_notification DROP FOREIGN KEY FK_EA479099953C1C61');
        $this->addSql('ALTER TABLE email_template DROP FOREIGN KEY FK_9C0600CA33D55B3E');
        $this->addSql('ALTER TABLE notification DROP FOREIGN KEY FK_BF5476CA953C1C61');
        $this->addSql('ALTER TABLE candidature DROP FOREIGN KEY FK_E33BD3B853C674EE');
        $this->addSql('ALTER TABLE offer DROP FOREIGN KEY FK_29D6873EC8327D79');
        $this->addSql('ALTER TABLE offer DROP FOREIGN KEY FK_29D6873EF339FA63');
        $this->addSql('ALTER TABLE saved_offer_search DROP FOREIGN KEY FK_E003B3A0F339FA63');
        $this->addSql('ALTER TABLE candidature DROP FOREIGN KEY FK_E33BD3B8A76ED395');
        $this->addSql('ALTER TABLE cv DROP FOREIGN KEY FK_B66FFE92A76ED395');
        $this->addSql('ALTER TABLE email_notification DROP FOREIGN KEY FK_EA479099A76ED395');
        $this->addSql('ALTER TABLE event_participant DROP FOREIGN KEY FK_7C16B891A76ED395');
        $this->addSql('ALTER TABLE `group` DROP FOREIGN KEY FK_6DC044C5B03A8386');
        $this->addSql('ALTER TABLE notification DROP FOREIGN KEY FK_BF5476CAA76ED395');
        $this->addSql('ALTER TABLE offer DROP FOREIGN KEY FK_29D6873E7D182D95');
        $this->addSql('ALTER TABLE saved_offer_search DROP FOREIGN KEY FK_E003B3A0A76ED395');
        $this->addSql('DROP TABLE advice');
        $this->addSql('DROP TABLE candidature');
        $this->addSql('DROP TABLE cv');
        $this->addSql('DROP TABLE domain');
        $this->addSql('DROP TABLE education');
        $this->addSql('DROP TABLE email_notification');
        $this->addSql('DROP TABLE email_template');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE event_participant');
        $this->addSql('DROP TABLE event_question');
        $this->addSql('DROP TABLE experience');
        $this->addSql('DROP TABLE `group`');
        $this->addSql('DROP TABLE group_status');
        $this->addSql('DROP TABLE news');
        $this->addSql('DROP TABLE notification');
        $this->addSql('DROP TABLE notification_source');
        $this->addSql('DROP TABLE offer');
        $this->addSql('DROP TABLE offer_status');
        $this->addSql('DROP TABLE saved_offer_search');
        $this->addSql('DROP TABLE skill');
        $this->addSql('DROP TABLE type_of_contract');
        $this->addSql('DROP TABLE user');
    }

    
    public function isTransactional(): bool
    {  
        return false;
    }  
}
