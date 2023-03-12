<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230312195941 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("INSERT INTO album (title, created_at) VALUES ('All eyez on me', NOW())");
        $this->addSql("INSERT INTO album (title, created_at) VALUES ('The Valley Of Vision', NOW())");
        $this->addSql("INSERT INTO album (title, created_at) VALUES ('Starting Over', NOW())");
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
