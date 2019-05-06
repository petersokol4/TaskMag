<?php
/**
 * Copyright (c) 2019. TaskMag project managing web app. Created by Peter Sokol for Diploma Thesis.
 */

require_once (__DIR__."/../../config/helpers/DbConfig.php");
require_once (__DIR__."/../models/Task.php");

class ColumnController
{
    /** @var object $pdo Copy of PDO connection */
    private $pdo = null;
    /** @var object $database of class DbConfig */
    private $database = null;

    /**
     * ProjectController constructor.
     * autoconnect to DB on creating object of class ProjectController
     */
    public function __construct()
    {
        $this -> database = new DbConfig();
        $this -> pdo = $this -> database->dbConnect();

    }

    /**
     * Redirect function
     * @param $url
     */
    public function redirect($url)
    {
        header("Location: $url");
    }

    /**
     * Execute SQL query function
     * @param $sql
     * @return mixed
     */
    public function runQuery($sql){
        $stmt = $this->pdo->prepare($sql);
        return $stmt;
    }


    //** CHECK INPUTS AND OUTPUTS FUNCTIONS **//

    /* outputs */

    /**
     * Check output light function - doesn't trim html tags (use it for comments,...)
     * @param string $data
     * @return string
     */
    public function checkOutputLight($data) {
        $data = trim($data);        //remove \n, \r, \t doesn't remove spaces between words, if wanted than use -> str_replace(" ", "", trim($data));
        $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');  //html tags will be displayed but no interpreted by browser
        return $data;
    }


    /**
     * Check output function - trim html tags (use it for name, surname,...)
     * @param string $data
     * @return string
     */
    public function checkOutput($data) {
        $data = trim($data);        //remove \n, \r, \t doesn't remove spaces between words, if wanted than use -> str_replace(" ", "", trim($data));
        $data = strip_tags($data);  //delete html tags
        return $data;
    }


    /**
     * Sanitize email function - return only valid email
     * @param string $data
     * @return mixed
     */
    public function sanitizeEmail($data){
        $data = filter_var($data, FILTER_SANITIZE_EMAIL);
        return $data;
    }

    /**
     * Sanitize number function - return only nubers (trim everything else)
     * @param string $data
     * @return mixed
     */
    public function sanitizeNumber($data){
        $data = filter_var($data, FILTER_SANITIZE_NUMBER_INT);
        return $data;
    }




    /*  inputs  */


    /**
     * Check input function - if length is >= 1 -> trim data else return false
     * @param string $data
     * @return bool|string
     */
    public function checkInput($data) {

        if(strlen($data) >= 1){
            $data = trim($data);        //remove \n, \r, \t doesn't remove spaces between words, if wanted than use -> str_replace(" ", "", trim($data));
            return $data;
        }else{
            return false;
        }

    }

    /**
     * Check input length min or min and max
     * $maxLength should be some "wise" value or max value from DB table for this input
     * @param string $data
     * @param int $minLength
     * @param int $maxLength
     * @return bool
     */

    public function checkInputLength($data, $minLength, $maxLength){
        if(strlen($data) < $minLength || strlen($data) > $maxLength){
            return false;
        }else{
            return true;
        }
    }

    public function createColumn($columnName, $columnColor, $projectId, $columnLimit)
    {
        try{

            $stmt = $this->runQuery("INSERT INTO columns (column_title, column_color, project_id, column_created, column_limit) VALUES (:column_title, :column_color, :project_id, NOW(), :column_limit)");
            $stmt->execute(array(
                ":column_title"=>$columnName,
                ":column_color"=>$columnColor,
                ":project_id"=>$projectId,
                ":column_limit"=>$columnLimit
            ));

            $stmt = null;
            $this -> database -> closeConnection();
            return true;


        }catch (Exception $ex){

            //echo "Oops. Chyba pri pripojovaní do databázy." .$ex ->getMessage();
            error_log($ex->getMessage());
            return false;
        }
    }

    public function editColumn($c_Id, $columnName, $columnColor, $columnLimit)
    {
        try{
            $stmt = $this->runQuery("UPDATE columns SET column_title =:column_title, column_color =:column_color, column_limit=:column_limit WHERE id_columns =:id_columns");
            $stmt->execute(array(
                ":column_title"=>$columnName,
                ":column_color"=>$columnColor,
                ":id_columns"=>$c_Id,
                ":column_limit"=>$columnLimit
            ));

            $stmt = null;
            $this -> database -> closeConnection();
            return true;

        }catch (PDOException $ex){

            //echo "Oops. Chyba pri pripojovaní do databázy." .$ex ->getMessage();
            error_log($ex->getMessage());
            return false;
        }
    }

    public function selectAllColumns($projectId)
    {
        try{
            $stmt = $this->runQuery("SELECT * FROM columns WHERE project_id =:project_id ORDER BY id_columns ASC");
            $stmt -> execute(array(
                ":project_id" => $projectId
            ));

            $result = $stmt ->fetchAll(PDO::FETCH_ASSOC);
            $stmt = null;
            $this -> database -> closeConnection();

        }catch (PDOException $ex){

            //echo "Oops. Chyba pri pripojovaní do databázy." .$ex ->getMessage();
            error_log($ex->getMessage());
            return false;
        }


            return $result;

    }

    public function selectSingleColumn($id)
    {
        try{
            $stmt = $this->runQuery("SELECT * FROM columns WHERE id_columns =:id_columns LIMIT 1");
            $stmt -> execute(array(
                ":id_columns" => $id
            ));

            $result = $stmt ->fetchAll(PDO::FETCH_ASSOC);
            $stmt = null;
            $this -> database -> closeConnection();

        }catch (PDOException $ex){

            //echo "Oops. Chyba pri pripojovaní do databázy." .$ex ->getMessage();
            error_log($ex->getMessage());
            return false;
        }


            return $result;

    }

    public function deleteColumn($c_Id)
    {

        try{
            $stmt = $this->runQuery("DELETE FROM columns WHERE id_columns = :id ");
            $stmt->execute(array(
                ":id" => $c_Id
            ));

            //ak sa niečo vymazalo
            if($stmt->rowCount() == 1)
            {
                $stmt = null;
                $this -> database -> closeConnection();
                return true;
            }else{
                $stmt = null;
                $this -> database -> closeConnection();
                return false;
            }
        }catch (Exception $ex){

            //echo "Oops. Chyba pri pripojovaní do databázy." .$ex ->getMessage();
            error_log($ex->getMessage());
            return false;
        }
    }

    public function selectProjectAuthor($projectId, $userId){
        try{
            $stmt = $this->runQuery("SELECT project_author FROM projects WHERE id = :id LIMIT 1");
            $stmt->execute(array(
                ":id" => $projectId
            ));
            $result = $stmt ->fetch(PDO::FETCH_ASSOC);

            if($stmt->rowCount() == 1)
            {

                $stmt = null;
                $this -> database -> closeConnection();

                if($result["project_author"] == $userId)
                {
                    return true;
                }
                else
                {
                    return false;
                }
            }
            else
            {
                $stmt = null;
                $this -> database -> closeConnection();

                return false;
            }


        }catch (PDOException $ex){

            //echo "Oops. Chyba pri pripojovaní do databázy." .$ex ->getMessage();
            error_log($ex->getMessage());
            return false;
        }
    }

    /**
     * Last inserted ID function
     * @return mixed
     */
    private function lastID(){
        $stmt = $this->pdo->lastInsertId();
        return $stmt;
    }

    public function createDefaultColumn($projectId)
    {
        try{

            $stmt = $this->runQuery("INSERT INTO columns (column_title, column_color, project_id, column_created) VALUES (:column_title, :column_color, :project_id, NOW())");
            $stmt->execute(array(
                ":column_title"=>"BackLog",
                ":column_color"=>"purple",
                ":project_id"=>$projectId
            ));

            $id = $this -> lastID();
            $stmt = null;
            $this -> database -> closeConnection();
            return $id;


        }catch (Exception $ex){

            //echo "Oops. Chyba pri pripojovaní do databázy." .$ex ->getMessage();
            error_log($ex->getMessage());
            return false;
        }
    }

    public function formatToZero($null){
        if($null == false){
            $zero = 0;
            return $zero;
        }else{
            return $null;
        }
    }


    public function changeColumn($c_Id, $c_newId)
    {
        try{
            $stmt = $this->runQuery("UPDATE tasks SET column_id =:column_new_id WHERE column_id =:column_old_id");
            $stmt->execute(array(
                ":column_new_id"=>$c_newId,
                ":column_old_id"=>$c_Id
            ));

            $taskCount = $stmt->rowCount();

            $stmt = null;
            $this -> database -> closeConnection();
            return $this->formatToZero($taskCount);

        }catch (PDOException $ex){

            //echo "Oops. Chyba pri pripojovaní do databázy." .$ex ->getMessage();
            error_log($ex->getMessage());
            return false;
        }
    }
}