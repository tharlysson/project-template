<?php

namespace POS\Shared\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use trash\Auth\Application\UseCases\AuthUseCase;
use trash\Auth\Infra\Repositories\Doctrine\TokenDB;
use trash\Auth\Infra\Repositories\Doctrine\UserDB;

class AuthMiddleware
{
    public function __invoke(Request $request, RequestHandler $handler): ResponseInterface
    {
        $authUseCase = new AuthUseCase(new UserDB());
        $authUseCase->validateToken($request, new TokenDB());
        return $handler->handle($request);
    }
}
