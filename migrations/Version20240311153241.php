<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240311153241 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE post_utilisateur (post_id INT NOT NULL, utilisateur_id INT NOT NULL, INDEX IDX_F15CE99C4B89032C (post_id), INDEX IDX_F15CE99CFB88E14F (utilisateur_id), PRIMARY KEY(post_id, utilisateur_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE post_utilisateur ADD CONSTRAINT FK_F15CE99C4B89032C FOREIGN KEY (post_id) REFERENCES post (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE post_utilisateur ADD CONSTRAINT FK_F15CE99CFB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE post ADD createur_id INT NOT NULL');
        $this->addSql('DELETE FROM post');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8D73A201E5 FOREIGN KEY (createur_id) REFERENCES utilisateur (id)');
        $this->addSql('CREATE INDEX IDX_5A8A6C8D73A201E5 ON post (createur_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE post_utilisateur DROP FOREIGN KEY FK_F15CE99C4B89032C');
        $this->addSql('ALTER TABLE post_utilisateur DROP FOREIGN KEY FK_F15CE99CFB88E14F');
        $this->addSql('DROP TABLE post_utilisateur');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8D73A201E5');
        $this->addSql('DROP INDEX IDX_5A8A6C8D73A201E5 ON post');
        $this->addSql('ALTER TABLE post DROP createur_id');
    }
}
