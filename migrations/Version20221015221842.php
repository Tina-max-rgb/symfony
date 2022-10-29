<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221015221842 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE partner DROP nom');
        $this->addSql('ALTER TABLE structure DROP adresse, DROP nom');
        $this->addSql('ALTER TABLE user ADD adresse VARCHAR(255) NOT NULL, ADD nom VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE partner ADD nom VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE structure ADD adresse VARCHAR(255) NOT NULL, ADD nom VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE user DROP adresse, DROP nom');
    }
}
