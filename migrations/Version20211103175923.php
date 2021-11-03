<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211103175923 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE Compte ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE Compte ADD CONSTRAINT FK_C85A5756A76ED395 FOREIGN KEY (user_id) REFERENCES User (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C85A5756A76ED395 ON Compte (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE Compte DROP FOREIGN KEY FK_C85A5756A76ED395');
        $this->addSql('DROP INDEX UNIQ_C85A5756A76ED395 ON Compte');
        $this->addSql('ALTER TABLE Compte DROP user_id');
    }
}
