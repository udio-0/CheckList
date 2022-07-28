<?php

declare(strict_types=1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;


//Routes for the tickets table

return function (App $app){
    //Displays all sections in json format
    $app->get('/tasks', function (Request $request, Response  $response){

        $sql = "SELECT * FROM tasks";

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
    $app->get('/tasks/{id}', function (Request $request, Response  $response, array $args){
        $id = $args['id'];
        $sql = "SELECT * FROM tasks WHERE id=$id";

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
                return queryResult($response, "No tasks found with id nº$id");
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
    $app->post('/tasks', function (Request $request, Response  $response){
        //Getting data from the raw body sent in json format
        $params = $request->getQueryParams();
        $section_id = $params['section_id'];
        $name = $params['name'];

        $sql = "INSERT INTO tasks (name, section_id) VALUES (:name, :section_id)";
        $checkForSectionId = "SELECT * FROM sections WHERE id=$section_id";

        if(!validateTaskParams($params)){
            return wrongParams($response,"Wrong params provided");
        }

        //Binds data to the query values to fight sql injections
        $data = [
            ':name'=>$name,
            ':section_id'=>$section_id
        ];

        try {

            //New DB connections needs to be established before placing query's
            $db = new DB();
            $conn = $db->connect();

            $stmt = $conn ->query($checkForSectionId);
            //fetch() returns false
            $ticket = $stmt->fetch(PDO::FETCH_OBJ);

            //Validates if id exists in the db
            if(!$ticket){
                return queryResult($response, "The section nº$section_id that you are trying to add a task doesn't exist.");
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
    $app->put('/tasks/{id}', function(Request $request, Response $response, array $args){
        $params = $request->getQueryParams();

        $id = $args['id'];
        $name = $params['name'];
        $section_id = $params['section_id'];
        $status = $params['status'];
        $is_checked = $params['is_checked'];

        $originalData ="SELECT * FROM tasks WHERE id = $id";
        $sectionValidation = "SELECT * FROM sections WHERE id = $section_id";
        $sql = "UPDATE tasks set name = :name, section_id = :section_id, status = :status, is_checked = :is_checked WHERE id = $id";



        switch ($is_checked){
            case "true":
                $is_checked = true;
                break;
            case "false":
                break;
            default:
                return queryResult($response, "is_checked column type is boolean");
        }

        //Preparing array of data to be persisted
        $data = [
            ':name'=>$name,
            ':section_id'=>$section_id,
            ':status'=>$status,
            ':is_checked'=>$is_checked
            ];

        try {
            //New DB connections needs to be established before placing query's
            $db = new DB();
            $conn = $db->connect();

            if ($section_id != ""){
                //Checks if new section id provided exists in db
                $validSection = $conn->query($sectionValidation);
                $section = $validSection->fetch(PDO::FETCH_OBJ);

                //Validates if id exists in the db
                if(!$section){
                    return queryResult($response, "No section found with id nº$id");
                }
            }

            //Getting original data from the DB if one of the params are null
            $getData = $conn ->query($originalData);
            $originalTask = $getData->fetch(PDO::FETCH_OBJ);

            //Validates if id exists in the db
            if(!$originalTask){
                return queryResult($response, "No task found with id nº$id");
            }
            //If any the params given are null maintain original data
            if(!$name){
                $data[':name'] = $originalTask->name;
            }
            if(!$section_id){
                $data[':section_id'] = $originalTask->section_id;
            }
            if(!$status){
                $data[':status'] = $originalTask->status;
            }
            if(!$is_checked){
                $data[':is_checked'] = $originalTask->is_checked;
            }

            //Validates if the status given as param is contained in the enum of the column
            if(!validateStatusGiven($status, $conn)){
                return queryResult($response, "Status type NA");
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
    $app->delete('/tasks/{id}', function(Request $request, Response $response, array $args){
        $id = $args['id'];

        $sql = "DELETE FROM tasks WHERE id = $id";
        $originalData ="SELECT * FROM tasks WHERE id = $id";

        try {
            $db = new DB();
            $conn = $db->connect();

            $getData = $conn ->query($originalData);
            $originalTab = $getData->fetch(PDO::FETCH_OBJ);

            //Validates if id exists in the db
            if(!$originalTab){
                return queryResult($response, "No task found with id nº$id");
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
function validateTaskParams(array $params) :bool{
    if( count($params) > 3){
        return false;
    }
    if( !array_key_exists('name',$params )){
        return false;
    }
    if (!array_key_exists('section_id', $params)){
        return false;
    }
    if(strlen($params['name']) > 100){
        return false;
    }
    return true;
}

//Validates given status
function validateStatusGiven(string $givenStatus, PDO $conn) : bool {
    //Query to obtain string containing the enum values
    $statusOptions =   "SELECT SUBSTRING(COLUMN_TYPE,5) as status
                            FROM information_schema.COLUMNS
                            WHERE TABLE_SCHEMA='sandbox'
                              AND TABLE_NAME='tasks'
                              AND COLUMN_NAME='status'";

    //Committing query
    $validStatus = $conn->query($statusOptions);
    $dbStatus = $validStatus->fetch(PDO::FETCH_OBJ);

    //Creating array out of the string given to validate status given
    $charsToIgnore = array("(", ")", "'");
    $filteredResult = str_replace($charsToIgnore, '', $dbStatus->status);
    $statusArray = explode(",", $filteredResult);

    if (!in_array($givenStatus, $statusArray)){
        return false;
    }
    return true;
}



