<?php
/**
 * Copyright (c) 2019. TaskMag project managing web app. Created by Peter Sokol for Diploma Thesis.
 */

require_once (__DIR__."/../../config/helpers/DbConfig.php");
require_once (__DIR__."/../models/Project.php");

class ProjectController

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
     * Last inserted ID function
     * @return mixed
     */
    private function lastID(){
        $stmt = $this->pdo->lastInsertId();
        return $stmt;
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

    /**
     * Correct format function
     * if no result FetchColumn == false -> format to 0
     *
     * @param mixed $null
     * @return int
     */
    public function formatToZero($null){
        if($null == false){
            $zero = 0;
            return $zero;
        }else{
            return $null;
        }
    }

    /**
     * Prercentage function
     *
     * @param $part
     * @param $all
     * @return float|int
     */
    public function formatToPercentage($part, $all){
        if($all != 0)
        {
            $percentage = round(($part/$all)*100);
            return $percentage;
        }
        else
        {
            return 0;
        }

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


    /*
     *
     *  MAIN METHODS
     *
     */

    public function generateName(){
        $fileName = bin2hex((new \DateTime())->setTimezone(new DateTimeZone('Europe/Prague'))->format('YmdHism'));
        return $fileName;
    }

    public function createFolder(){
        $folderName = $this->generateName();

        //The name of the directory that we need to create.
        $directoryName = "../../../public_html/uploads/projects/". $folderName;

        //Directory does not exist, so lets create it.
        mkdir($directoryName, 0755, true);
        return $folderName;
    }

    public function deleteFolder($folderName){

        $directoryName = "../../../public_html/uploads/projects/". $folderName;

        if (is_dir($directoryName)) {
            array_map('unlink', glob("$directoryName/*.*"));
            error_log("$directoryName/*.*");
            rmdir($directoryName);
            return true;
        }



    }

    /**
     * Create New Project function
     *
     * @param $projectClient
     * @param $projectName
     * @param $projectDescription
     * @param $projectStatus
     * @param $projectCategory
     * @param $projectStart
     * @param $projectEnd
     * @param $projectAuthor
     * @return bool
     */
    public function createProject($projectClient, $projectName, $projectDescription, $projectStatus, $projectCategory, $projectStart, $projectEnd, $projectAuthor){
        try{
            //create unique folder for project
            if($folderName = $this->createFolder()){

                $stmt = $this->runQuery("INSERT INTO projects (project_client, project_name, project_description, project_status, project_category, project_start, project_end, create_time, project_author, project_directory) VALUES (:projectClient, :projectName, :projectDescription, :projectStatus, :projectCategory, :projectStart, :projectEnd, NOW(), :projectAuthor, :folderName)");
                $stmt->execute(array(
                    ":projectClient"=>$projectClient,
                    ":projectName"=>$projectName,
                    ":projectDescription"=>$projectDescription,
                    ":projectStatus"=>$projectStatus,
                    ":projectCategory"=>$projectCategory,
                    ":projectStart"=>$projectStart,
                    ":projectEnd"=>$projectEnd,
                    ":projectAuthor"=>$projectAuthor,
                    ":folderName" => $folderName
                ));

                $p_Id = $this->lastID();

                $stmt = $this->runQuery("INSERT INTO projects_users (project_id, user_id, user_role, assign_time) VALUES (:project_id, :user_id, :user_role, NOW())");
                $stmt->execute(array(
                    ":project_id"=>$p_Id,
                    ":user_id"=>$projectAuthor,
                    ":user_role"=> 1
                ));
                    $stmt = null;
                    $this -> database -> closeConnection();
                    return true;
            }else{

                return false;
            }

        }catch (Exception $ex){

            //echo "Oops. Chyba pri pripojovaní do databázy." .$ex ->getMessage();
            error_log($ex->getMessage());
            return false;
        }


    }


    /* fetch all projects */

    /**
     * @param $projectAuthor
     * @return mixed
     */
    public function selectAllProjects($projectAuthor){
        try{
            $stmt = $this->runQuery("SELECT * FROM projects LEFT JOIN projects_users ON projects.id = projects_users.project_id WHERE projects_users.user_id = :projectAuthor ORDER BY projects.id DESC");
            $stmt -> execute(array(
                ":projectAuthor" => $projectAuthor
            ));

            $result = $stmt ->fetchAll(PDO::FETCH_ASSOC);
            $stmt = null;
            $this -> database -> closeConnection();

        }catch (PDOException $ex){

            //echo "Oops. Chyba pri pripojovaní do databázy.".$ex ->getMessage();
            error_log($ex->getMessage());
            return false;
        }

        return $result;
    }


    /* fetch only one project by id */
    /**
     * @param $id
     * @return mixed
     */
    public function selectSingleProject($id){
        try{
            $stmt = $this->runQuery("SELECT * FROM projects WHERE id = :id LIMIT 1");
            $stmt->execute(array(
                ":id" => $id
            ));
            $result = $stmt ->fetchAll(PDO::FETCH_ASSOC);

            $stmt = null;
            $this -> database -> closeConnection();

        }catch (PDOException $ex){

            echo "Oops. Chyba pri pripojovaní do databázy.".$ex ->getMessage();
            //error_log($exception->getMessage());
            //TODO: buď to presmerovať na nejaký error page alebo na index
            die();
        }

        return $result;
    }


    /* edit project */
    /**
     * @param $id
     * @param $projectClient
     * @param $projectName
     * @param $projectDescription
     * @param $projectStatus
     * @param $projectCategory
     * @param $projectStart
     * @param $projectEnd
     * @return bool
     */
    public function editProject($id, $projectClient, $projectName, $projectDescription, $projectStatus, $projectCategory, $projectStart, $projectEnd){
        try{
            $stmt = $this->runQuery("UPDATE projects SET project_client = :projectClient, project_name = :projectName, project_description = :projectDescription, project_status = :projectStatus, project_category = :projectCategory, project_start = :projectStart, project_end =:projectEnd  WHERE id = :id");
            $stmt->execute(array(
                ":projectClient"=>$projectClient,
                ":projectName"=>$projectName,
                ":projectDescription"=>$projectDescription,
                ":projectStatus"=>$projectStatus,
                ":projectCategory"=>$projectCategory,
                ":projectStart"=>$projectStart,
                ":projectEnd"=>$projectEnd,
                ":id"=>$id
            ));

            $stmt = null;
            $this -> database -> closeConnection();
            return true;

        }catch (PDOException $ex){

            echo "Oops. Chyba pri pripojovaní do databázy.".$ex ->getMessage();
            //error_log($exception->getMessage());
            //TODO: buď to presmerovať na nejaký error page alebo na index
            die();
        }
    }


    /* delete project */
    /**
     * @param $id
     * @return bool
     */
    public function deleteProject($id){

        try{

            $stmt = $this->runQuery("SELECT project_directory FROM projects WHERE id=:id");
            $stmt->execute(array(
                ":id" => $id
            ));
            $result = $stmt->fetch();
            $directory = $result['project_directory'];
            if($this->deleteFolder($directory))
            {
                $stmt = $this->runQuery("DELETE FROM projects WHERE id = :id");
                $stmt->execute(array(
                    ":id" => $id
                ));

                $stmt = null;
                $this -> database -> closeConnection();
                return true;
            }
            else
            {
                return false;
            }



        }catch (PDOException $ex){

            echo "Oops. Chyba pri pripojovaní do databázy.".$ex ->getMessage();
            //error_log($exception->getMessage());
            //TODO: buď to presmerovať na nejaký error page alebo na index
            die();
        }
    }

    /**
     * @param $userId
     * @return int
     */
    public function countAllProjects($userId){
        try{
            $stmt = $this->runQuery("SELECT COUNT(*) FROM projects_users WHERE user_id= :user_id");
            $stmt -> execute(array(
                ":user_id" => $userId
            ));
            $result = (int)$stmt->fetchColumn();

            $stmt = null;
            $this -> database -> closeConnection();

        }catch (PDOException $ex){

            echo "Oops. Chyba pri pripojovaní do databázy.".$ex ->getMessage();
            //error_log($exception->getMessage());
            //TODO: buď to presmerovať na nejaký error page alebo na index
            die();
        }

        return $result;
    }

    /* count of "my" projects */
    /**
     * @param $projectAuthor
     * @return int
     */
    public function countMyProjects($projectAuthor){
        try{
            $stmt = $this->runQuery("SELECT COUNT(*) FROM projects_users WHERE user_id=:projectAuthor AND user_role = 1");
            $stmt -> execute(array(
                ":projectAuthor" => $projectAuthor
            ));
            $result = (int)$stmt->fetchColumn();
            $stmt = null;
            $this -> database -> closeConnection();

        }catch (PDOException $ex){

            echo "Oops. Chyba pri pripojovaní do databázy.".$ex ->getMessage();
            //error_log($exception->getMessage());
            //TODO: buď to presmerovať na nejaký error page alebo na index
            die();
        }

        //return result if not empty
        if (!empty($result)) {
            return $result;
        }
    }

    /* count of selected type (by status) of projects */
    /**
     * @param $projectStatus
     * @param $projectAuthor
     * @return int
     */
    public function countSelectedProjects($projectStatus, $projectAuthor){
        try{
            $stmt = $this->runQuery("SELECT COUNT(*) FROM projects LEFT JOIN projects_users ON projects.id = projects_users.project_id WHERE projects_users.user_id = :projectAuthor AND projects.project_status=:projectStatus");    //Nezačal | Prebieha | Pozastavený | Zrušený | Dokončený
            $stmt -> execute(array(
                ":projectStatus" => $projectStatus,
                ":projectAuthor" => $projectAuthor
            ));
            $result = (int)$stmt->fetchColumn();

            $stmt = null;
            $this -> database -> closeConnection();

        }catch (PDOException $ex){

            echo "Oops. Chyba pri pripojovaní do databázy.".$ex ->getMessage();
            //error_log($exception->getMessage());
            //TODO: buď to presmerovať na nejaký error page alebo na index
            die();
        }

        return $result;
    }

    /* fetch custom projects (by status) */
    /**
     * @param $projectStatus
     * @param $projectAuthor
     * @return mixed
     */
    public function selectCustomProjects($projectStatus, $projectAuthor){

        try{
            $stmt = $this->runQuery("SELECT * FROM projects LEFT JOIN projects_users ON projects.id = projects_users.project_id WHERE projects_users.user_id = :projectAuthor AND projects.project_status=:projectStatus ORDER BY projects.id DESC");
            $stmt -> execute(array(
                ":projectAuthor" => $projectAuthor,
                ":projectStatus" => $projectStatus
            ));

            $result = $stmt ->fetchAll(PDO::FETCH_ASSOC);
            $stmt = null;
            $this -> database -> closeConnection();

        }catch (PDOException $ex){

            //echo "Oops. Chyba pri pripojovaní do databázy.".$ex ->getMessage();
            error_log($ex->getMessage());
            return false;
        }

        return $result;
    }

    /**
     * @param $projectAuthor
     * @return mixed
     */
    public function selectMyProjects($projectAuthor){
        try{
            $stmt = $this->runQuery("SELECT * FROM projects WHERE project_author = :projectAuthor ORDER BY id DESC");
            $stmt -> execute(array(
                ":projectAuthor" => $projectAuthor
            ));

            $result = $stmt ->fetchAll(PDO::FETCH_ASSOC);
            $stmt = null;
            $this -> database -> closeConnection();

        }catch (PDOException $ex){

            echo "Oops. Chyba pri pripojovaní do databázy.".$ex ->getMessage();
            //error_log($exception->getMessage());
            //TODO: buď to presmerovať na nejaký error page alebo na index
            die();
        }

        return $result;
    }

    public function selectProjectUsers($id){
        try{
            $stmt = $this->runQuery("SELECT * FROM users LEFT JOIN projects_users ON users.id_users = projects_users.user_id WHERE projects_users.project_id = :project_id");
            $stmt->execute(array(
                ":project_id" => $id
            ));

            $result = $stmt ->fetchAll(PDO::FETCH_ASSOC);
            return $result;

        }catch (PDOException $ex){


            error_log($ex->getMessage());
//            echo "Oops. Chyba pri pripojovaní do databázy.".$ex ->getMessage();
            return false;
        }

    }

    public function unassignFromProject($userId, $projectId)
    {
        try{

            $stmt = $this->runQuery("DELETE FROM projects_users WHERE user_id =:user_id AND project_id=:project_id");
            $stmt -> execute(array(
                ":user_id" => $userId,
                ":project_id" => $projectId
            ));

            if($stmt->rowCount() > 0)
            {
                $stmt = null;
                $this -> database -> closeConnection();
                return true;
            }
            else
            {
                return false;
            }

        }catch (PDOException $ex){

            //echo "Oops. Chyba pri pripojovaní do databázy.".$ex ->getMessage();
            error_log($ex->getMessage());
            return false;
        }
    }


}