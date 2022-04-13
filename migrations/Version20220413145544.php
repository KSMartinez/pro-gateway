<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220413145544 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE email_notification (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, source_id INT DEFAULT NULL, message VARCHAR(255) NOT NULL, is_sent TINYINT(1) NOT NULL, sent_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_EA479099A76ED395 (user_id), INDEX IDX_EA479099953C1C61 (source_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE notification_source (id INT AUTO_INCREMENT NOT NULL, source_label VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE saved_offer_search (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, domain_id INT DEFAULT NULL, type_of_contract_id INT DEFAULT NULL, url VARCHAR(255) NOT NULL, name_of_search VARCHAR(255) NOT NULL, title VARCHAR(255) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, city VARCHAR(255) DEFAULT NULL, country VARCHAR(255) DEFAULT NULL, min_salary INT DEFAULT NULL, max_salary INT DEFAULT NULL, company_name VARCHAR(255) DEFAULT NULL, is_active TINYINT(1) NOT NULL, last_search DATE DEFAULT NULL, INDEX IDX_E003B3A0A76ED395 (user_id), INDEX IDX_E003B3A0115F0EE5 (domain_id), INDEX IDX_E003B3A0F339FA63 (type_of_contract_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE email_notification ADD CONSTRAINT FK_EA479099A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE email_notification ADD CONSTRAINT FK_EA479099953C1C61 FOREIGN KEY (source_id) REFERENCES notification_source (id)');
        $this->addSql('ALTER TABLE saved_offer_search ADD CONSTRAINT FK_E003B3A0A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE saved_offer_search ADD CONSTRAINT FK_E003B3A0115F0EE5 FOREIGN KEY (domain_id) REFERENCES domain (id)');
        $this->addSql('ALTER TABLE saved_offer_search ADD CONSTRAINT FK_E003B3A0F339FA63 FOREIGN KEY (type_of_contract_id) REFERENCES type_of_contract (id)');
        $this->addSql('ALTER TABLE notification ADD source_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CA953C1C61 FOREIGN KEY (source_id) REFERENCES notification_source (id)');
        $this->addSql('CREATE INDEX IDX_BF5476CA953C1C61 ON notification (source_id)');
        $this->addSql('ALTER TABLE user ADD frequency INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE email_notification DROP FOREIGN KEY FK_EA479099953C1C61');
        $this->addSql('ALTER TABLE notification DROP FOREIGN KEY FK_BF5476CA953C1C61');
        $this->addSql('DROP TABLE email_notification');
        $this->addSql('DROP TABLE notification_source');
        $this->addSql('DROP TABLE saved_offer_search');
        $this->addSql('DROP INDEX IDX_BF5476CA953C1C61 ON notification');
        $this->addSql('ALTER TABLE notification DROP source_id');
        $this->addSql('ALTER TABLE user DROP frequency');
    }
}
