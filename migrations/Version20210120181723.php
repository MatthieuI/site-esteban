<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210120181723 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE admin_user ADD id_status_id INT NOT NULL');
        $this->addSql('ALTER TABLE admin_user ADD CONSTRAINT FK_AD8A54A9EBC2BC9A FOREIGN KEY (id_status_id) REFERENCES status (id)');
        $this->addSql('CREATE INDEX IDX_AD8A54A9EBC2BC9A ON admin_user (id_status_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE admin_user DROP FOREIGN KEY FK_AD8A54A9EBC2BC9A');
        $this->addSql('DROP INDEX IDX_AD8A54A9EBC2BC9A ON admin_user');
        $this->addSql('ALTER TABLE admin_user DROP id_status_id');
    }
}
