<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220623105836 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE advice ADD document VARCHAR(255) DEFAULT NULL, ADD is_public TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE event ADD end_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD location VARCHAR(255) DEFAULT NULL, ADD category VARCHAR(255) NOT NULL, ADD image VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE event_answer CHANGE event_creator_id event_creator_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE news ADD category VARCHAR(255) DEFAULT NULL, ADD published_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD image VARCHAR(255) DEFAULT NULL, ADD is_public TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE offer ADD url VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE advice DROP document, DROP is_public');
        $this->addSql('ALTER TABLE event DROP end_at, DROP location, DROP category, DROP image');
        $this->addSql('ALTER TABLE event_answer CHANGE event_creator_id event_creator_id INT NOT NULL');
        $this->addSql('ALTER TABLE news DROP category, DROP published_at, DROP image, DROP is_public');
        $this->addSql('ALTER TABLE offer DROP url');
    }
}
