<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221019181440 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE partner DROP is_active, CHANGE user_id user_id INT NOT NULL');
        $this->addSql('ALTER TABLE structure DROP is_active, CHANGE user_id user_id INT NOT NULL, CHANGE partner_id partner_id INT NOT NULL');
        $this->addSql('ALTER TABLE user ADD created_at DATETIME NOT NULL, ADD updated_at DATETIME NOT NULL, CHANGE is_verified is_active TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE partner ADD is_active TINYINT(1) DEFAULT NULL, CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE structure ADD is_active TINYINT(1) DEFAULT NULL, CHANGE user_id user_id INT DEFAULT NULL, CHANGE partner_id partner_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user DROP created_at, DROP updated_at, CHANGE is_active is_verified TINYINT(1) NOT NULL');
    }
}
