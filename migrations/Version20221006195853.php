<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221006195853 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE partner_o_permission (partner_o_id INT NOT NULL, permission_id INT NOT NULL, INDEX IDX_58BBF9098B351773 (partner_o_id), INDEX IDX_58BBF909FED90CCA (permission_id), PRIMARY KEY(partner_o_id, permission_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE partner_o_permission ADD CONSTRAINT FK_58BBF9098B351773 FOREIGN KEY (partner_o_id) REFERENCES partner_o (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE partner_o_permission ADD CONSTRAINT FK_58BBF909FED90CCA FOREIGN KEY (permission_id) REFERENCES permission (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE u ADD is_verified TINYINT(1) NOT NULL, DROP roles');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE partner_o_permission DROP FOREIGN KEY FK_58BBF9098B351773');
        $this->addSql('ALTER TABLE partner_o_permission DROP FOREIGN KEY FK_58BBF909FED90CCA');
        $this->addSql('DROP TABLE partner_o_permission');
        $this->addSql('ALTER TABLE u ADD roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', DROP is_verified');
    }
}
