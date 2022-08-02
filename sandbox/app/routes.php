<?php

declare(strict_types=1);

use App\Application\Actions\Section\CreateSectionAction;
use App\Application\Actions\Section\DeleteSectionAction;
use App\Application\Actions\Section\ListSectionAction;
use App\Application\Actions\Section\UpdateSectionAction;
use App\Application\Actions\Section\ViewSectionAction;
use App\Application\Actions\Tab\CreateTabAction;
use App\Application\Actions\Tab\DeleteTabAction;
use App\Application\Actions\Tab\ListTabAction;
use App\Application\Actions\Tab\UpdateTabAction;
use App\Application\Actions\Tab\ViewTabAction;
use App\Application\Actions\Task\CreateTaskAction;
use App\Application\Actions\Task\DeleteTaskAction;
use App\Application\Actions\Task\ListTaskAction;
use App\Application\Actions\Task\UpdateTaskAction;
use App\Application\Actions\Task\ViewTaskAction;
use App\Application\Actions\Ticket\CreateTicketAction;
use App\Application\Actions\Ticket\DeleteTicketAction;
use App\Application\Actions\Ticket\ListTicketAction;
use App\Application\Actions\Ticket\UpdateTicketAction;
use App\Application\Actions\Ticket\ViewTicketAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;
use Slim\Views\Twig;
use Symfony\Component\Yaml\Yaml;

require __DIR__ . '/../config/db.php';


return function (App $app){
    $app->options('/{routes:.*}', function (Request $request, Response $response) {
        // CORS Pre-Flight OPTIONS Request Handler
        return $response;
    });

    $app->get('/', function (Request $request, Response $response) {
        $response->getBody()->write('Hello world!');
        return $response;
    });

    $app->group('/api', function (Group $group) {
        $group->get('', function (Request $request, Response $response) {
            $response->getBody()->write('Welcome to the JIRA Checklist API! Go to https://sandbox.exads.rocks/api/docs for more info.');
            return $response;
        });

        $group->get('/docs', function ($request, $response, $args) {
            $yamlFile = __DIR__ . '/../checklist.yaml';
            return $this->get('view')->render($response, 'docs/swagger.twig', [
                'spec' =>json_encode(Yaml::parseFile($yamlFile)),
            ]);
        });

        $group->group('/tickets', function (Group $group) {
            $group->get('', ListTicketAction::class);
            $group->get('/{id}', ViewTicketAction::class);
            $group->post('', CreateTicketAction::class);
            $group->put('/{id}', UpdateTicketAction::class);
            $group->delete('/{id}', DeleteTicketAction::class);
        });

        $group->group('/tabs', function (Group $group) {
            $group->get('', ListTabAction::class);
            $group->get('/{id}', ViewTabAction::class);
            $group->post('', CreateTabAction::class);
            $group->delete('/{id}', DeleteTabAction::class);
            $group->put('/{id}', UpdateTabAction::class);
        });

        $group->group('/sections', function (Group $group) {
            $group->get('', ListSectionAction::class);
            $group->get('/{id}', ViewSectionAction::class);
            $group->post('', CreateSectionAction::class);
            $group->delete('/{id}', DeleteSectionAction::class);
            $group->put('/{id}', UpdateSectionAction::class);
        });

        $group->group('/tasks', function (Group $group) {
            $group->get('', ListTaskAction::class);
            $group->get('/{id}', ViewTaskAction::class);
            $group->post('', CreateTaskAction::class);
            $group->delete('/{id}', DeleteTaskAction::class);
            $group->put('/{id}', UpdateTaskAction::class);
        });


    });


};
