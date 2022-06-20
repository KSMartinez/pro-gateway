<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220615092243 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event ADD created_by_id INT DEFAULT NULL, ADD max_number_of_participants INT DEFAULT NULL, CHANGE created_at created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA7B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_3BAE0AA7B03A8386 ON event (created_by_id)');
        $this->addSql('ALTER TABLE event_participant ADD event_id INT NOT NULL');
        $this->addSql('ALTER TABLE event_participant ADD CONSTRAINT FK_7C16B89171F7E88B FOREIGN KEY (event_id) REFERENCES event (id)');
        $this->addSql('CREATE INDEX IDX_7C16B89171F7E88B ON event_participant (event_id)');
        $this->addSql('ALTER TABLE event_question ADD question VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA7B03A8386');
        $this->addSql('DROP INDEX IDX_3BAE0AA7B03A8386 ON event');
        $this->addSql('ALTER TABLE event DROP created_by_id, DROP max_number_of_participants, CHANGE created_at created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE event_participant DROP FOREIGN KEY FK_7C16B89171F7E88B');
        $this->addSql('DROP INDEX IDX_7C16B89171F7E88B ON event_participant');
        $this->addSql('ALTER TABLE event_participant DROP event_id');
        $this->addSql('ALTER TABLE event_question DROP question');
    }

    public function isTransactional(): bool
    {
        return false;
    }
}
