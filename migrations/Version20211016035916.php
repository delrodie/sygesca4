<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211016035916 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE Adherant (id INT AUTO_INCREMENT NOT NULL, matricule VARCHAR(255) DEFAULT NULL, carte VARCHAR(255) DEFAULT NULL, nom VARCHAR(255) DEFAULT NULL, prenoms VARCHAR(255) DEFAULT NULL, dateNaissance VARCHAR(255) DEFAULT NULL, lieuNaissance VARCHAR(255) DEFAULT NULL, sexe VARCHAR(255) DEFAULT NULL, contact VARCHAR(255) DEFAULT NULL, urgence VARCHAR(255) DEFAULT NULL, contactUrgence VARCHAR(255) DEFAULT NULL, branche VARCHAR(255) DEFAULT NULL, fonction VARCHAR(255) DEFAULT NULL, cotisation VARCHAR(255) DEFAULT NULL, slug VARCHAR(255) DEFAULT NULL, idTransaction VARCHAR(255) DEFAULT NULL, statusPaiement VARCHAR(255) DEFAULT NULL, createdAt DATETIME DEFAULT NULL, UpdatedAt DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE Adherant');
    }
}
