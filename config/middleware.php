<?php

use \Slim\Middleware\HttpBasicAuthentication\PdoAuthenticator;

/**
 * Add basic authentication Middleware
 */
$app->add(new \Slim\Middleware\HttpBasicAuthentication([
    "path" => "/api/v1/auth/token",
    //"realm" => "Protected",
    "secure" => false,
    "authenticator" => new PdoAuthenticator([
        "pdo" => new PDO('mysql:host='.getenv('DB_HOST').'; dbname='.getenv('DB_DATABASE').';charset=utf8', getenv('DB_USERNAME'), getenv('DB_PASSWORD')),
        "table" => "users",
        "user" => "email",
        "hash" => "password"
    ]),
    "error" => function ($request, $response, $arguments) {
        $data = [];
        $data["status"] = "error";
        $data["message"] = $arguments["message"];
        return $response->write(json_encode($data, JSON_UNESCAPED_SLASHES));
    }
]));

/**
 * Add JSON Web Token authentication Middleware
 */ 
$app->add(new \Slim\Middleware\JwtAuthentication([
	"secure" => false,
	"path" => "/api",
	"passthrough" => ["/api/v1/auth/token"],
    "secret" => getenv("JWT_SECRET"),
    "callback" => function ($request, $response, $arguments) use ($container) {
        $container["jwt"] = $arguments["decoded"];
    },
    "error" => function ($request, $response, $arguments) {
        $data["status"] = "error";
        $data["message"] = $arguments["message"];
        return $response
            ->withHeader("Content-Type", "application/json")
            ->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
    }
]));
