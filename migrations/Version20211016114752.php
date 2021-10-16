<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211016114752 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE Cotisation (id INT AUTO_INCREMENT NOT NULL, membre_id INT DEFAULT NULL, annee VARCHAR(255) DEFAULT NULL, fonction VARCHAR(255) DEFAULT NULL, carte VARCHAR(255) DEFAULT NULL, montant INT DEFAULT NULL, montantSansFrais INT DEFAULT NULL, ristourne INT DEFAULT NULL, createdAt DATETIME DEFAULT NULL, telephone VARCHAR(255) DEFAULT NULL, INDEX IDX_E139D13D6A99F74A (membre_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE Cotisation ADD CONSTRAINT FK_E139D13D6A99F74A FOREIGN KEY (membre_id) REFERENCES Membre (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE Cotisation');
    }
}
