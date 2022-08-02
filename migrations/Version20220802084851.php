<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220802084851 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event CHANGE is_public public TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE `group` ADD public TINYINT(1) DEFAULT NULL, ADD institutional TINYINT(1) DEFAULT NULL, DROP is_public, DROP is_institutional');
        $this->addSql('ALTER TABLE offer ADD direct TINYINT(1) NOT NULL, ADD public TINYINT(1) NOT NULL, DROP is_direct, DROP is_public');
        $this->addSql('ALTER TABLE offer_draft ADD direct TINYINT(1) DEFAULT NULL, ADD public TINYINT(1) DEFAULT NULL, ADD posted_by_partner TINYINT(1) DEFAULT NULL, ADD accessible_for_disabled TINYINT(1) DEFAULT NULL, ADD created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', DROP is_direct, DROP is_public, DROP is_of_partner, DROP is_accessible_for_disabled, CHANGE update_at update_at DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event CHANGE public is_public TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE `group` ADD is_public TINYINT(1) DEFAULT NULL, ADD is_institutional TINYINT(1) DEFAULT NULL, DROP public, DROP institutional');
        $this->addSql('ALTER TABLE offer ADD is_direct TINYINT(1) NOT NULL, ADD is_public TINYINT(1) NOT NULL, DROP direct, DROP public');
        $this->addSql('ALTER TABLE offer_draft ADD is_direct TINYINT(1) DEFAULT NULL, ADD is_public TINYINT(1) DEFAULT NULL, ADD is_of_partner TINYINT(1) DEFAULT NULL, ADD is_accessible_for_disabled TINYINT(1) DEFAULT NULL, DROP direct, DROP public, DROP posted_by_partner, DROP accessible_for_disabled, DROP created_at, CHANGE update_at update_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
    }

    public function isTransactional(): bool
    {
        return false;
    }

}
