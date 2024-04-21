#!/bin/bash

sistema="POS"

echo -e  "🚀 Criando estrutura básica de um crud...\n"

read -p "Digite o nome do domínio: " domain
read -p "Digite o nome da entidade: " entity
read -p "Digite o nome da tabela: " table_name
read -p "Digite o alias da tabela: " table_alias
read -p "Digite o nome da chave primária: " primary_key


# Exibe os dados inseridos pelo usuário
echo -e "\n📝 Aqui estão os dados que você inseriu:\n"
echo "Nome do domínio: $domain"
echo "Nome da entidade: $entity"
echo "Nome da tabela: $table_name"
echo "Alias da tabela: $table_alias"
echo "Nome da chave primária: $primary_key"

echo -e "\n🔍 Verifique se os dados estão corretos.\n"

read -p "Você confirma esses dados e deseja continuar? (s/n) " confirm

if [ "$confirm" != "s" ]; then
    echo -e "\nOperação cancelada pelo usuário."
    exit 1
fi

timestamp=$(date +%Y%m%d%H%M%S)
migration_name="Version$timestamp"

base_dir="../src"
entity_lower=$(echo "$entity" | awk '{print tolower(substr($0,1,1)) substr($0,2)}')

source ./crud/create_directories.sh
source ./crud/create_files.sh
source ./crud/implement_classes.sh

echo -e "\n🚀 Crud criado com sucesso!\n"