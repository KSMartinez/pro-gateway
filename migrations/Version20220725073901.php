<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220725073901 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE domain CHANGE name label VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE event ADD associated_group_id INT DEFAULT NULL, ADD starting_hour VARCHAR(255) DEFAULT NULL, ADD ending_hour VARCHAR(255) DEFAULT NULL, ADD register TINYINT(1) NOT NULL, ADD register_begin DATETIME DEFAULT NULL, ADD register_end DATETIME DEFAULT NULL, ADD handicapes TINYINT(1) DEFAULT NULL, ADD link VARCHAR(255) DEFAULT NULL, ADD questions JSON DEFAULT NULL');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA78FFE1531 FOREIGN KEY (associated_group_id) REFERENCES `group` (id)');
        $this->addSql('CREATE INDEX IDX_3BAE0AA78FFE1531 ON event (associated_group_id)');
        $this->addSql('ALTER TABLE news ADD visibility VARCHAR(255) DEFAULT \'private\' COMMENT \'private or public\', ADD published TINYINT(1) DEFAULT 0 COMMENT \'Is published if the request has been approved by the admin\', ADD admin_validation_request VARCHAR(255) DEFAULT NULL, ADD publish_in_my_name TINYINT(1) DEFAULT 0, ADD want_to_publish TINYINT(1) DEFAULT 0 COMMENT \'Would like to publish\', ADD created_at DATETIME DEFAULT NULL, DROP is_public, CHANGE published_at published_at DATETIME DEFAULT NULL, CHANGE name title VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE news_category CHANGE label label VARCHAR(255) DEFAULT \'other\'');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4F72BA90EA750E8 ON news_category (label)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE domain CHANGE label name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA78FFE1531');
        $this->addSql('DROP INDEX IDX_3BAE0AA78FFE1531 ON event');
        $this->addSql('ALTER TABLE event DROP associated_group_id, DROP starting_hour, DROP ending_hour, DROP register, DROP register_begin, DROP register_end, DROP handicapes, DROP link, DROP questions');
        $this->addSql('ALTER TABLE news ADD is_public TINYINT(1) NOT NULL, DROP visibility, DROP published, DROP admin_validation_request, DROP publish_in_my_name, DROP want_to_publish, DROP created_at, CHANGE published_at published_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE title name VARCHAR(255) NOT NULL');
        $this->addSql('DROP INDEX UNIQ_4F72BA90EA750E8 ON news_category');
        $this->addSql('ALTER TABLE news_category CHANGE label label VARCHAR(255) NOT NULL');
    }

    public function isTransactional(): bool
    {
        return false;
    }
}
