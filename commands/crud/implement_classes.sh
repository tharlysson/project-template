#!/bin/bash

echo -e "\n📝 Implementando classes...\n"

# Chame os scripts de implementação de classes
source ./crud/class/implement_migration.sh
source ./crud/class/implement_routes.sh
source ./crud/class/implement_Listing.sh
source ./crud/class/implement_UseCase.sh
source ./crud/class/implement_Entity.sh
source ./crud/class/implement_Repository.sh
source ./crud/class/implement_Database.sh
source ./crud/class/implement_Controller.sh
source ./crud/class/implement_UpdateDTO.sh
source ./crud/class/implement_CreateDTO.sh

echo -e "\n✅ Classes implementadas com sucesso!\n"