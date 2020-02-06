<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200206202443 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, enterprise_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, price DOUBLE PRECISION NOT NULL, vat DOUBLE PRECISION NOT NULL, description VARCHAR(255) DEFAULT NULL, INDEX IDX_D34A04ADA97D1AC3 (enterprise_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE enterprise (id INT AUTO_INCREMENT NOT NULL, customer_id INT NOT NULL, user_id INT NOT NULL, name VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, web VARCHAR(255) NOT NULL, nif VARCHAR(255) NOT NULL, logo VARCHAR(255) NOT NULL, footer VARCHAR(255) NOT NULL, INDEX IDX_B1B36A039395C3F3 (customer_id), INDEX IDX_B1B36A03A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE social_network (id INT AUTO_INCREMENT NOT NULL, network_id INT NOT NULL, url VARCHAR(255) NOT NULL, INDEX IDX_EFFF522134128B91 (network_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, max INT NOT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE supervisor (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, telephone VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_line (id INT AUTO_INCREMENT NOT NULL, invoice_id INT NOT NULL, description VARCHAR(255) DEFAULT NULL, quantity INT NOT NULL, price DOUBLE PRECISION NOT NULL, vat DOUBLE PRECISION NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_5CFC96572989F1FD (invoice_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE invoice (id INT AUTO_INCREMENT NOT NULL, invoicenumber INT NOT NULL, date DATE NOT NULL, description VARCHAR(255) NOT NULL, footer VARCHAR(255) NOT NULL, subtotal DOUBLE PRECISION NOT NULL, total DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE network (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE client (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, nif VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, telephone VARCHAR(255) NOT NULL, web VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADA97D1AC3 FOREIGN KEY (enterprise_id) REFERENCES enterprise (id)');
        $this->addSql('ALTER TABLE enterprise ADD CONSTRAINT FK_B1B36A039395C3F3 FOREIGN KEY (customer_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE enterprise ADD CONSTRAINT FK_B1B36A03A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE social_network ADD CONSTRAINT FK_EFFF522134128B91 FOREIGN KEY (network_id) REFERENCES network (id)');
        $this->addSql('ALTER TABLE product_line ADD CONSTRAINT FK_5CFC96572989F1FD FOREIGN KEY (invoice_id) REFERENCES invoice (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04ADA97D1AC3');
        $this->addSql('ALTER TABLE enterprise DROP FOREIGN KEY FK_B1B36A03A76ED395');
        $this->addSql('ALTER TABLE product_line DROP FOREIGN KEY FK_5CFC96572989F1FD');
        $this->addSql('ALTER TABLE social_network DROP FOREIGN KEY FK_EFFF522134128B91');
        $this->addSql('ALTER TABLE enterprise DROP FOREIGN KEY FK_B1B36A039395C3F3');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE enterprise');
        $this->addSql('DROP TABLE social_network');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE supervisor');
        $this->addSql('DROP TABLE product_line');
        $this->addSql('DROP TABLE invoice');
        $this->addSql('DROP TABLE network');
        $this->addSql('DROP TABLE client');
    }
}
