<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220103201126 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE developers (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE games (id INT AUTO_INCREMENT NOT NULL, developer_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, image LONGTEXT NOT NULL, INDEX IDX_FF232B3164DD9267 (developer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE games_genres (games_id INT NOT NULL, genres_id INT NOT NULL, INDEX IDX_6AC30AA397FFC673 (games_id), INDEX IDX_6AC30AA36A3B2603 (genres_id), PRIMARY KEY(games_id, genres_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE games_plattforms (games_id INT NOT NULL, plattforms_id INT NOT NULL, INDEX IDX_A0794C7D97FFC673 (games_id), INDEX IDX_A0794C7DF7D77C1A (plattforms_id), PRIMARY KEY(games_id, plattforms_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE genres (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE plattforms (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE games ADD CONSTRAINT FK_FF232B3164DD9267 FOREIGN KEY (developer_id) REFERENCES developers (id)');
        $this->addSql('ALTER TABLE games_genres ADD CONSTRAINT FK_6AC30AA397FFC673 FOREIGN KEY (games_id) REFERENCES games (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE games_genres ADD CONSTRAINT FK_6AC30AA36A3B2603 FOREIGN KEY (genres_id) REFERENCES genres (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE games_plattforms ADD CONSTRAINT FK_A0794C7D97FFC673 FOREIGN KEY (games_id) REFERENCES games (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE games_plattforms ADD CONSTRAINT FK_A0794C7DF7D77C1A FOREIGN KEY (plattforms_id) REFERENCES plattforms (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE games DROP FOREIGN KEY FK_FF232B3164DD9267');
        $this->addSql('ALTER TABLE games_genres DROP FOREIGN KEY FK_6AC30AA397FFC673');
        $this->addSql('ALTER TABLE games_plattforms DROP FOREIGN KEY FK_A0794C7D97FFC673');
        $this->addSql('ALTER TABLE games_genres DROP FOREIGN KEY FK_6AC30AA36A3B2603');
        $this->addSql('ALTER TABLE games_plattforms DROP FOREIGN KEY FK_A0794C7DF7D77C1A');
        $this->addSql('DROP TABLE developers');
        $this->addSql('DROP TABLE games');
        $this->addSql('DROP TABLE games_genres');
        $this->addSql('DROP TABLE games_plattforms');
        $this->addSql('DROP TABLE genres');
        $this->addSql('DROP TABLE plattforms');
    }
}
