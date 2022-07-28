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

require __DIR__ . '/../config/db.php';
require __DIR__ . '/routes/queryHandler/queries.php';


return function (App $app){
    $app->options('/{routes:.*}', function (Request $request, Response $response) {
        // CORS Pre-Flight OPTIONS Request Handler
        return $response;
    });

    $app->get('/', function (Request $request, Response $response) {
        $response->getBody()->write('Hello world!');
        return $response;
    });

    $app->group('/tickets', function (Group $group) {
        $group->get('', ListTicketAction::class);
        $group->get('/{id}', ViewTicketAction::class);
        $group->post('', CreateTicketAction::class);
        $group->delete('/{id}', DeleteTicketAction::class);
        $group->put('/{id}', UpdateTicketAction::class);
    });

    $app->group('/tabs', function (Group $group) {
        $group->get('', ListTabAction::class);
        $group->get('/{id}', ViewTabAction::class);
        $group->post('', CreateTabAction::class);
        $group->delete('/{id}', DeleteTabAction::class);
        $group->put('/{id}', UpdateTabAction::class);
    });

    $app->group('/sections', function (Group $group) {
        $group->get('', ListSectionAction::class);
        $group->get('/{id}', ViewSectionAction::class);
        $group->post('', CreateSectionAction::class);
        $group->delete('/{id}', DeleteSectionAction::class);
        $group->put('/{id}', UpdateSectionAction::class);
    });

    $app->group('/tasks', function (Group $group) {
        $group->get('', ListTaskAction::class);
        $group->get('/{id}', ViewTaskAction::class);
        $group->post('', CreateTaskAction::class);
        $group->delete('/{id}', DeleteTaskAction::class);
        $group->put('/{id}', UpdateTaskAction::class);
    });

    /*$app->group('/tickets', function (Group $group) {
        //Displays all tickets in json format
        $group->get('', ListTicketAction::class);
    });*/

    /*//TODO Create routes for the api in separate files
    $tickets = require __DIR__ . "/routes/tickets.php";
    $tickets($app);

    $tabs = require __DIR__ . "/routes/tabs.php";
    $tabs($app);

    $sessions = require __DIR__ . "/routes/sections.php";
    $sessions($app);

    $tasks = require  __DIR__ . "/routes/tasks.php";
    $tasks($app);*/
};
