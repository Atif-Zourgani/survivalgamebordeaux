<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240402191815 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE game_session (game_id INT NOT NULL, session_id INT NOT NULL, INDEX IDX_4586AAFBE48FD905 (game_id), INDEX IDX_4586AAFB613FECDF (session_id), PRIMARY KEY(game_id, session_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE game_session ADD CONSTRAINT FK_4586AAFBE48FD905 FOREIGN KEY (game_id) REFERENCES game (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE game_session ADD CONSTRAINT FK_4586AAFB613FECDF FOREIGN KEY (session_id) REFERENCES session (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE game_session DROP FOREIGN KEY FK_4586AAFBE48FD905');
        $this->addSql('ALTER TABLE game_session DROP FOREIGN KEY FK_4586AAFB613FECDF');
        $this->addSql('DROP TABLE game_session');
    }
}
