<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220721143021 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE domain CHANGE name label VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE event ADD starting_hour VARCHAR(255) DEFAULT NULL, ADD ending_hour VARCHAR(255) DEFAULT NULL, ADD register TINYINT(1) NOT NULL, ADD register_begin DATETIME DEFAULT NULL, ADD register_end DATETIME DEFAULT NULL, ADD handicapes TINYINT(1) DEFAULT NULL, ADD link VARCHAR(255) DEFAULT NULL, ADD questions JSON DEFAULT NULL');
        $this->addSql('ALTER TABLE news CHANGE name title VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE news_category CHANGE label label VARCHAR(255) DEFAULT \'other\'');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4F72BA90EA750E8 ON news_category (label)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE domain CHANGE label name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE event DROP starting_hour, DROP ending_hour, DROP register, DROP register_begin, DROP register_end, DROP handicapes, DROP link, DROP questions');
        $this->addSql('ALTER TABLE news CHANGE title name VARCHAR(255) NOT NULL');
        $this->addSql('DROP INDEX UNIQ_4F72BA90EA750E8 ON news_category');
        $this->addSql('ALTER TABLE news_category CHANGE label label VARCHAR(255) NOT NULL');
    }

    public function isTransactional(): bool
    {
        return false;
    }
}
