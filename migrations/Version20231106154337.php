<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231106154337 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE carte (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(50) NOT NULL, point INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE joueur (id INT AUTO_INCREMENT NOT NULL, rang_id INT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, pseudonyme VARCHAR(50) NOT NULL, nbr_wins INT NOT NULL, total_parties INT NOT NULL, point_elo INT NOT NULL, date_inscription DATE NOT NULL, UNIQUE INDEX UNIQ_FD71A9C5E7927C74 (email), INDEX IDX_FD71A9C53CC0D837 (rang_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE partie (id INT AUTO_INCREMENT NOT NULL, date_debut DATE NOT NULL, date_fin DATE NOT NULL, etat VARCHAR(50) NOT NULL, est_gagne TINYINT(1) NOT NULL, point_joueur INT NOT NULL, point_banquier INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rang (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(50) NOT NULL, score_min INT NOT NULL, score_max INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE joueur ADD CONSTRAINT FK_FD71A9C53CC0D837 FOREIGN KEY (rang_id) REFERENCES rang (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE joueur DROP FOREIGN KEY FK_FD71A9C53CC0D837');
        $this->addSql('DROP TABLE carte');
        $this->addSql('DROP TABLE joueur');
        $this->addSql('DROP TABLE partie');
        $this->addSql('DROP TABLE rang');
    }
}
