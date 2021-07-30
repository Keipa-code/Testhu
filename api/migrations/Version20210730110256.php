<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210730110256 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE tag_question');
        $this->addSql('ALTER TABLE question ADD tag_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494EBAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_B6F7494EBAD26311 ON question (tag_id)');
        $this->addSql('ALTER TABLE "user" ALTER username TYPE VARCHAR(30)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE TABLE tag_question (tag_id INT NOT NULL, question_id INT NOT NULL, PRIMARY KEY(tag_id, question_id))');
        $this->addSql('CREATE INDEX idx_80c63295bad26311 ON tag_question (tag_id)');
        $this->addSql('CREATE INDEX idx_80c632951e27f6bf ON tag_question (question_id)');
        $this->addSql('ALTER TABLE tag_question ADD CONSTRAINT fk_80c63295bad26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE tag_question ADD CONSTRAINT fk_80c632951e27f6bf FOREIGN KEY (question_id) REFERENCES question (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "user" ALTER username TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE question DROP CONSTRAINT FK_B6F7494EBAD26311');
        $this->addSql('DROP INDEX IDX_B6F7494EBAD26311');
        $this->addSql('ALTER TABLE question DROP tag_id');
    }
}
