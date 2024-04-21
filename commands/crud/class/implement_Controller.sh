#!/bin/bash

echo "Implementando classe $base_dir/$domain/Presentation/Controller/${entity}Controller.php"
echo "<?php

declare(strict_types=1);

namespace $sistema\\${domain}\Presentation\Controller;

use Exception;
use $sistema\\${domain}\Application\UseCase\\${entity}UseCase;
use $sistema\\${domain}\Domain\Repository\\${entity}Repository;
use $sistema\\${domain}\Infra\Repository\Database\\${entity}Doctrine;
use $sistema\\${domain}\Presentation\DTO\\${entity}CreateRequest;
use $sistema\\${domain}\Presentation\DTO\\${entity}UpdateRequest;
use $sistema\Shared\DTO\Response;
use $sistema\Shared\DTO\ResponseListing;
use $sistema\Shared\DTO\ResponseStatus;
use $sistema\Shared\Helpers\Validator;
use $sistema\Shared\History\History;
use $sistema\Shared\ValueObjects\Uuid;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpBadRequestException;

final class ${entity}Controller
{
    private ${entity}UseCase \$useCase;

    public function __construct(
        private readonly ${entity}Repository \$repository = new ${entity}Doctrine()
    ) {
        \$this->useCase = new ${entity}UseCase(\$this->repository);
    }

    public function store(
        ServerRequestInterface \$request,
        ResponseInterface \$response,
        array \$args
    ): ResponseInterface {
        try {
            History::start();
            \$body = \$request->getParsedBody();

            \$rules = [
                'name' => 'required|string|max:255',
                'active' => 'boolean',
            ];

            Validator::init()->validate(\$body, \$rules);

            \$uuid = \$this->useCase->store(
                new ${entity}CreateRequest(
                    name: \$body['name'],
                    active: (bool)\$body['active']
                )
            );
            \$return = Response::create(
                ResponseStatus::SUCCESS,
                ['message' => '${entity} created', 'id' => \$uuid->value]
            );
            History::end();
            \$response->getBody()->write((string)json_encode(\$return));
        } catch (Exception \$e) {
            throw new HttpBadRequestException(\$request, \$e->getMessage());
        }

        return \$response->withStatus(201);
    }

    public function update(
        ServerRequestInterface \$request,
        ResponseInterface \$response,
        array \$args
    ): ResponseInterface {
        try {
            History::start();
            \$body = \$request->getParsedBody();

            \$rules = [
                'id' => 'required|uuid',
                'name' => 'string|max:255',
                'active' => 'boolean',
            ];

            Validator::init()->validate(array_merge(\$args, \$body), \$rules);

            \$this->useCase->update(
                new ${entity}UpdateRequest(
                    id: new Uuid(\$args['id']),
                    name: \$body['name'],
                    active: \$body['active'] ?? true
                )
            );
            \$return = Response::create(
                ResponseStatus::SUCCESS,
                ['message' => '${entity} updated']
            );
            History::end();
            \$response->getBody()->write((string)json_encode(\$return));
        } catch (Exception \$e) {
            throw new HttpBadRequestException(\$request, \$e->getMessage());
        }

        return \$response->withStatus(200);
    }

    public function destroy(
        ServerRequestInterface \$request,
        ResponseInterface \$response,
        array \$args
    ): ResponseInterface {
        try {
            History::start();
            \$this->useCase->destroy(new Uuid(\$args['id']));
            \$return = Response::create(
                ResponseStatus::SUCCESS,
                ['message' => '${entity} deleted']
            );
            History::end();
            \$response->getBody()->write((string)json_encode(\$return));
        } catch (Exception \$e) {
            throw new HttpBadRequestException(\$request, \$e->getMessage());
        }
        return \$response->withStatus(200);
    }

    public function show(
        ServerRequestInterface \$request,
        ResponseInterface \$response,
        array \$args
    ): ResponseInterface {
        try {
            \$data = \$this->useCase->show(new Uuid(\$args['id']));

            if (is_null(\$data)) {
                throw new Exception('${entity} not found');
            }

            \$return = Response::create(
                ResponseStatus::SUCCESS,
                \$data
            );

            \$response->getBody()->write((string)json_encode(\$return));
        } catch (Exception \$e) {
            throw new HttpBadRequestException(\$request, \$e->getMessage());
        }
        return \$response->withStatus(200);
    }

    public function list(
        ServerRequestInterface \$request,
        ResponseInterface \$response,
        array \$args
    ): ResponseInterface {
        try {
            \$filters = \$request->getQueryParams();
            \$listing = ResponseListing::create(
                ResponseStatus::SUCCESS,
                \$this->useCase->list(\$filters),
                \$this->useCase->countList(\$filters),
                \$filters
            );
            \$response->getBody()->write((string)json_encode(\$listing));
        } catch (Exception \$e) {
            throw new HttpBadRequestException(\$request, \$e->getMessage());
        }
        return \$response->withStatus(200);
    }
}" > "$base_dir/$domain/Presentation/Controller/${entity}Controller.php"