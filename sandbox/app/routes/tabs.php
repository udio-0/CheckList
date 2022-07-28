<?php

declare(strict_types=1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;


//Routes for the tickets table

return function (App $app){
    //Displays all tabs in json format
    $app->get('/tabs', function (Request $request, Response  $response){

        $sql = "SELECT * FROM tabs";

        try {

            //New DB connections needs to be established before placing query's
            $db = new DB();
            $conn = $db->connect();

            //SQL statement needs to be prepped and the mode for the fetch type needs to pe passed
            $stmt = $conn ->query($sql);
            $tabs = $stmt->fetchAll(PDO::FETCH_OBJ);

            //To close db connection the variable storing it needs to be set to null
            $db = null;

            //Getting the webpage body and writing the data from the database in json format
            return queryResult($response, $tabs);

        } catch (PDOException $exception){
            //Getting the webpage body and writing error log
            return queryError($response, $exception);
        }
    });

    //Display tabs with determined by id
    $app->get('/tabs/{id}', function (Request $request, Response  $response, array $args){
        $id = $args['id'];
        $sql = "SELECT * FROM tabs WHERE id=$id";

        try {

            //New DB connections needs to be established before placing query's
            $db = new DB();
            $conn = $db->connect();

            //SQL statement needs to be prepped and the mode for the fetch type needs to pe passed
            $stmt = $conn ->query($sql);
            //fetch() returns false if no match i  a
            $tab = $stmt->fetch(PDO::FETCH_OBJ);

            //Validates if id exists in the db
            if(!$tab){
                return queryResult($response, "No tab found with id nº$id");
            }

            //To close db connection the variable storing it needs to be set to null
            $db = null;

            //Getting the webpage body and writing the data from the database in json format
            return queryResult($response, $tab);
        } catch (PDOException $exception){
            return queryError($response, $exception);
        }
    });

    //Add new tab to db
    $app->post('/tabs', function (Request $request, Response  $response){
        //Getting data from the raw body sent in json format
        $params = $request->getQueryParams();
        $id = $params['ticket_id'];

        $sql = "INSERT INTO tabs (name, ticket_id) VALUES (:name, :ticket_id)";
        $checkForTicketId = "SELECT * FROM tickets WHERE id=$id";

        if(!validateTabParams($params)){
            return wrongParams($response,"Wrong params provided");
        }

        //Binds data to the query values to fight sql injections
        $data = [
            ':name'=>$params['name'],
            ':ticket_id'=>$params['ticket_id']
        ];

        try {

            //New DB connections needs to be established before placing query's
            $db = new DB();
            $conn = $db->connect();

            $stmt = $conn ->query($checkForTicketId);
            //fetch() returns false
            $ticket = $stmt->fetch(PDO::FETCH_OBJ);

            //Validates if id exists in the db
            if(!$ticket){
                return queryResult($response, "The ticket nº$id that you are trying to add a tab doesn't exist.");
            }

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

    //Updates existing tabs
    $app->put('/tabs/{id}', function(Request $request, Response $response, array $args){
        $params = $request->getQueryParams();

        $id = $args['id'];
        $name = $params['name'];
        $ticket_id = $params['ticket_id'];

        $originalData ="SELECT * FROM tabs WHERE id = $id";
        $ticketValidation = "SELECT * FROM tickets WHERE id = $ticket_id";
        $sql = "UPDATE tabs set name = :name, ticket_id = :ticket_id WHERE id = $id";



        //Preparing array of data to be persisted
        $data = [
            ':name'=>$name,
            ':ticket_id'=>$ticket_id
        ];

        try {
            //New DB connections needs to be established before placing query's
            $db = new DB();
            $conn = $db->connect();

            if($ticket_id != ""){
                //Checks if new ticket id provided exists in db
                $validTicket = $conn->query($ticketValidation);
                $ticket = $validTicket->fetch(PDO::FETCH_OBJ);

                //Validates if id exists in the db
                if(!$ticket){
                    return queryResult($response, "No ticket found with id nº$id");
                }
            }


            //Getting original data from the DB if one of the params are null
            $getData = $conn ->query($originalData);
            $originalTab = $getData->fetch(PDO::FETCH_OBJ);

            //Validates if id exists in the db
            if(!$originalTab){
                return queryResult($response, "No tab found with id nº$id");
            }
            //If any the params given are null maintain original data
            if(!$name){
                $data[':name'] = $originalTab->name;
            }
            if(!$ticket_id){
                $data[':ticket_id'] = $originalTab->ticket_id;
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

    //Deletes tab searched by id
    $app->delete('/tabs/{id}', function(Request $request, Response $response, array $args){
        $id = $args['id'];

        $sql = "DELETE FROM tabs WHERE id = $id";
        $originalData ="SELECT * FROM tabs WHERE id = $id";

        try {
            $db = new DB();
            $conn = $db->connect();

            $getData = $conn ->query($originalData);
            $originalTab = $getData->fetch(PDO::FETCH_OBJ);

            //Validates if id exists in the db
            if(!$originalTab){
                return queryResult($response, "No tab found with id nº$id");
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
function validateTabParams(array $params) :bool{
    if( count($params) > 3){
        return false;
    }
    if( !array_key_exists('name',$params )){
        return false;
    }
    if (!array_key_exists('ticket_id', $params)){
        return false;
    }
    if(strlen($params['name']) > 100){
        return false;
    }
    return true;
}




