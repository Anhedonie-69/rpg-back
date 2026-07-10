<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260710085751 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE game_character (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, class VARCHAR(20) NOT NULL, slot INT NOT NULL, unlocked TINYINT NOT NULL, level INT NOT NULL, experience INT NOT NULL, hp_max INT NOT NULL, mp_max INT NOT NULL, atk INT NOT NULL, def INT NOT NULL, mag INT NOT NULL, res INT NOT NULL, spd INT NOT NULL, lck INT NOT NULL, hp_current INT NOT NULL, mp_current INT NOT NULL, status_effects JSON NOT NULL, skills JSON NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, user_id INT NOT NULL, INDEX IDX_41DC7136A76ED395 (user_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE game_character ADD CONSTRAINT FK_41DC7136A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE game_character DROP FOREIGN KEY FK_41DC7136A76ED395');
        $this->addSql('DROP TABLE game_character');
    }
}
