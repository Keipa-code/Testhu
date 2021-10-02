<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211002085016 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE question DROP CONSTRAINT fk_b6f7494e1e5d0459');
        $this->addSql('DROP INDEX idx_b6f7494e1e5d0459');
        $this->addSql('ALTER TABLE question RENAME COLUMN test_id TO test');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494ED87F7E0C FOREIGN KEY (test) REFERENCES test (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_B6F7494ED87F7E0C ON question (test)');
        $this->addSql('ALTER TABLE "user" ALTER password_reset_token TYPE VARCHAR(250)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D6491F043FA9 ON "user" (new_email)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP INDEX UNIQ_8D93D6491F043FA9');
        $this->addSql('ALTER TABLE "user" ALTER password_reset_token TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE question DROP CONSTRAINT FK_B6F7494ED87F7E0C');
        $this->addSql('DROP INDEX IDX_B6F7494ED87F7E0C');
        $this->addSql('ALTER TABLE question RENAME COLUMN test TO test_id');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT fk_b6f7494e1e5d0459 FOREIGN KEY (test_id) REFERENCES test (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_b6f7494e1e5d0459 ON question (test_id)');
    }
}
