<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220523123122 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Addition of the accounts in the profile';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user ADD linkedin_account VARCHAR(255) DEFAULT NULL, ADD facebook_account VARCHAR(255) DEFAULT NULL, ADD instagram_account VARCHAR(255) DEFAULT NULL, ADD twitter_account VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP linkedin_account, DROP facebook_account, DROP instagram_account, DROP twitter_account');
    }

    public function isTransactional(): bool
    {
        return false;
    }
}
