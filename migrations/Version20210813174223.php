<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210813174223 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE video_tag DROP FOREIGN KEY FK_F9107287BAD26311');
        $this->addSql('CREATE TABLE video_db_tag (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, bgcolor VARCHAR(9) DEFAULT NULL, color VARCHAR(9) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE video_db_user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, pseudo VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_54D0A13BE7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE video_db_video_tag (video_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_AECC14C229C1004E (video_id), INDEX IDX_AECC14C2BAD26311 (tag_id), PRIMARY KEY(video_id, tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE video_db_video_tag ADD CONSTRAINT FK_AECC14C229C1004E FOREIGN KEY (video_id) REFERENCES video_db_video (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE video_db_video_tag ADD CONSTRAINT FK_AECC14C2BAD26311 FOREIGN KEY (tag_id) REFERENCES video_db_tag (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE tag');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE video_tag');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE video_db_video_tag DROP FOREIGN KEY FK_AECC14C2BAD26311');
        $this->addSql('CREATE TABLE tag (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, bgcolor VARCHAR(9) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, color VARCHAR(9) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, roles JSON NOT NULL, password VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, pseudo VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE video_tag (video_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_F910728729C1004E (video_id), INDEX IDX_F9107287BAD26311 (tag_id), PRIMARY KEY(video_id, tag_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE video_tag ADD CONSTRAINT FK_F910728729C1004E FOREIGN KEY (video_id) REFERENCES video_db_video (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE video_tag ADD CONSTRAINT FK_F9107287BAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('DROP TABLE video_db_tag');
        $this->addSql('DROP TABLE video_db_user');
        $this->addSql('DROP TABLE video_db_video_tag');
    }
}
