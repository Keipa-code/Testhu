<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210904114337 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE tag_test (tag_id INT NOT NULL, test_id INT NOT NULL, PRIMARY KEY(tag_id, test_id))');
        $this->addSql('CREATE INDEX IDX_3670C1BABAD26311 ON tag_test (tag_id)');
        $this->addSql('CREATE INDEX IDX_3670C1BA1E5D0459 ON tag_test (test_id)');
        $this->addSql('ALTER TABLE tag_test ADD CONSTRAINT FK_3670C1BABAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE tag_test ADD CONSTRAINT FK_3670C1BA1E5D0459 FOREIGN KEY (test_id) REFERENCES test (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP TABLE tag_question');
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
        $this->addSql('DROP TABLE tag_test');
    }
}
