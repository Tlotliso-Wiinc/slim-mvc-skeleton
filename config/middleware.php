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
	"passthrough" => ["/api/v1/auth/token", "/signin", "/signup"],
    "secret" => getenv("JWT_SECRET"),
    "logger" => $container["logger"],
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

/**
 * Add Cross-origin resource sharing (Cors) Middleware
 */ 
$app->add(new \Tuupola\Middleware\Cors([
    "logger" => $container["logger"],
    "origin" => ["*"],
    "methods" => ["GET", "POST", "PUT", "PATCH", "DELETE"],
    "headers.allow" => ["Authorization", "If-Match", "If-Unmodified-Since"],
    "headers.expose" => ["Authorization", "Etag"],
    "credentials" => true,
    "cache" => 60,
    "error" => function ($request, $response, $arguments) {
        $data["status"] = "error";
        $data["message"] = $arguments["message"];
        return $response
            ->withHeader("Content-Type", "application/json")
            ->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
    }
]));

// Add negotiation Middleware
$app->add(new Gofabian\Negotiation\NegotiationMiddleware([
    'accept' => ['application/json']
]));
