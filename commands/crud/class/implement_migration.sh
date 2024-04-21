#!/bin/bash

echo "Implementando migration"
echo "<?php

declare(strict_types=1);

namespace POS\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class $migration_name extends AbstractMigration
{
    public function up(Schema \$schema): void
    {

        \$this->addSql(
        \"CREATE TABLE $table_name (
            id CHAR(36) NOT NULL,
            name varchar(255) NOT NULL,
            project_id CHAR(36) NOT NULL,
            active TINYINT(1) NOT NULL DEFAULT 1,
            created_at timestamp NOT NULL DEFAULT current_timestamp(),
            updated_at timestamp NOT NULL DEFAULT current_timestamp(),
            deleted_at timestamp NULL DEFAULT NULL,
            PRIMARY KEY (id)
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;\"
        );
    }

    public function down(Schema \$schema): void
    {
        \$this->addSql('DROP TABLE $table_name;');
    }
}" > "../resources/database/migrations/$migration_name.php"