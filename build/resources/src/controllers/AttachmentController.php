<?php
/**
 * Copyright (c) 2019. TaskMag project managing web app. Created by Peter Sokol for Diploma Thesis.
 */

require_once (__DIR__."/../../config/helpers/DbConfig.php");
require_once (__DIR__."/../models/Attachment.php");


class AttachmentController
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
        $data = htmlspecialchars($data, ENT_COMPAT, 'UTF-8');  //html tags will be displayed but no interpreted by browser
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

    /* MAIN METHODS */

    public function generateName(){
        $fileName = bin2hex((new \DateTime())->setTimezone(new DateTimeZone('Europe/Prague'))->format('YmdHism'));
        return $fileName;
    }

    public function uploadAttach($newFileName, $originalName, $taskId, $projectId, $attachDir, $userId){
        try{
            $stmt = $this->runQuery("INSERT INTO attachements (attach_name, attach_name_orig, attach_dir, upload_time, task_id, project_id, uploaded_by) VALUES (:attach_name, :attach_name_orig, :attach_dir, NOW(), :task_id, :project_id, :uploaded_by)");
            $stmt->execute(array(
                ":attach_name" => $newFileName,
                ":attach_name_orig" => $originalName,
                ":attach_dir" => $attachDir,
                ":task_id" => $taskId,
                ":project_id" => $projectId,
                ":uploaded_by"=> $userId
            ));
            $stmt = null;
            $this -> database -> closeConnection();
            return true;
        }catch(PDOException $e){
            error_log($e->getMessage());
            return false;
        }
    }

    /**
     * @param $taskId
     * @return bool
     */
    public function selectAllAttachments($taskId)
    {
        try{
            $stmt = $this->runQuery("SELECT * FROM attachements WHERE task_id =:task_id ORDER BY id_attachements DESC");
            $stmt -> execute(array(
                ":task_id" => $taskId
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

    public function deleteAttachment($id){
        try{
            $stmt = $this->runQuery("DELETE FROM attachements WHERE id_attachements = :id");
            $stmt->execute(array(
                ":id" => $id
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

//    public function getAttachmentInfo($id){
//        try{
//            $stmt = $this->runQuery("SELECT * FROM attachements WHERE id_attachements = :id");
//            $stmt->execute(array(
//                ":id" => $id
//            ));
//
//            $result = $stmt ->fetchAll(PDO::FETCH_ASSOC);
//            $stmt = null;
//            $this -> database -> closeConnection();
//            return $result;
//
//        }catch (PDOException $ex){
//
//            //echo "Oops. Chyba pri pripojovaní do databázy." .$ex ->getMessage();
//            error_log($ex->getMessage());
//            return false;
//        }
//    }
}