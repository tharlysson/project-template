#!/bin/bash

domain_lower=$(echo "$domain" | tr '[:upper:]' '[:lower:]')
entity_lower=$(echo "$entity" | tr '[:upper:]' '[:lower:]')

echo "Implementando rotas"
echo "<?php

use $sistema\\$domain\Presentation\Controller\\${entity}Controller;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;


return function (App \$app) {
  \$app->group('/api/$domain_lower', function (RouteCollectorProxy \$app) {
      \$app->group('/$entity_lower', function (RouteCollectorProxy \$app) {
          \$app->get('', [${entity}Controller::class, 'list']);
          \$app->get('/{id}', [${entity}Controller::class, 'show']);
          \$app->post('', [${entity}Controller::class, 'store']);
          \$app->put('/{id}', [${entity}Controller::class, 'update']);
          \$app->delete('/{id}', [${entity}Controller::class, 'destroy']);
      });
  });
};" > "../app/routes/$entity.php"