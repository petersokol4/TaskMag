<?php
/**
 * Copyright (c) 2019. TaskMag project managing web app. Created by Peter Sokol for Diploma Thesis.
 */

require_once (__DIR__."/../../config/helpers/DbConfig.php");
require_once (__DIR__."/../models/Invitation.php");

//PHP Mailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once (__DIR__."/../vendors/phpMailer/src/Exception.php");
require_once (__DIR__."/../vendors/phpMailer/src/PHPMailer.php");
require_once (__DIR__."/../vendors/phpMailer/src/SMTP.php");

class InvitationController
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

    /**
     * Validate email function - if email -> return true else -> return false
     * @param $email
     * @return bool
     */
    public function validateEmail($email) {

        if(filter_var($email, FILTER_VALIDATE_EMAIL)){
            return true;
        }else{
            return false;
        }

    }


    /**
     * Send email through PHPMailer Class.
     *
     * @param $email
     * @param $message
     * @param $subject
     * @param $from
     * @return true
     */

    public function sendEmail($email,$message,$subject, $from)
    {

        $mail = new PHPMailer(true);                    // Passing `true` enables exceptions
        try {
            //Server settings
            $mail->SMTPDebug = 0;                                 // Enable verbose debug output
            $mail->isSMTP();                                      // Set mailer to use SMTP
            $mail->Host = SMTP_HOST;                              // Specify main and backup SMTP servers
            $mail->Port = SMTP_PORT;                              // TCP port to connect to
            $mail->SMTPAuth = true;                               // Enable SMTP authentication
            $mail->Username = SMTP_USERNAME;                      // SMTP username
            $mail->Password = SMTP_PASSWORD;                      // SMTP password
            $mail->SMTPSecure = SMTP_ENCRYPTION;                  // Enable TLS encryption, `ssl` also accepted
            $mail->CharSet = 'UTF-8';
            $mail->setLanguage(MAILER_LANGUAGE, __DIR__.'../vendors/phpMailer/language/');

            //TODO if working on localhost
            $whitelist = array('127.0.0.1', "::1");

            if(in_array($_SERVER['REMOTE_ADDR'], $whitelist)){
                $mail->SMTPOptions = array(
                    'ssl' => array(
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => true
                    )
                );
            }



            //Recipients
            $mail->setFrom($from, MAIL_FROM_NAME);
            $mail->addAddress($email);

            //Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = $subject;
            $mail->Body    = $message;
            $mail->AltBody = strip_tags($message);                      //alternative no HTML

            $mail->send();

            return true;

        } catch (Exception $e) {

            error_log($mail->ErrorInfo);
//            echo "Oops. Mail sa nepodarilo odoslať." .$mail->ErrorInfo;
            return false;
        }

    }

    /**
     * Generate Token function
     * @param $length
     * @return bool|string
     */
    public function generateInvitationToken($length){
        try {

            $code = bin2hex(random_bytes($length));

            return $code;
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    /**
     * Generate idToKey function
     * @return string
     */
    public function generateIdKey(){
        $key = base64_encode($this->lastID());
        return $key;
    }

    /**
     * Last inserted ID function
     * @return mixed
     */
    private function lastID(){
        $stmt = $this->pdo->lastInsertId();
        return $stmt;
    }

    public function setInvitation($email, $projectId, $iToken)
    {
        try {

            $stmt = $this->runQuery("SELECT * FROM invitations WHERE email =:email AND project_id = :project_id");
            $stmt->execute(array(
                ":email" => $email,
                ":project_id" => $projectId
            ));

            if($stmt->rowCount() == 0)
            {

                //todo check if is already in project

                $stmt = $this->runQuery("INSERT INTO invitations (email, project_id, invitation_token, send_at) VALUES (:email, :project_id, :invitation_token, NOW())");
                $stmt->execute(array(
                    ":email" => $email,
                    ":project_id" => $projectId,
                    ":invitation_token" => $iToken
                ));

                $idKey = $this ->generateIdKey();

                $stmt = null;
                $this -> database -> closeConnection();
                return $idKey;
            }
            else
            {
                $stmt = null;
                $this -> database -> closeConnection();
                return false;
            }

        } catch (PDOException $ex) {
            error_log($ex->getMessage());
//            echo "Oops. Registrácia neúspešná kvôli chybe pri pripojovaní do databázy." . $ex->getMessage();
            return false;
        }
    }

    public function checkInvitationCode($i_Id, $i_Token)
    {
        try{
            $stmt = $this->runQuery("SELECT * FROM invitations WHERE id_invitations=:id AND invitation_token = :invitation_token LIMIT 1");
            $stmt->execute(array(
                ":id" => $i_Id,
                ":invitation_token" => $i_Token
            ));
            $userRow=$stmt->fetch(PDO::FETCH_ASSOC);

            if($stmt->rowCount() == 1){

                $i_Creds[0] = $userRow["project_id"];
                $i_Creds[1] = $userRow["email"];

                $stmt = null;
                $this -> database -> closeConnection();
                return $i_Creds;
            }else{

                $stmt = null;
                $this -> database -> closeConnection();
                return false;
            }

        }catch (PDOException $ex) {
            error_log($ex->getMessage());
//            echo "Oops. Neúspech kvôli chybe pri pripojovaní do databázy." . $ex->getMessage();
            return false;
        }

    }

    public function checkRegistration($email){
        try{
            $stmt = $this->runQuery("SELECT * FROM users WHERE user_email=:user_email AND user_confirmed = 1 LIMIT 1");
            $stmt->execute(array(
                ":user_email" => $email
            ));

            $userRow=$stmt->fetch(PDO::FETCH_ASSOC);

            if($stmt->rowCount() == 1){

                $u_Id = $userRow["id_users"];

                $stmt = null;
                $this -> database -> closeConnection();
                return $u_Id;
            }else{

                $stmt = null;
                $this -> database -> closeConnection();
                return false;
            }

        }catch (PDOException $ex) {
            error_log($ex->getMessage());
//            echo "Oops. Neúspech kvôli chybe pri pripojovaní do databázy." . $ex->getMessage();
            return false;
        }
    }

    public function assignUserToProject($u_Id, $p_Id)
    {
        try {
            $stmt = $this->runQuery("INSERT INTO projects_users (project_id, user_id,  user_role, assign_time) VALUES (:project_id, :user_id, 0, NOW())");
            $stmt->execute(array(
                ":project_id" => $p_Id,
                ":user_id" => $u_Id
            ));

            if ($stmt->rowCount() == 1)
            {
                $stmt = null;
                $this -> database -> closeConnection();
                return true;
            }
            else
            {
                $stmt = null;
                $this -> database -> closeConnection();
                return false;
            }

        } catch (PDOException $ex) {
            error_log($ex->getMessage());
//            echo "Oops. Registrácia neúspešná kvôli chybe pri pripojovaní do databázy." . $ex->getMessage();
            return false;
        }
    }

    public function deleteInvitation($i_Id)
    {
        //delete from invitations where token = $tokenCode
        try{
            $stmt = $this->runQuery("DELETE FROM invitations WHERE id_invitations = :id_invitations");
            $stmt->execute(array(
                ":id_invitations" => $i_Id
            ));

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
        }catch (PDOException $ex){

            //echo "Oops. Chyba pri pripojovaní do databázy." .$ex ->getMessage();
            error_log($ex->getMessage());
            return false;
        }
    }

    public function checkIsMember($email, $projectId)
    {
        try{
            $stmt = $this->runQuery("SELECT * FROM users LEFT JOIN projects_users ON users.id_users = projects_users.user_id WHERE users.user_email=:user_email AND users.user_confirmed = 1  AND projects_users.project_id = :project_id LIMIT 1");
            $stmt->execute(array(
                ":user_email" => $email,
                ":project_id" => $projectId
            ));

            if($stmt->rowCount() > 0){

                $stmt = null;
                $this -> database -> closeConnection();
                return true;
            }else{

                $stmt = null;
                $this -> database -> closeConnection();
                return false;
            }

        }catch (PDOException $ex) {
            error_log($ex->getMessage());
//            echo "Oops. Neúspech kvôli chybe pri pripojovaní do databázy." . $ex->getMessage();
            die();
        }
    }

    //todo dať pri catch radšej die()?

}