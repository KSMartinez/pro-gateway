<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220721131223 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE news CHANGE category_id category_id INT DEFAULT NULL, CHANGE updated_at updated_at DATETIME DEFAULT NULL, CHANGE image image_path VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE news_category CHANGE title title VARCHAR(255) DEFAULT \'other\'');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4F72BA902B36786B ON news_category (title)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE news CHANGE category_id category_id INT NOT NULL, CHANGE updated_at updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE image_path image VARCHAR(255) DEFAULT NULL');
        $this->addSql('DROP INDEX UNIQ_4F72BA902B36786B ON news_category');
        $this->addSql('ALTER TABLE news_category CHANGE title title VARCHAR(255) NOT NULL');
    }


    public function isTransactional(): bool
    {
        return false;
    }
}