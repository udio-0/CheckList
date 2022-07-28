<?php

declare(strict_types=1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;


//Routes for the tickets table

return function (App $app){
    //Displays all sections in json format
    $app->get('/sections', function (Request $request, Response  $response){

        $sql = "SELECT * FROM sections";

        try {

            //New DB connections needs to be established before placing query's
            $db = new DB();
            $conn = $db->connect();

            //SQL statement needs to be prepped and the mode for the fetch type needs to pe passed
            $stmt = $conn ->query($sql);
            $sections = $stmt->fetchAll(PDO::FETCH_OBJ);

            //To close db connection the variable storing it needs to be set to null
            $db = null;

            //Getting the webpage body and writing the data from the database in json format
            return queryResult($response, $sections);

        } catch (PDOException $exception){
            //Getting the webpage body and writing error log
            return queryError($response, $exception);
        }
    });

    //Display section with determined by id
    $app->get('/sections/{id}', function (Request $request, Response  $response, array $args){
        $id = $args['id'];
        $sql = "SELECT * FROM sections WHERE id=$id";

        try {

            //New DB connections needs to be established before placing query's
            $db = new DB();
            $conn = $db->connect();

            //SQL statement needs to be prepped and the mode for the fetch type needs to pe passed
            $stmt = $conn ->query($sql);
            //fetch() returns false if no match is found
            $section = $stmt->fetch(PDO::FETCH_OBJ);

            //Validates if id exists in the db
            if(!$section){
                return queryResult($response, "No section found with id nº$id");
            }

            //To close db connection the variable storing it needs to be set to null
            $db = null;

            //Getting the webpage body and writing the data from the database in json format
            return queryResult($response, $section);
        } catch (PDOException $exception){
            return queryError($response, $exception);
        }
    });

    //Add new section to db
    $app->post('/sections', function (Request $request, Response  $response){
        //Getting data from the raw body sent in json format
        $params = $request->getQueryParams();
        $id = $params['tab_id'];

        $sql = "INSERT INTO sections (name, tab_id) VALUES (:name, :tab_id)";
        $checkForTabId = "SELECT * FROM tabs WHERE id=$id";

        if(!validateSectionsParams($params)){
            return wrongParams($response,"Wrong params provided");
        }

        //Binds data to the query values to fight sql injections
        $data = [
            ':name'=>$params['name'],
            ':tab_id'=>$params['tab_id']
        ];

        try {

            //New DB connections needs to be established before placing query's
            $db = new DB();
            $conn = $db->connect();

            $stmt = $conn ->query($checkForTabId);
            //fetch() returns false
            $ticket = $stmt->fetch(PDO::FETCH_OBJ);

            //Validates if id exists in the db
            if(!$ticket){
                return queryResult($response, "The tab nº$id that you are trying to add a section doesn't exist.");
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

    //Updates existing section
    $app->put('/sections/{id}', function(Request $request, Response $response, array $args){
        $params = $request->getQueryParams();

        $id = $args['id'];
        $name = $params['name'];
        $tab_id = $params['tab_id'];

        $originalData ="SELECT * FROM sections WHERE id = $id";
        $tabValidation = "SELECT * FROM tabs WHERE id = $tab_id";
        $sql = "UPDATE sections set name = :name, tab_id = :tab_id WHERE id = $id";



        //Preparing array of data to be persisted
        $data = [
            ':name'=>$name,
            ':tab_id'=>$tab_id];

        try {
            //New DB connections needs to be established before placing query's
            $db = new DB();
            $conn = $db->connect();

            if ($tab_id != ""){
                //Checks if new tab id provided exists in db
                $validTab = $conn->query($tabValidation);
                $tab = $validTab->fetch(PDO::FETCH_OBJ);


                //Validates if id exists in the db
                if(!$tab){
                    return queryResult($response, "No tab found with id nº$id");
                }
            }


            //Getting original data from the DB if one of the params are null
            $getData = $conn ->query($originalData);
            $originalSection = $getData->fetch(PDO::FETCH_OBJ);

            //Validates if id exists in the db
            if(!$originalSection){
                return queryResult($response, "No section found with id nº$id");
            }
            //If any the params given are null maintain original data
            if(!$name){
                $data[':name'] = $originalSection->name;
            }
            if($tab_id == ""){
                $data[':tab_id'] = $originalSection->tab_id;
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

    //Deletes section searched by id
    $app->delete('/sections/{id}', function(Request $request, Response $response, array $args){
        $id = $args['id'];

        $sql = "DELETE FROM sections WHERE id = $id";
        $originalData ="SELECT * FROM sections WHERE id = $id";

        try {
            $db = new DB();
            $conn = $db->connect();

            $getData = $conn ->query($originalData);
            $originalTab = $getData->fetch(PDO::FETCH_OBJ);

            //Validates if id exists in the db
            if(!$originalTab){
                return queryResult($response, "No section found with id nº$id");
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
function validateSectionsParams(array $params) :bool{
    if( count($params) > 3){
        return false;
    }
    if( !array_key_exists('name',$params )){
        return false;
    }
    if (!array_key_exists('tab_id', $params)){
        return false;
    }
    if(strlen($params['name']) > 100){
        return false;
    }
    return true;
}




