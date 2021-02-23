<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210223143528 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE appointment_time DROP FOREIGN KEY FK_7C575084D8D38EFF');
        $this->addSql('DROP INDEX IDX_7C575084D8D38EFF ON appointment_time');
        $this->addSql('ALTER TABLE appointment_time CHANGE id_appointment_id appointment_type_id INT NOT NULL');
        $this->addSql('ALTER TABLE appointment_time ADD CONSTRAINT FK_7C575084546FBEBB FOREIGN KEY (appointment_type_id) REFERENCES appointment_type (id)');
        $this->addSql('CREATE INDEX IDX_7C575084546FBEBB ON appointment_time (appointment_type_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE appointment_time DROP FOREIGN KEY FK_7C575084546FBEBB');
        $this->addSql('DROP INDEX IDX_7C575084546FBEBB ON appointment_time');
        $this->addSql('ALTER TABLE appointment_time CHANGE appointment_type_id id_appointment_id INT NOT NULL');
        $this->addSql('ALTER TABLE appointment_time ADD CONSTRAINT FK_7C575084D8D38EFF FOREIGN KEY (id_appointment_id) REFERENCES appointment (id)');
        $this->addSql('CREATE INDEX IDX_7C575084D8D38EFF ON appointment_time (id_appointment_id)');
    }
}
