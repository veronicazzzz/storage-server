<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220107082211 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '1. Add file\'s table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE file (file_name VARCHAR(255) NOT NULL, file_size INT NOT NULL, PRIMARY KEY(file_name)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE file');
    }
}
