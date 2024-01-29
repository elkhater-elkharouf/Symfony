<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220331224437 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment CHANGE publication_id publication_id INT NOT NULL, CHANGE created_at created_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE events DROP idevent, CHANGE price price VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE participation DROP FOREIGN KEY FK_AB55E24F3DAAA2E7');
        $this->addSql('DROP INDEX IDX_AB55E24F3DAAA2E7 ON participation');
        $this->addSql('ALTER TABLE participation ADD idevent VARCHAR(100) NOT NULL, DROP eventid_id, CHANGE numtel numtel VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE publication CHANGE image image VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment CHANGE publication_id publication_id INT DEFAULT NULL, CHANGE created_at created_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE events ADD idevent INT NOT NULL, CHANGE price price DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE participation ADD eventid_id INT NOT NULL, DROP idevent, CHANGE numtel numtel INT NOT NULL');
        $this->addSql('ALTER TABLE participation ADD CONSTRAINT FK_AB55E24F3DAAA2E7 FOREIGN KEY (eventid_id) REFERENCES events (id)');
        $this->addSql('CREATE INDEX IDX_AB55E24F3DAAA2E7 ON participation (eventid_id)');
        $this->addSql('ALTER TABLE publication CHANGE image image VARCHAR(255) DEFAULT NULL');
    }
}
