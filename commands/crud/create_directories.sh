#!/bin/bash

echo -e "\n📁 Criando diretórios...\n"

folders=("Application/DTO" "Application/UseCase" "Domain/Entity" "Domain/Repository" "Infra/Repository/Database" "Presentation/Controller" "Presentation/DTO")

for folder in "${folders[@]}"; do
  mkdir -p "$base_dir/$domain/$folder"
  echo "Diretório $base_dir/$domain/$folder criado com sucesso!"
done
