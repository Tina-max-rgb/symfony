<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221015115224 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE partner (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, is_active TINYINT(1) DEFAULT NULL, UNIQUE INDEX UNIQ_312B3E16A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE partner_permission (partner_id INT NOT NULL, permission_id INT NOT NULL, INDEX IDX_FAB8914C9393F8FE (partner_id), INDEX IDX_FAB8914CFED90CCA (permission_id), PRIMARY KEY(partner_id, permission_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE permission (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, partenaire_id INT NOT NULL, structure_id INT NOT NULL, is_active TINYINT(1) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE structure (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, partner_id INT DEFAULT NULL, adresse VARCHAR(255) NOT NULL, is_active TINYINT(1) DEFAULT NULL, id_permission INT NOT NULL, UNIQUE INDEX UNIQ_6F0137EAA76ED395 (user_id), INDEX IDX_6F0137EA9393F8FE (partner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, is_verified TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE partner ADD CONSTRAINT FK_312B3E16A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE partner_permission ADD CONSTRAINT FK_FAB8914C9393F8FE FOREIGN KEY (partner_id) REFERENCES partner (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE partner_permission ADD CONSTRAINT FK_FAB8914CFED90CCA FOREIGN KEY (permission_id) REFERENCES permission (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE structure ADD CONSTRAINT FK_6F0137EAA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE structure ADD CONSTRAINT FK_6F0137EA9393F8FE FOREIGN KEY (partner_id) REFERENCES partner (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE partner DROP FOREIGN KEY FK_312B3E16A76ED395');
        $this->addSql('ALTER TABLE partner_permission DROP FOREIGN KEY FK_FAB8914C9393F8FE');
        $this->addSql('ALTER TABLE partner_permission DROP FOREIGN KEY FK_FAB8914CFED90CCA');
        $this->addSql('ALTER TABLE structure DROP FOREIGN KEY FK_6F0137EAA76ED395');
        $this->addSql('ALTER TABLE structure DROP FOREIGN KEY FK_6F0137EA9393F8FE');
        $this->addSql('DROP TABLE partner');
        $this->addSql('DROP TABLE partner_permission');
        $this->addSql('DROP TABLE permission');
        $this->addSql('DROP TABLE structure');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
