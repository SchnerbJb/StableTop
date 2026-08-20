<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260820120046 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE editeur (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE jds_mecanique (jds_id INT NOT NULL, mecanique_id INT NOT NULL, INDEX IDX_4E22E44994954F42 (jds_id), INDEX IDX_4E22E449FAE435EB (mecanique_id), PRIMARY KEY (jds_id, mecanique_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE mecanique (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE jds_mecanique ADD CONSTRAINT FK_4E22E44994954F42 FOREIGN KEY (jds_id) REFERENCES jds (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE jds_mecanique ADD CONSTRAINT FK_4E22E449FAE435EB FOREIGN KEY (mecanique_id) REFERENCES mecanique (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE jds ADD age_min INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE jds_mecanique DROP FOREIGN KEY FK_4E22E44994954F42');
        $this->addSql('ALTER TABLE jds_mecanique DROP FOREIGN KEY FK_4E22E449FAE435EB');
        $this->addSql('DROP TABLE editeur');
        $this->addSql('DROP TABLE jds_mecanique');
        $this->addSql('DROP TABLE mecanique');
        $this->addSql('ALTER TABLE jds DROP age_min');
    }
}
