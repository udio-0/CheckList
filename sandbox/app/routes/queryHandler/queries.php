<?php

declare(strict_types=1);

use Psr\Http\Message\ResponseInterface as Response;

//Gets result from the placed query. Most likely boolean
function queryResult(Response $response, $stmt): Response
{
    $response
        ->getBody()
        ->write(json_encode($stmt));

    //Returning result from query
    return $response
        ->withHeader('content-type', 'application/json')
        ->withStatus(200);
}

//In case of error connecting to db, display the error in json format
function queryError(Response $response, PDOException $exception): Response
{
    $error = array(
        "message" => $exception->getMessage()
    );

    $response
        ->getBody()
        ->write(json_encode($error));
    return $response
        ->withHeader('content-type','application/json')
        ->withStatus(500);
}

//In case of error on the params given
function wrongParams(Response $response, string $message): Response
{
    $response
        ->getBody()
        ->write($message);
    return $response
        ->withHeader('content-type','application/json')
        ->withStatus(500);
}

