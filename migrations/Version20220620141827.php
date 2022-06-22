<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220620141827 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE event_answer (id INT AUTO_INCREMENT NOT NULL, event_question_id INT DEFAULT NULL, event_creator_id INT NOT NULL, response VARCHAR(255) NOT NULL, INDEX IDX_E8443D77B5AE253F (event_question_id), INDEX IDX_E8443D7739CCD789 (event_creator_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE event_answer ADD CONSTRAINT FK_E8443D77B5AE253F FOREIGN KEY (event_question_id) REFERENCES event_question (id)');
        $this->addSql('ALTER TABLE event_answer ADD CONSTRAINT FK_E8443D7739CCD789 FOREIGN KEY (event_creator_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE event_answer');
    }

    public function isTransactional(): bool
    {
        return false;
    }

    
}
