<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220715092103 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event ADD starting_hour VARCHAR(255) DEFAULT NULL, ADD ending_hour VARCHAR(255) DEFAULT NULL, ADD register TINYINT(1) NOT NULL, ADD register_begin DATETIME DEFAULT NULL, ADD register_end DATETIME DEFAULT NULL, ADD handicapes TINYINT(1) DEFAULT NULL, ADD link VARCHAR(255) DEFAULT NULL, ADD questions JSON DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event DROP starting_hour, DROP ending_hour, DROP register, DROP register_begin, DROP register_end, DROP handicapes, DROP link, DROP questions');
    }
    
    public function isTransactional(): bool
    {
        return false;
    }
}
