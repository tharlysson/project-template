#!/bin/bash

echo -e "\n📄 Criando classes...\n"

echo "Criando arquivo da migration"
touch "../resources/database/migrations/$migration_name.php"

echo "Criando arquivo de rotas"
touch "../app/routes/$entity.php"

echo "Criando arquivo ${entity}Listing.php em $base_dir/$domain/Application/UseCase"
touch "$base_dir/$domain/Application/DTO/${entity}Listing.php"

echo "Criando arquivo ${entity}UseCase.php em $base_dir/$domain/Application/UseCase"
touch "$base_dir/$domain/Application/UseCase/${entity}UseCase.php"

echo "Criando arquivo $entity.php em $base_dir/$domain/Domain/Entity"
touch "$base_dir/$domain/Domain/Entity/$entity.php"

echo "Criando arquivo ${entity}Repository.php em $base_dir/$domain/Domain/Repository"
touch "$base_dir/$domain/Domain/Repository/${entity}Repository.php"

echo "Criando arquivo ${entity}Database.php em $base_dir/$domain/Infra/Repository/Database"
touch "$base_dir/$domain/Infra/Repository/Database/${entity}Doctrine.php"

echo "Criando arquivo ${entity}Controller.php em $base_dir/$domain/Presentation/Controller"
touch "$base_dir/$domain/Presentation/Controller/${entity}Controller.php"

echo "Criando arquivo Atualizar${entity}Request.php em $base_dir/$domain/Presentation/DTO"
touch "$base_dir/$domain/Presentation/DTO/${entity}UpdateRequest.php"

echo "Criando arquivo Criar${entity}Request.php em $base_dir/$domain/Presentation/DTO"
touch "$base_dir/$domain/Presentation/DTO/${entity}CreateRequest.php"

echo -e "\n✅ Arquivos criados com sucesso!\n"