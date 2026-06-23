<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260623095043 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE game_save (id INT AUTO_INCREMENT NOT NULL, slot INT NOT NULL, map_id VARCHAR(100) NOT NULL, pos_x INT NOT NULL, pos_y INT NOT NULL, play_time INT NOT NULL, gold INT NOT NULL, chapter INT NOT NULL, flags JSON NOT NULL, party_ids JSON NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, user_id INT NOT NULL, INDEX IDX_BE7356F2A76ED395 (user_id), UNIQUE INDEX unique_user_slot (user_id, slot), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE user_options (id INT AUTO_INCREMENT NOT NULL, volume_master INT NOT NULL, volume_music INT NOT NULL, volume_sfx INT NOT NULL, fullscreen TINYINT NOT NULL, show_fps TINYINT NOT NULL, text_speed VARCHAR(10) NOT NULL, keyboard_layout VARCHAR(10) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, user_id INT NOT NULL, UNIQUE INDEX UNIQ_8838E48DA76ED395 (user_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE game_save ADD CONSTRAINT FK_BE7356F2A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_options ADD CONSTRAINT FK_8838E48DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE game_save DROP FOREIGN KEY FK_BE7356F2A76ED395');
        $this->addSql('ALTER TABLE user_options DROP FOREIGN KEY FK_8838E48DA76ED395');
        $this->addSql('DROP TABLE game_save');
        $this->addSql('DROP TABLE user_options');
    }
}
