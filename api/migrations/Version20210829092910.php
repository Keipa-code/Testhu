<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210829092910 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE tag_question (tag_id INT NOT NULL, question_id INT NOT NULL, PRIMARY KEY(tag_id, question_id))');
        $this->addSql('CREATE INDEX IDX_80C63295BAD26311 ON tag_question (tag_id)');
        $this->addSql('CREATE INDEX IDX_80C632951E27F6BF ON tag_question (question_id)');
        $this->addSql('ALTER TABLE tag_question ADD CONSTRAINT FK_80C63295BAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE tag_question ADD CONSTRAINT FK_80C632951E27F6BF FOREIGN KEY (question_id) REFERENCES question (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE tag DROP CONSTRAINT fk_389b7831e27f6bf');
        $this->addSql('DROP INDEX idx_389b7831e27f6bf');
        $this->addSql('ALTER TABLE tag DROP question_id');
        $this->addSql('ALTER TABLE tag ALTER tag_name TYPE VARCHAR(30)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE tag_question');
        $this->addSql('ALTER TABLE tag ADD question_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE tag ALTER tag_name TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE tag ADD CONSTRAINT fk_389b7831e27f6bf FOREIGN KEY (question_id) REFERENCES question (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_389b7831e27f6bf ON tag (question_id)');
    }
}
