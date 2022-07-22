<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220722083307 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE domain CHANGE name label VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE news CHANGE published_at published_at DATETIME DEFAULT NULL');
        $this->addSql('DROP INDEX UNIQ_4F72BA902B36786B ON news_category');
        $this->addSql('ALTER TABLE news_category CHANGE title label VARCHAR(255) DEFAULT \'other\'');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4F72BA90EA750E8 ON news_category (label)');
        $this->addSql('ALTER TABLE user CHANGE updated_at updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE image_path image_link VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE domain CHANGE label name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE news CHANGE published_at published_at DATETIME NOT NULL');
        $this->addSql('DROP INDEX UNIQ_4F72BA90EA750E8 ON news_category');
        $this->addSql('ALTER TABLE news_category CHANGE label title VARCHAR(255) DEFAULT \'other\'');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4F72BA902B36786B ON news_category (title)');
        $this->addSql('ALTER TABLE user CHANGE updated_at updated_at DATETIME DEFAULT NULL, CHANGE image_link image_path VARCHAR(255) DEFAULT NULL');
    }

    public function isTransactional(): bool
    {
        return false;
    }

}
