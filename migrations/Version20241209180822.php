<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241209180822 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user ADD added_by_trainer_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64954A5CBF4 FOREIGN KEY (added_by_trainer_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_8D93D64954A5CBF4 ON user (added_by_trainer_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64954A5CBF4');
        $this->addSql('DROP INDEX IDX_8D93D64954A5CBF4 ON user');
        $this->addSql('ALTER TABLE user DROP added_by_trainer_id');
    }
}
