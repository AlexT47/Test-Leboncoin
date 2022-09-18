<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220916151442 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs

        // Ajout des data pour la table Marque
        $this->addSql("INSERT INTO marque (nom) VALUES ('Audi')");
        $this->addSql("INSERT INTO marque (nom) VALUES ('BMW')");
        $this->addSql("INSERT INTO marque (nom) VALUES ('Citroen')");

        // Ajout des data pour la table Modele
        $modelesAudi = explode(', ', 'Cabriolet, Q2, Q3, Q5, Q7, Q8, R8, Rs3, Rs4, Rs5, Rs7, S3, S4, S4 Avant, S4 Cabriolet, S5, S7, S8, SQ5, SQ7, Tt, Tts, V8');
        foreach ($modelesAudi as $modeleAudi) {
            $this->addSql("INSERT INTO modele (nom, marque_id) VALUES ('" . $modeleAudi . "', (select id from marque where nom='Audi'))");
        }

        $modelesAudi = explode(', ', 'M3, M4, M5, M535, M6, M635, Serie 1, Serie 2, Serie 3, Serie 4, Serie 5, Serie 6, Serie 7, Serie 8');
        foreach ($modelesAudi as $modeleAudi) {
            $this->addSql("INSERT INTO modele (nom, marque_id) VALUES ('" . $modeleAudi . "', (select id from marque where nom='BMW'))");
        }

        $modelesAudi = explode(', ', 'C1, C15, C2, C25, C25D, C25E, C25TD, C3, C3 Aircross, C3 Picasso, C4, C4 Picasso, C5, C6, C8, Ds3, Ds4, Ds5');
        foreach ($modelesAudi as $modeleAudi) {
            $this->addSql("INSERT INTO modele (nom, marque_id) VALUES ('" . $modeleAudi . "', (select id from marque where nom='Citroen'))");
        }
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
