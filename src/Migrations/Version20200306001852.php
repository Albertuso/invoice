<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200306001852 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE budget (id INT AUTO_INCREMENT NOT NULL, enterprise_id INT NOT NULL, client_id INT NOT NULL, status INT NOT NULL, psswrd VARCHAR(255) NOT NULL, contract LONGTEXT NOT NULL, INDEX IDX_73F2F77BA97D1AC3 (enterprise_id), INDEX IDX_73F2F77B19EB6921 (client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE budget ADD CONSTRAINT FK_73F2F77BA97D1AC3 FOREIGN KEY (enterprise_id) REFERENCES enterprise (id)');
        $this->addSql('ALTER TABLE budget ADD CONSTRAINT FK_73F2F77B19EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE product_line ADD budget_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE product_line ADD CONSTRAINT FK_5CFC965736ABA6B8 FOREIGN KEY (budget_id) REFERENCES budget (id)');
        $this->addSql('CREATE INDEX IDX_5CFC965736ABA6B8 ON product_line (budget_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE product_line DROP FOREIGN KEY FK_5CFC965736ABA6B8');
        $this->addSql('DROP TABLE budget');
        $this->addSql('DROP INDEX IDX_5CFC965736ABA6B8 ON product_line');
        $this->addSql('ALTER TABLE product_line DROP budget_id');
    }
}
