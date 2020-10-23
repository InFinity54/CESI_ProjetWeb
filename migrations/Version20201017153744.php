<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201017153744 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE historique (id INT AUTO_INCREMENT NOT NULL, agent_id INT NOT NULL, vehicle_id VARCHAR(9) NOT NULL, dateheure_modif DATETIME NOT NULL, nature_modif VARCHAR(1000) NOT NULL, description_modif VARCHAR(1000) DEFAULT NULL, ancienne_valeur VARCHAR(255) DEFAULT NULL, nouvelle_valeur VARCHAR(255) DEFAULT NULL, INDEX IDX_EDBFD5EC3414710B (agent_id), INDEX IDX_EDBFD5EC545317D1 (vehicle_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE historique ADD CONSTRAINT FK_EDBFD5EC3414710B FOREIGN KEY (agent_id) REFERENCES agent (id)');
        $this->addSql('ALTER TABLE historique ADD CONSTRAINT FK_EDBFD5EC545317D1 FOREIGN KEY (vehicle_id) REFERENCES vehicle (numberplate)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE historique');
    }
}
