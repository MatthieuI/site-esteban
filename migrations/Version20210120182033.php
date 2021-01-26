<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210120182033 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE admin_user ADD status_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE admin_user ADD CONSTRAINT FK_AD8A54A96BF700BD FOREIGN KEY (status_id) REFERENCES status (id)');
        $this->addSql('CREATE INDEX IDX_AD8A54A96BF700BD ON admin_user (status_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE admin_user DROP FOREIGN KEY FK_AD8A54A96BF700BD');
        $this->addSql('DROP INDEX IDX_AD8A54A96BF700BD ON admin_user');
        $this->addSql('ALTER TABLE admin_user DROP status_id');
    }
}
