<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220525142356 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE group_member (id INT AUTO_INCREMENT NOT NULL, group_of_member_id INT NOT NULL, user_id INT NOT NULL, group_member_status_id INT DEFAULT NULL, roles JSON DEFAULT NULL, INDEX IDX_A36222A8AEB539C1 (group_of_member_id), INDEX IDX_A36222A8A76ED395 (user_id), INDEX IDX_A36222A8D46E2F95 (group_member_status_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE group_member_status (id INT AUTO_INCREMENT NOT NULL, status VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE group_member ADD CONSTRAINT FK_A36222A8AEB539C1 FOREIGN KEY (group_of_member_id) REFERENCES `group` (id)');
        $this->addSql('ALTER TABLE group_member ADD CONSTRAINT FK_A36222A8A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE group_member ADD CONSTRAINT FK_A36222A8D46E2F95 FOREIGN KEY (group_member_status_id) REFERENCES group_member_status (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE group_member DROP FOREIGN KEY FK_A36222A8D46E2F95');
        $this->addSql('DROP TABLE group_member');
        $this->addSql('DROP TABLE group_member_status');
    }

    public function isTransactional(): bool
    {
        return false;
    }
}
