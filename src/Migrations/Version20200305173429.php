<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200305173429 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE contract (id INT AUTO_INCREMENT NOT NULL, enterprise_id INT NOT NULL, client_id INT NOT NULL, status INT NOT NULL, safe_code VARCHAR(255) NOT NULL, INDEX IDX_E98F2859A97D1AC3 (enterprise_id), INDEX IDX_E98F285919EB6921 (client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE contract ADD CONSTRAINT FK_E98F2859A97D1AC3 FOREIGN KEY (enterprise_id) REFERENCES enterprise (id)');
        $this->addSql('ALTER TABLE contract ADD CONSTRAINT FK_E98F285919EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE product_line ADD contract_id INT NOT NULL');
        $this->addSql('ALTER TABLE product_line ADD CONSTRAINT FK_5CFC96572576E0FD FOREIGN KEY (contract_id) REFERENCES contract (id)');
        $this->addSql('CREATE INDEX IDX_5CFC96572576E0FD ON product_line (contract_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE product_line DROP FOREIGN KEY FK_5CFC96572576E0FD');
        $this->addSql('DROP TABLE contract');
        $this->addSql('DROP INDEX IDX_5CFC96572576E0FD ON product_line');
        $this->addSql('ALTER TABLE product_line DROP contract_id');
    }
}
