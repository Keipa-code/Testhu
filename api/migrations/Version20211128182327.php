<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211128182327 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE result ADD is_wrong_answers_visibles BOOLEAN NOT NULL');
        $this->addSql('ALTER TABLE result ADD is_public BOOLEAN NOT NULL');
        $this->addSql('ALTER TABLE result ADD test_results JSON DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE result DROP is_wrong_answers_visibles');
        $this->addSql('ALTER TABLE result DROP is_public');
        $this->addSql('ALTER TABLE result DROP test_results');
    }
}
