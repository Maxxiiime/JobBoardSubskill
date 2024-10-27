<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230828205330 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE annonce (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', entreprise_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', ville_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', metier_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', contrat_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', image_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', title VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, reference VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_F65593E5AEA34913 (reference), INDEX IDX_F65593E5A4AEAFEA (entreprise_id), INDEX IDX_F65593E5A73F0036 (ville_id), INDEX IDX_F65593E5ED16FA20 (metier_id), INDEX IDX_F65593E51823061F (contrat_id), INDEX IDX_F65593E53DA5256D (image_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contrat (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', type_contrat VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE entreprise (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE image (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', url VARCHAR(255) NOT NULL, fact VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE metier (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', job_name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ville (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE annonce ADD CONSTRAINT FK_F65593E5A4AEAFEA FOREIGN KEY (entreprise_id) REFERENCES entreprise (id)');
        $this->addSql('ALTER TABLE annonce ADD CONSTRAINT FK_F65593E5A73F0036 FOREIGN KEY (ville_id) REFERENCES ville (id)');
        $this->addSql('ALTER TABLE annonce ADD CONSTRAINT FK_F65593E5ED16FA20 FOREIGN KEY (metier_id) REFERENCES metier (id)');
        $this->addSql('ALTER TABLE annonce ADD CONSTRAINT FK_F65593E51823061F FOREIGN KEY (contrat_id) REFERENCES contrat (id)');
        $this->addSql('ALTER TABLE annonce ADD CONSTRAINT FK_F65593E53DA5256D FOREIGN KEY (image_id) REFERENCES image (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE annonce DROP FOREIGN KEY FK_F65593E5A4AEAFEA');
        $this->addSql('ALTER TABLE annonce DROP FOREIGN KEY FK_F65593E5A73F0036');
        $this->addSql('ALTER TABLE annonce DROP FOREIGN KEY FK_F65593E5ED16FA20');
        $this->addSql('ALTER TABLE annonce DROP FOREIGN KEY FK_F65593E51823061F');
        $this->addSql('ALTER TABLE annonce DROP FOREIGN KEY FK_F65593E53DA5256D');
        $this->addSql('DROP TABLE annonce');
        $this->addSql('DROP TABLE contrat');
        $this->addSql('DROP TABLE entreprise');
        $this->addSql('DROP TABLE image');
        $this->addSql('DROP TABLE metier');
        $this->addSql('DROP TABLE ville');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
