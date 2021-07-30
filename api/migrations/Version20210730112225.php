<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210730112225 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE question DROP CONSTRAINT fk_b6f7494e1e5d0459');
        $this->addSql('ALTER TABLE question DROP CONSTRAINT fk_b6f7494ebad26311');
        $this->addSql('DROP INDEX idx_b6f7494e1e5d0459');
        $this->addSql('DROP INDEX idx_b6f7494ebad26311');
        $this->addSql('ALTER TABLE question DROP test_id');
        $this->addSql('ALTER TABLE question DROP tag_id');
        $this->addSql('ALTER TABLE tag ADD question_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE tag ADD CONSTRAINT FK_389B7831E27F6BF FOREIGN KEY (question_id) REFERENCES question (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_389B7831E27F6BF ON tag (question_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE tag DROP CONSTRAINT FK_389B7831E27F6BF');
        $this->addSql('DROP INDEX IDX_389B7831E27F6BF');
        $this->addSql('ALTER TABLE tag DROP question_id');
        $this->addSql('ALTER TABLE question ADD test_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE question ADD tag_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT fk_b6f7494e1e5d0459 FOREIGN KEY (test_id) REFERENCES test (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT fk_b6f7494ebad26311 FOREIGN KEY (tag_id) REFERENCES tag (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_b6f7494e1e5d0459 ON question (test_id)');
        $this->addSql('CREATE INDEX idx_b6f7494ebad26311 ON question (tag_id)');
    }
}
