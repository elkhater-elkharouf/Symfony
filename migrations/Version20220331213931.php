<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220331213931 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE calendar (id INT AUTO_INCREMENT NOT NULL, reservations_id INT NOT NULL, start DATE NOT NULL, end DATE NOT NULL, title VARCHAR(255) NOT NULL, INDEX IDX_6EA9A146D9A7F869 (reservations_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(1000) NOT NULL, nom VARCHAR(1000) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, publication_id INT NOT NULL, iduser_id INT DEFAULT NULL, content VARCHAR(255) NOT NULL, author_name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_9474526C38B217A7 (publication_id), INDEX IDX_9474526C786A81FB (iduser_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE evenement (id INT AUTO_INCREMENT NOT NULL, destination VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, prix INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE events (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(100) NOT NULL, idevent INT NOT NULL, place VARCHAR(100) NOT NULL, date DATE NOT NULL, price DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hotel (id INT AUTO_INCREMENT NOT NULL, adresse VARCHAR(255) NOT NULL, superficie DOUBLE PRECISION NOT NULL, name VARCHAR(255) NOT NULL, nombrechambre INT NOT NULL, etage INT NOT NULL, prix DOUBLE PRECISION NOT NULL, img VARCHAR(255) NOT NULL, promotion INT NOT NULL, etoile INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE image (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE notifications (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, link VARCHAR(255) NOT NULL, date VARCHAR(255) NOT NULL, to_show INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE participation (id INT AUTO_INCREMENT NOT NULL, eventid_id INT NOT NULL, nom VARCHAR(100) NOT NULL, prenom VARCHAR(100) NOT NULL, datanes DATE NOT NULL, numtel INT NOT NULL, adresse VARCHAR(100) NOT NULL, INDEX IDX_AB55E24F3DAAA2E7 (eventid_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE produit (id INT AUTO_INCREMENT NOT NULL, category_id INT DEFAULT NULL, image_id INT DEFAULT NULL, nom VARCHAR(50) NOT NULL, couleur VARCHAR(50) NOT NULL, prix DOUBLE PRECISION NOT NULL, quantite INT NOT NULL, INDEX IDX_29A5EC2712469DE2 (category_id), UNIQUE INDEX UNIQ_29A5EC273DA5256D (image_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE publication (id INT AUTO_INCREMENT NOT NULL, iduser_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, content VARCHAR(255) NOT NULL, abn INT DEFAULT NULL, INDEX IDX_AF3C6779786A81FB (iduser_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reservation (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, hotel_id INT NOT NULL, date_debut DATE NOT NULL, date_fin DATE NOT NULL, prixreservation DOUBLE PRECISION NOT NULL, nbrperson INT NOT NULL, INDEX IDX_42C84955A76ED395 (user_id), INDEX IDX_42C849553243BB18 (hotel_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reservationv (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, voiture_id INT NOT NULL, datedebut DATE NOT NULL, datefin DATE NOT NULL, prixreservation DOUBLE PRECISION NOT NULL, INDEX IDX_702756B6A76ED395 (user_id), INDEX IDX_702756B6181A8BA (voiture_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, mail VARCHAR(255) NOT NULL, telephone INT DEFAULT NULL, cin INT DEFAULT NULL, date_naissance DATE DEFAULT NULL, mdp VARCHAR(255) NOT NULL, role VARCHAR(255) DEFAULT NULL, enable VARCHAR(11) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE voiture (id INT AUTO_INCREMENT NOT NULL, marque VARCHAR(255) NOT NULL, couleur VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, prix DOUBLE PRECISION NOT NULL, modele VARCHAR(255) NOT NULL, killometrage INT NOT NULL, carburant VARCHAR(255) NOT NULL, transmission VARCHAR(255) NOT NULL, bagage INT NOT NULL, place INT NOT NULL, matricule INT NOT NULL, UNIQUE INDEX UNIQ_E9E2810F12B2DC9C (matricule), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE calendar ADD CONSTRAINT FK_6EA9A146D9A7F869 FOREIGN KEY (reservations_id) REFERENCES reservation (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C38B217A7 FOREIGN KEY (publication_id) REFERENCES publication (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C786A81FB FOREIGN KEY (iduser_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE participation ADD CONSTRAINT FK_AB55E24F3DAAA2E7 FOREIGN KEY (eventid_id) REFERENCES events (id)');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC2712469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC273DA5256D FOREIGN KEY (image_id) REFERENCES image (id)');
        $this->addSql('ALTER TABLE publication ADD CONSTRAINT FK_AF3C6779786A81FB FOREIGN KEY (iduser_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C849553243BB18 FOREIGN KEY (hotel_id) REFERENCES hotel (id)');
        $this->addSql('ALTER TABLE reservationv ADD CONSTRAINT FK_702756B6A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE reservationv ADD CONSTRAINT FK_702756B6181A8BA FOREIGN KEY (voiture_id) REFERENCES voiture (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC2712469DE2');
        $this->addSql('ALTER TABLE participation DROP FOREIGN KEY FK_AB55E24F3DAAA2E7');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C849553243BB18');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC273DA5256D');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C38B217A7');
        $this->addSql('ALTER TABLE calendar DROP FOREIGN KEY FK_6EA9A146D9A7F869');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C786A81FB');
        $this->addSql('ALTER TABLE publication DROP FOREIGN KEY FK_AF3C6779786A81FB');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955A76ED395');
        $this->addSql('ALTER TABLE reservationv DROP FOREIGN KEY FK_702756B6A76ED395');
        $this->addSql('ALTER TABLE reservationv DROP FOREIGN KEY FK_702756B6181A8BA');
        $this->addSql('DROP TABLE calendar');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE evenement');
        $this->addSql('DROP TABLE events');
        $this->addSql('DROP TABLE hotel');
        $this->addSql('DROP TABLE image');
        $this->addSql('DROP TABLE notifications');
        $this->addSql('DROP TABLE participation');
        $this->addSql('DROP TABLE produit');
        $this->addSql('DROP TABLE publication');
        $this->addSql('DROP TABLE reservation');
        $this->addSql('DROP TABLE reservationv');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE voiture');
    }
}
