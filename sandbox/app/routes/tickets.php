<?php

declare(strict_types=1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;

//Routes for the tickets table

return function (App $app){
    //Displays all tickets in json format


    $app->get('/tickets', function (Request $request, Response  $response){

        $sql = "SELECT * FROM tickets";

        try {

            //New DB connections needs to be established before placing query's
            $db = new DB();
            $conn = $db->connect();

            //SQL statement needs to be prepped and the mode for the fetch type needs to pe passed
            $stmt = $conn ->query($sql);
            $tickets = $stmt->fetchAll(PDO::FETCH_OBJ);

            //To close db connection the variable storing it needs to be set to null
            $db = null;

            //Getting the webpage body and writing the data from the database in json format
            return queryResult($response, $tickets);

        } catch (PDOException $exception){
            //Getting the webpage body and writing error log
            return queryError($response, $exception);
        }
    });

    //Display ticket with determined by id
    $app->get('/tickets/{id}', function (Request $request, Response  $response, array $args){
        $id = $args['id'];
        $sql = "SELECT * FROM tickets WHERE id=$id";

        try {

            //New DB connections needs to be established before placing query's
            $db = new DB();
            $conn = $db->connect();

            //SQL statement needs to be prepped and the mode for the fetch type needs to pe passed
            $stmt = $conn ->query($sql);
            //fetch() returns false
            $ticket = $stmt->fetch(PDO::FETCH_OBJ);

            //Validates if id exists in the db
            if(!$ticket){
                return queryResult($response, "No ticket found with id nº$id");
            }

            //To close db connection the variable storing it needs to be set to null
            $db = null;

            //Getting the webpage body and writing the data from the database in json format
            return queryResult($response, $ticket);
        } catch (PDOException $exception){
            return queryError($response, $exception);
        }
    });

    //Display ticket by title search
    $app->get('/tickets/title/{title}', function (Request $request, Response  $response, array $args){

        $title = $args['title'];

        $sql = "SELECT * FROM tickets WHERE title LIKE '%$title%'";

        try {

            //New DB connections needs to be established before placing query's
            $db = new DB();
            $conn = $db->connect();

            //SQL statement needs to be prepped and the mode for the fetch type needs to pe passed
            $stmt = $conn ->query($sql);
            $ticket = $stmt->fetchAll(PDO::FETCH_OBJ);

            //Validates if id exists in the db
            if(!$ticket){
                return queryResult($response, "No ticket found with title \"$title\"");
            }
            //To close db connection the variable storing it needs to be set to null
            $db = null;

            //Getting the webpage body and writing the data from the database in json format
            return queryResult($response, $ticket);
        } catch (PDOException $exception){
            return queryError($response, $exception);
        }
    });

    //Add new ticket to db
    $app->post('/tickets', function (Request $request, Response  $response){
        //Getting data from the raw body sent in json format
        $params = $request->getQueryParams();

        $sql = "INSERT INTO tickets (title, description) VALUES (:title, :description)";

        if(!validateTicketParams($params)){
            return wrongParams($response,"Wrong params provided");
        }

        //Binds data to the query values to fight sql injections
        $data = [
            ':title'=>$params['title'],
            ':description'=>$params['description']
        ];

        try {

            //New DB connections needs to be established before placing query's
            $db = new DB();
            $conn = $db->connect();

            //SQL statement needs to be prepped and the mode for the fetch type needs to pe passed
            $stmt = $conn ->prepare($sql);

            //Committing data to the database
            $result = $stmt->execute($data);

            //To close db connection the variable storing it needs to be set to null
            $db = null;

            //Getting the webpage body and writing the data from the database in json format
            return queryResult($response, $result);

        } catch (PDOException $exception){
            //Getting the webpage body and writing error log
            return queryError($response, $exception);
        }
    });

    //Updates existing tickets
    $app->put('/tickets/{id}', function(Request $request, Response $response, array $args){
        $params = $request->getQueryParams();

        $id = $args['id'];
        $originalData ="SELECT * FROM tickets WHERE id = $id";
        $sql = "UPDATE tickets set title = :title, description = :description WHERE id = $id";

        //Storing received params to
        $title = $params['title'];
        $description = $params['description'];

        //Preparing array of data to be persisted
        $data = [
            ':title'=>$title,
            ':description'=>$description
        ];

        try {
            //New DB connections needs to be established before placing query's
            $db = new DB();
            $conn = $db->connect();

            //Getting original data from the DB if one of the params are null
            $getData = $conn ->query($originalData);
            $originalTicket = $getData->fetch(PDO::FETCH_OBJ);

            //Validates if id exists in the db
            if(!$originalTicket){
                return queryResult($response, "No ticket found with id nº$id");
            }
            //If any the params given are null maintain original data
            if(!$title){
                $data[':title'] = $originalTicket->title;
            }
            if(!$description){
                $data[':description'] = $originalTicket->description;
            }

            $stmt = $conn ->prepare($sql);

            //Committing data to the database
            $result = $stmt->execute($data);

            //To close db connection the variable storing it needs to be set to null
            $db = null;

            //Getting the webpage body and writing the data from the database in json format
            return queryResult($response, $result);

        } catch (PDOException $exception){
            //Getting the webpage body and writing error log
            return queryError($response, $exception);
        }
    });

    //Deletes ticked searched by id
    $app->delete('/tickets/{id}', function(Request $request, Response $response, array $args){
        $id = $args['id'];

        $sql = "DELETE FROM tickets WHERE id = $id";
        $originalData ="SELECT * FROM tickets WHERE id = $id";

        try {
            $db = new DB();
            $conn = $db->connect();

            $getData = $conn ->query($originalData);
            $originalTicket = $getData->fetch(PDO::FETCH_OBJ);

            //Validates if id exists in the db
            if(!$originalTicket){
                return queryResult($response, "No ticket found with id nº$id");
            }

            $stmt = $conn->prepare($sql);
            $result = $stmt->execute();

            $db = null;
            return queryResult($response, $result);
        } catch (PDOException $exception){
            return queryError($response, $exception);
        }
    });

};

//Validates if length and type of param matches the DB
/*function validateTicketParams(array $params) :bool{
    if( count($params) > 2){
        return false;
    }
    if( !array_key_exists('title',$params )){
        return false;
    }
    if (!array_key_exists('description', $params)){
        return false;
    }
    if(strlen($params['title']) > 100){
        return false;
    }
    return true;
}*/




