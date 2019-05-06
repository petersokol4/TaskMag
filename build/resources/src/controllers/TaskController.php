<?php
/**
 * Copyright (c) 2019. TaskMag project managing web app. Created by Peter Sokol for Diploma Thesis.
 */

require_once (__DIR__."/../../config/helpers/DbConfig.php");
require_once (__DIR__."/../models/Task.php");

class TaskController
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

    public function createTask($taskName, $taskDescription, $taskPriority, $taskDueDate, $projectId, $userId, $columnId){
        try{

            $stmt = $this->runQuery("INSERT INTO tasks (task_name, task_description, task_created, task_created_by, task_due_date, task_priority, project_id, column_id) VALUES (:task_name, :task_description, NOW(), :task_created_by, :task_due_date, :task_priority, :project_id, :column_id)");
            $stmt->execute(array(
                ":task_name"=>$taskName,
                ":task_description"=>$taskDescription,
                ":task_created_by"=>$userId,
                ":task_due_date"=>$taskDueDate,
                ":task_priority"=>$taskPriority,
                ":project_id"=>$projectId,
                ":column_id"=>$columnId
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

    public function editTask($id, $taskName, $taskDescription, $taskPriority, $taskDueDate){
        try{
            $stmt = $this->runQuery("UPDATE tasks SET task_name = :task_name, task_description = :task_description, task_due_date = :task_due_date, task_priority = :task_priority WHERE id_tasks = :id_tasks");
            $stmt->execute(array(
                ":task_name"=>$taskName,
                ":task_description"=>$taskDescription,
                ":task_due_date"=>$taskDueDate,
                ":task_priority"=>$taskPriority,
                ":id_tasks"=>$id
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


    public function selectSingleTask($id){
        try{
            $stmt = $this->runQuery("SELECT * FROM tasks WHERE id_tasks = :id LIMIT 1");
            $stmt->execute(array(
                ":id" => $id
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


    public function selectGraphData($p_Id){
        try{
            $stmt = $this->runQuery("SELECT COUNT(*) FROM tasks WHERE task_status = 0 AND project_id =:project_id");
            $stmt -> execute(array(
                ":project_id" => $p_Id
            ));
            $open = $this->formatToZero((int)$stmt->fetchColumn());

            $stmt = $this->runQuery("SELECT COUNT(*) FROM tasks WHERE task_status = 1 AND project_id =:project_id");
            $stmt -> execute(array(
                ":project_id" => $p_Id
            ));
            $done = $this->formatToZero((int)$stmt->fetchColumn());

            $stmt = null;
            $this -> database -> closeConnection();

            $result=["open"=>$open, "done" => $done];
            return $result;



        }catch (PDOException $ex){

            //echo "Oops. Chyba pri pripojovaní do databázy." .$ex ->getMessage();
            error_log($ex->getMessage());
            return false;
        }
    }

    public function selectCycleTime($p_Id){
        try{

            $stmt = $this->runQuery("SELECT COUNT(*) FROM tasks WHERE task_started IS NOT NULL AND task_completed IS NOT NULL AND project_id=:project_id");
            $stmt -> execute(array(
                ":project_id" => $p_Id
            ));
            $all = $this->formatToZero((int)$stmt->fetchColumn());


            $stmt = $this->runQuery("SELECT * FROM tasks WHERE task_started IS NOT NULL AND task_completed IS NOT NULL AND project_id=:project_id");
            $stmt -> execute(array(
                ":project_id" => $p_Id
            ));
            $result = $stmt ->fetchAll(PDO::FETCH_ASSOC);

            $stmt = null;
            $this -> database -> closeConnection();

            $result=["all"=>$all, "cycleTime" => $result];
            return $result;



        }catch (PDOException $ex){

            //echo "Oops. Chyba pri pripojovaní do databázy." .$ex ->getMessage();
            error_log($ex->getMessage());
            return false;
        }
    }


    public function selectMemberGraphData($p_Id, $u_Id){
        try{
            $stmt = $this->runQuery("SELECT COUNT(*) FROM tasks LEFT JOIN tasks_users ON tasks.id_tasks = tasks_users.task_id WHERE tasks.task_status = 0 AND tasks.project_id =:project_id AND tasks_users.user_id = :task_created_by");
            $stmt -> execute(array(
                ":project_id" => $p_Id,
                ":task_created_by" => $u_Id
            ));
            $open = $this->formatToZero((int)$stmt->fetchColumn());

            $stmt = $this->runQuery("SELECT COUNT(*) FROM tasks LEFT JOIN tasks_users ON tasks.id_tasks = tasks_users.task_id WHERE tasks.task_status = 1 AND tasks.project_id =:project_id AND tasks_users.user_id = :task_created_by");
            $stmt -> execute(array(
                ":project_id" => $p_Id,
                ":task_created_by" => $u_Id
            ));
            $done = $this->formatToZero((int)$stmt->fetchColumn());

            $stmt = $this->runQuery("SELECT COUNT(*) FROM tasks WHERE task_status = 1 AND project_id =:project_id");
            $stmt -> execute(array(
                ":project_id" => $p_Id
            ));
            $doneAll = $this->formatToZero((int)$stmt->fetchColumn());

            $stmt = null;
            $this -> database -> closeConnection();

            $result=["open"=>$open, "done" => $done, "doneAll" => $doneAll];
            return $result;



        }catch (PDOException $ex){

            //echo "Oops. Chyba pri pripojovaní do databázy." .$ex ->getMessage();
            error_log($ex->getMessage());
            return false;
        }
    }



    public function completeTask($taskId){
        try{

            $today = (new DateTime())->format('Y-m-d H:i:s');

            $stmt = $this->runQuery("UPDATE tasks SET task_completed = :task_completed, task_status = 1 WHERE id_tasks = :id_tasks AND task_status = 0");
            $stmt->execute(array(
                ":task_completed"=>$today,
                ":id_tasks"=>$taskId
            ));
            if($stmt->rowCount() == 1)
            {
                $stmt = null;
                $this -> database -> closeConnection();

                return $today;
            }
            else
            {
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

    public function startTask($taskId){
        try{

            $today = (new DateTime())->format('Y-m-d H:i:s');

            $stmt = $this->runQuery("UPDATE tasks SET task_started = :task_started WHERE id_tasks = :id_tasks AND task_started IS NULL");
            $stmt->execute(array(
                ":task_started"=>$today,
                ":id_tasks"=>$taskId
            ));
            if($stmt->rowCount() == 1)
            {
                $stmt = null;
                $this -> database -> closeConnection();

                return $today;
            }
            else
            {
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

//    public function selectProjectAuthor($projectId){
//        try{
//            $stmt = $this->runQuery("SELECT project_author FROM projects WHERE id = :id LIMIT 1");
//            $stmt->execute(array(
//                ":id" => $projectId
//            ));
//            $result = $stmt ->fetchAll(PDO::FETCH_ASSOC);
//
//            $stmt = null;
//            $this -> database -> closeConnection();
//
//            return $result[0]["project_author"];
//
//        }catch (PDOException $ex){
//
//            //echo "Oops. Chyba pri pripojovaní do databázy." .$ex ->getMessage();
//            error_log($ex->getMessage());
//            return false;
//        }
//    }

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


    public function deleteTask($id, $userId){

        try{
            $stmt = $this->runQuery("DELETE FROM tasks WHERE id_tasks = :id AND task_created_by =:task_created_by ");
            $stmt->execute(array(
                ":id" => $id,
                ":task_created_by" => $userId
            ));

            //ak sa niečo vymazalo
            if($stmt->rowCount() == 1)
            {
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

    //todo ?
    public function deleteTaskAdmin($id){
        try{
            $stmt = $this->runQuery("DELETE FROM tasks WHERE id_tasks = :id");
            $stmt->execute(array(
                ":id" => $id
            ));

            if($stmt->rowCount() == 1)
            {
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

    /**
     * Last inserted ID function
     * @return mixed
     */
    private function lastID(){
        $stmt = $this->pdo->lastInsertId();
        return $stmt;
    }

    /* TASK CONTROLLER */

    public function startTimer($projectId, $userId)
    {
        try{

            $stmt = $this->runQuery("SELECT * FROM timers WHERE created_by = :created_by AND timer_finished = 0 LIMIT 1");
            $stmt->execute(array(
                ":created_by" => $userId
            ));

            if($stmt->rowCount() > 0)
            {
                return false;
            }

            $stmt = $this->runQuery("INSERT INTO timers (project_id, timer_start, created_by) VALUES (:project_id, NOW(), :created_by)");
            $stmt->execute(array(
                ":project_id"=>$projectId,
                ":created_by"=>$userId
            ));

            $id = $this->lastID();

            $stmt = $this->runQuery("SELECT * FROM  timers WHERE id_timers = :id_timers LIMIT 1");
            $stmt->execute(array(
                ":id_timers"=>$id
            ));
            $result = $stmt ->fetch(PDO::FETCH_ASSOC);
            $arr = array($result, $id);

            $stmt = null;
            $this -> database -> closeConnection();

            return $arr;

        }catch (Exception $ex){

            //echo "Oops. Chyba pri pripojovaní do databázy." .$ex ->getMessage();
            error_log($ex->getMessage());
            return false;
        }
    }

    public function stopTimer($id, $timerDescription)
    {
         try{
                $stmt = $this->runQuery("UPDATE timers SET timer_stop = NOW(), timer_finished = 1, timer_description = :timer_description  WHERE id_timers = :id_timers LIMIT 1");
                $stmt->execute(array(
                    ":timer_description"=>$timerDescription,
                    ":id_timers"=>$id
                ));

             if($stmt->rowCount() > 0)
             {

                 unset($_SESSION["timer"]["project"]);
                 unset($_SESSION["timer"]["id"]);
                 unset($_SESSION["timer"]["start"]);

                 $stmt = null;
                 $this -> database -> closeConnection();
                 return true;

             }else
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


    public function selectAllTimers($projectId)
    {
        try{
            $stmt = $this->runQuery("SELECT timers.*, users.user_name, users.user_avatar FROM timers INNER JOIN users ON timers.created_by = users.id_users WHERE timers.project_id = :project_id AND timers.timer_finished= 1 ORDER BY timers.id_timers DESC");
            $stmt -> execute(array(
                ":project_id" => $projectId
            ));

            $result = $stmt ->fetchAll(PDO::FETCH_ASSOC);
            $stmt = null;
            $this -> database -> closeConnection();
            return $result;

        }catch (PDOException $ex){

            //echo "Oops. Chyba pri pripojovaní do databázy." .$ex ->getMessage();
            error_log($ex->getMessage());
            return false;
        }
    }

    public function selectMyTimers($projectId, $userId)
    {
        try{
            $stmt = $this->runQuery("SELECT * FROM timers WHERE project_id = :project_id  AND created_by= :created_by AND timer_finished= 1 ORDER BY id_timers DESC");
            $stmt -> execute(array(
                ":project_id" => $projectId,
                ":created_by" => $userId
            ));

            $result = $stmt ->fetchAll(PDO::FETCH_ASSOC);
            $stmt = null;
            $this -> database -> closeConnection();
            return $result;

        }catch (PDOException $ex){

            //echo "Oops. Chyba pri pripojovaní do databázy." .$ex ->getMessage();
            error_log($ex->getMessage());
            return false;
        }
    }

    public function checkTimer($userId)
    {
        try{
            $stmt = $this->runQuery("SELECT * FROM timers WHERE created_by = :created_by AND timer_finished= 0 LIMIT 1");
            $stmt -> execute(array(
                ":created_by" => $userId
            ));

            $result = $stmt ->fetch(PDO::FETCH_ASSOC);

            if($stmt->rowCount() > 0)
            {
                $stmt = null;
                $this -> database -> closeConnection();
                return $result;

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

    public function assignTask($taskId, $userId)
    {

        try{

            $stmt = $this->runQuery("INSERT INTO tasks_users (task_id, user_id, user_role, assign_time) VALUES (:task_id, :user_id, :user_role, NOW())");
            $stmt->execute(array(
                ":task_id"=>$taskId,
                ":user_id"=>$userId,
                ":user_role"=> 0
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

    public function unassignFromTask($userId, $taskId)
    {
        try{

            $stmt = $this->runQuery("DELETE FROM tasks_users WHERE user_id =:user_id AND task_id=:task_id");
            $stmt -> execute(array(
                ":user_id" => $userId,
                ":task_id" => $taskId
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

    public function parseTime($his)
    {
        $hours =  floor($his / 3600);
        $minutes = floor($his / 60) % 60;
        $seconds = $his % 60;

        if($hours < 10)
        {
            $hours = "0".$hours;
        }
        if($minutes < 10)
        {
            $minutes = "0".$minutes;
        }
        if($seconds < 10)
        {
            $seconds = "0".$seconds;
        }

        return $hours." hod ".$minutes." min ".$seconds." sek ";

    }

//    TASK COLUMN

    public function selectColumnTasks($c_Id, $p_Id)
    {
        try{
            $stmt = $this->runQuery("SELECT * FROM tasks WHERE column_id =:column_id AND project_id = :project_id ORDER BY task_priority DESC, id_tasks DESC");
            $stmt -> execute(array(
                ":column_id" => $c_Id,
                ":project_id" => $p_Id
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

    public function editTaskColumn($taskId, $columnId){
        try{
            $stmt = $this->runQuery("UPDATE tasks SET column_id = :column_id WHERE id_tasks = :id_tasks");
            $stmt->execute(array(
                ":column_id"=>$columnId,
                ":id_tasks"=>$taskId
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

    //TODO da sa to aj cez veľkosť poľa? ALE bude to treba updatovať cez AJAX
    public function selectColumnTasksCount($columnId)
    {
        try{
            $stmt = $this->runQuery("SELECT COUNT(*) FROM tasks WHERE column_id =:column_id");
            $stmt -> execute(array(
                ":column_id" => $columnId
            ));

            $result = $stmt ->fetchColumn();
            $stmt = null;
            $this -> database -> closeConnection();

        }catch (PDOException $ex){

            //echo "Oops. Chyba pri pripojovaní do databázy." .$ex ->getMessage();
            error_log($ex->getMessage());
            return false;
        }



            return $result;

    }

    public function selectProjectColumns($projectId)
    {
        try{
            $stmt = $this->runQuery("SELECT * FROM columns WHERE project_id =:project_id ORDER BY id_columns ASC LIMIT 1");
            $stmt -> execute(array(
                ":project_id" => $projectId
            ));

            if($stmt->rowCount() == 1)
            {
                $result = $stmt ->fetch(PDO::FETCH_ASSOC);
                $stmt = null;
                $this -> database -> closeConnection();
                $columnId = $result["id_columns"];
                return $columnId;

            }
            else{

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

    public function deleteColumnTasks($c_Id)
    {
        try{
            $stmt = $this->runQuery("DELETE FROM tasks WHERE column_id = :id");
            $stmt->execute(array(
                ":id" => $c_Id
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

    public function checkAssignMemberTask($userId, $taskId)
    {
        try{

            $stmt = $this->runQuery("SELECT * FROM tasks_users WHERE task_id =:task_id AND user_id=:user_id");
            $stmt -> execute(array(
                ":task_id" => $taskId,
                ":user_id" => $userId
            ));

            if($stmt->rowCount() > 0){
                return true;

            }else{
                return false;
            }

        }catch (PDOException $ex){

            //echo "Oops. Chyba pri pripojovaní do databázy.".$ex ->getMessage();
            error_log($ex->getMessage());
            return false;
        }
    }



}