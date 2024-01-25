<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240125194331 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create product table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE product (product_id UUID NOT NULL, name VARCHAR(255) NOT NULL COLLATE "de-DE-x-icu", created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(product_id))');
        $this->addSql('COMMENT ON COLUMN product.product_id IS \'(DC2Type:product_id)\'');
        $this->addSql('COMMENT ON COLUMN product.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN product.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE cache_items (item_id VARCHAR(255) NOT NULL, item_data BYTEA NOT NULL, item_lifetime INT DEFAULT NULL, item_time INT NOT NULL, PRIMARY KEY(item_id))');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE cache_items');
    }
}
