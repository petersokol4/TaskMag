<?php

require_once (__DIR__."/../../config/helpers/DbConfig.php");
require_once (__DIR__."/../models/User.php");

//PHP Mailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once (__DIR__."/../vendors/phpMailer/src/Exception.php");
require_once (__DIR__."/../vendors/phpMailer/src/PHPMailer.php");
require_once (__DIR__."/../vendors/phpMailer/src/SMTP.php");

/**
 * Secure login/registration UserController class.
 */

class UserController
{
    /** @var object $pdo Copy of PDO connection */
    private $pdo = null;
    /** @var object $database of class DbConfig */
    private $database = null;



//                    /** BruteForce Firewall parameters */
//                    public $attemps=5; //max počet chybných pokusov prihlásenia
//                    public $blockTime= "5 minutes"; // čas blokovania opätovného prihlásenia


    /** @var int $attemps Number of max. unsuccessful login attemps */
    private $attemps = WRONG_ATTEMPTS;
    private $blockTime =FIREWALL_BLOCK_TIME;

    private $aesKey = AES_KEY;

    //object of the logged in user
    private $user;



    /**
     * UserController constructor.
     * auto-connect to DB on creating object of class UserController
     *
     * assign BruteForce Firewall Parameters
     */
    function __construct()
    {
        $this->database = new DbConfig();
        $this->pdo = $this -> database->dbConnect();

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
     * Redirect function
     * @param $url
     */
    public function redirect($url)
    {
        header("Location: $url");
    }

    /**
     * Check input function
     * @param $data
     * @return string
     */
    public function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
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
     * Check password function
     * check if is 8-20 characters length and contain at min. one upper, one lower case and one digit
     * @param string $data
     * @return bool
     */
    public function checkPassword($data) {
        if(!preg_match("/^.*(?=.{8,20})(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z]).*$/", $data)){
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
     * Last inserted ID function
     * @return mixed
     */
    private function lastID(){
        $stmt = $this->pdo->lastInsertId();
        return $stmt;
    }




    /*
     * BruteForce Firewall Methods ----------------------------------------------------------------
     *
     */

    // update bfFirewall attemps
/*
    private function updateAttemps($email, $ip, $wrongLogin){

        try {
            $pdo = $this->pdo;
            $stmt = $pdo->prepare('UPDATE bfFirewall SET wrongLogin = :wrongLogin_id WHERE email = :email_id AND ipAddress = :ip_id ');
            $stmt->execute(array(":wrongLogin_id"=>$wrongLogin, ":email_id"=>$email, ":ip_id"=>$ip));
        }catch (PDOException $ex){
            //echo $ex->getMessage();
            echo "Oops. len kód chyby??? Chyba pri updatovaní databázy.";
            //TODO: buď to presmerovať na nejaký error page alebo na index
            die();
        }
    }

    // delete from bfFirewall

    private function deleteAttemp($email, $ip){

        try {
            $pdo = $this->pdo;
            $stmt = $pdo->prepare('DELETE from bfFirewall WHERE email = :email_id AND ipAddress = :ip_id ');
            $stmt->execute(array(":email_id"=>$email, ":ip_id"=>$ip));
        }catch (PDOException $ex){
            //echo $ex->getMessage();
            echo "Oops. len kód chyby??? Chyba pri vymazávaní z databázy.";
            //TODO: buď to presmerovať na nejaký error page alebo na index
            die();
        }
    }

    // insert to bfFirewall

    private function insertAttemp($email, $ip, $wrongLogin){

        try {
            $pdo = $this->pdo;
            $stmt = $pdo->prepare('INSERT into bfFirewall(email, wrongLogin, ipAddress) VALUES (:email_id, :wrongLogin_id, :ip_id)');
            $stmt->execute(array(":email_id"=>$email, ":wrongLogin_id"=>$wrongLogin,":ip_id"=>$ip));
        }catch (PDOException $ex){
            //echo $ex->getMessage();
            echo "Oops. len kód chyby??? Chyba pri pridávaní do databázy.".$ex->getMessage();
            //TODO: buď to presmerovať na nejaký error page alebo na index
            die();
        }
    }

*/





    /*
     *
     * MAIN METHODS
     *
     */

    /**
     * Change file name function
     *
     *
     */

    public function generateName(){
        $fileName = bin2hex((new \DateTime())->setTimezone(new DateTimeZone('Europe/Prague'))->format('YmdHism'));
        return $fileName;
    }

    public function changeAvatar($newFileName, $id){
        try{
            $stmt = $this->runQuery("UPDATE users SET user_avatar=:user_avatar WHERE id_users= :id");
            $stmt->execute(array(
                ":user_avatar" => $newFileName,
                ":id" => $id
            ));
            return true;
        }catch(PDOException $e){
            error_log($e->getMessage());
            return false;
        }
    }


    /* fetch only one project by id */
    /**
     * @param $id
     * @return mixed
     */
    public function selectProfile($id){
        try{
            $stmt = $this->runQuery("SELECT * FROM users WHERE id_users = :id LIMIT 1");
            $stmt->execute(array(
                ":id" => $id
            ));
            $result = $stmt ->fetchAll(PDO::FETCH_ASSOC);

        }catch (PDOException $ex){

            //echo "Oops. Chyba pri pripojovaní do databázy.".$ex ->getMessage();
            error_log($ex->getMessage());
            die();
        }

       return $result;
    }

    public function assignTime($userId){
        try{
            $stmt = $this->runQuery("SELECT * FROM projects_users WHERE user_id = :user_id LIMIT 1");
            $stmt->execute(array(
                ":user_id" => $userId
            ));
            $result = $stmt ->fetch(PDO::FETCH_ASSOC);

        }catch (PDOException $ex){

            //echo "Oops. Chyba pri pripojovaní do databázy.".$ex ->getMessage();
            error_log($ex->getMessage());
            die();
        }

       return $result;
    }

    public function editProfile($id, $userName, $userAbout){
        try{
            $stmt = $this->runQuery("UPDATE users SET user_name = :userName, user_about = :userAbout WHERE id_users = :id");
            $stmt->execute(array(
                ":userName"=> $userName,
                ":userAbout"=>$userAbout,
                ":id"=>$id
            ));
            return true;

        }catch (PDOException $ex){

            //echo "Oops. Chyba pri pripojovaní do databázy.".$ex ->getMessage();
            error_log($ex->getMessage());
            die();
        }
    }


    /**
     * Generate Token function
     * @param $length
     * @return bool|string
     */
    public function generateToken($length){
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
    private function generateIdKey(){
        $key = base64_encode($this->lastID());
        return $key;
    }


    /**
     * Register a new user account function
     * @param $userName
     * @param $userEmail
     * @param $userPass
     * @param $userToken
     * @return bool|string
     */
    public function registration($userName, $userEmail, $userPass, $userToken)
    {
        try {
            $stmt = $this->runQuery("INSERT INTO users (user_name, user_email, user_pass, user_created, user_activate_token, user_activate_token_created) VALUES (:user_name, :user_email, AES_ENCRYPT(:user_pass, :aesKey), NOW(), :token_code, NOW())");
            $stmt->execute(array(
                ":user_name" => $userName,
                ":user_email" => $userEmail,
                ":user_pass" => $userPass,
                ":aesKey" => $this->aesKey,
                ":token_code" => $userToken
            ));

            $idKey = $this ->generateIdKey();


            return $idKey;

        } catch (PDOException $ex) {
            error_log($ex->getMessage());
//            echo "Oops. Registrácia neúspešná kvôli chybe pri pripojovaní do databázy." . $ex->getMessage();
            return false;
        }
    }


    /**
     * Forgot Pasword function
     * @param $userEmail
     * @param $passwordToken
     * @return bool|string
     */
    public function forgotPassword($userEmail,$passwordToken)
    {
        try {
            $stmt = $this->runQuery("SELECT id_users FROM users WHERE user_email=:email LIMIT 1");
            $stmt->execute(array(":email"=>$userEmail));
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if($stmt->rowCount() == 1) {
                $idKey = base64_encode($row["id_users"]);
                $stmt = $this->runQuery("UPDATE users SET user_pass_token= :token_code, user_pass_token_created= NOW() WHERE user_email= :user_email AND user_confirmed = 1 ");
                $stmt->execute(array(
                    ":user_email" => $userEmail,
                    ":token_code" => $passwordToken
                ));
                return $idKey;
            }else{
                return false;
            }
        } catch (PDOException $ex) {
            error_log($ex->getMessage());
//            echo "Oops. Registrácia neúspešná kvôli chybe pri pripojovaní do databázy." . $ex->getMessage();
            return false;
        }
    }


    /**
     *Select user info
     * @param $id
     * @return mixed
     */
    public function selectUser($id){
        try{
            $stmt = $this->runQuery("SELECT * FROM users WHERE id_users = :id LIMIT 1");
            $stmt->execute(array(
                ":id" => $id
            ));

            $result = $stmt ->fetchAll(PDO::FETCH_ASSOC);



        }catch (PDOException $ex){


            error_log($ex->getMessage());
//            echo "Oops. Chyba pri pripojovaní do databázy.".$ex ->getMessage();
            return false;
        }

        //return result if not empty
        if (! empty($result)) {
            return $result;
        }else{
            return false;
        }
    }



    /**
     * User information update function
     * @param int $id User id.
     * @param string $userName User name.
     * @param string $userAvatar User avatar.
     * @return boolean of success.
     */
    public function userUpdate($id, $userName, $userAvatar){
        try {
            $stmt = $this->runQuery("UPDATE users SET user_name = :user_name, user_avatar = :user_avatar WHERE id_users = :id");
            $stmt->execute(array(
                ":user_name" => $userName,
                ":user_avatar" => $userAvatar,
                ":id" => $id
            ));


            return true;

        } catch (PDOException $ex) {
            error_log($ex->getMessage());
//            echo "Oops. Úprava údajov neúspešná kvôli chybe pri pripojovaní do databázy." . $ex->getMessage();
            return false;
        }
    }


    /**
     * Login user function
     * @param $userEmail
     * @param $userPass
     * @param $ip
     * @return bool
     */
    public function login($userEmail, $userPass, $ip)
    {
        $ip = inet_pton($ip);
        try
        {
            //kontrola emailu, hesla a verifikácie
            $stmt = $this->runQuery("SELECT * FROM users WHERE user_email=:user_email AND user_pass=AES_ENCRYPT(:user_pass, :aesKey) AND user_confirmed = 1 limit 1 ");
            $stmt->execute(array(
                ":user_email"=>$userEmail,
                ":user_pass" =>$userPass,
                ":aesKey" => $this->aesKey
            ));

            $userRow=$stmt->fetch(PDO::FETCH_ASSOC);

            //if correct email, password and account is activated -> successful login
            if($stmt->rowCount() == 1)
            {

                    //TODO formát pole na OBJECT?
                    $this->user = $userRow;

                    //regenerate session
                    session_regenerate_id(true);

                    //save user info to session
                    $_SESSION['user']['id'] = $userRow['id_users'];
                    $_SESSION['user']['name'] = $userRow['user_name'];
                    $_SESSION['user']['avatar'] = $userRow['user_avatar'];
                    $_SESSION['user']['email'] = $userRow['user_email'];
                    $_SESSION['user']['user_type'] = $userRow['user_type'];

                    $stmt = $this->runQuery("SELECT * FROM timers WHERE created_by = :created_by AND timer_finished = 0 LIMIT 1");
                    $stmt->execute(array(
                        ":created_by" => $userRow['id_users']
                    ));

                    $result = $stmt ->fetch(PDO::FETCH_ASSOC);

                    if($stmt->rowCount() > 0)
                    {
                        $_SESSION["timer"]["project"] = $this->sanitizeNumber($result["project_id"]);
                        $_SESSION["timer"]["id"] = $this->sanitizeNumber($result["id_timers"]);
                        $_SESSION["timer"]["start"] = (new DateTime($this->sanitizeNumber($result["timer_start"])))->getTimestamp();
                    }


                return true;


            }
            //if wrong email or password or unactivated account
            else
            {

                return false;
            }
        }
        catch(PDOException $ex)
        {
            error_log($ex->getMessage());
//            echo "Oops. len kód chyby???Prihlásenie neúspešné, kvôli chybe s databázou.3".$ex->getMessage();
            return false;
        }
    }


    // Check if logged in function

    public function isLoggedIn()
    {
        if(isset($_SESSION['user']))
        {
            return true;
        }else{
            return false;
        }
    }


    /**
     * Logout the user and regenerate & unset & destroy session.
     *
     * @return true
     */
    public function logout()
    {
        session_regenerate_id(true);
        $_SESSION = array();
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();
        return true;
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
     * Activate a login by a confirmation code and login function
     * @param $id
     * @param $userToken
     * @return boolean of success.
     */
    public function emailActivation($id, $userToken){
        try{
            $stmt = $this->runQuery("SELECT user_activate_token_created FROM users WHERE id_users= :id AND user_confirmed = 0 AND user_activate_token= :user_activate_token");
            $stmt->execute(array(
                ":id" => $id,
                ":user_activate_token" =>$userToken
            ));

            //time of creation user_token
            $userRow=$stmt->fetch(PDO::FETCH_ASSOC);

            //if exists and not activated
            if($stmt->rowCount()>0){


                //TODO ako metodu private
                //key lifetime
                $keyLife = ACCOUNT_ACTIVATION_KEY_LIFE;
                //token created time
                $dateTime = new DateTime($userRow["user_activate_token_created"]);
                $dateTime->modify("+{$keyLife} minutes");
                $dateTime = $dateTime->format('Y-m-d H:i:s');
                //now
                $today = (new DateTime())->format('Y-m-d H:i:s');

                //if token code is valid
                if($dateTime >= $today){

                    $stmt = $this->runQuery("UPDATE users SET user_confirmed = 1 WHERE id_users = :id");
                    $stmt->execute(array(
                        ":id" => $id
                    ));
                    return true;

                }else{

                    return false;
                }

            }else{

                return false;
            }
    }catch (PDOException $ex) {
        error_log($ex->getMessage());
//            echo "Oops. Neúspech kvôli chybe pri pripojovaní do databázy." . $ex->getMessage();
        return false;
    }
    }



    /**
     * Check if email is already used function
     * @param string $email User email.
     * @return boolean of success.
     */
    public function checkEmailExists($email){
        try{
            $stmt = $this->runQuery("SELECT * FROM users WHERE user_email=:user_email LIMIT 1");
            $stmt->execute(array(
                ":user_email" => $email
            ));

            if($stmt->rowCount() > 0){
                return true;

            }else{
                return false;
            }

        }catch (PDOException $ex) {
            error_log($ex->getMessage());
//            echo "Oops. Neúspech kvôli chybe pri pripojovaní do databázy." . $ex->getMessage();
            return false;
        }
    }


    /**
     * ResetPassword function
     * @param $id
     * @param $passwordToken
     * @param $newPass
     * @return boolean of success.
     */
    public function resetPassword($id, $passwordToken, $newPass){
        try{
            $stmt = $this->runQuery("SELECT * FROM users WHERE id_users=:id AND user_pass_token=:password_token LIMIT 1");
            $stmt->execute(array(
                ":id" => $id,
                ":password_token" => $passwordToken
            ));
            $userRow=$stmt->fetch(PDO::FETCH_ASSOC);
//
            if($stmt->rowCount() == 1){

                //TODO ako metodu private
                //key lifetime
                $keyLife = PASSWORD_RESET_KEY_LIFE;
                //token created time
                $dateTime = new DateTime($userRow["user_pass_token_created"]);
                $dateTime->modify("+{$keyLife} minutes");
                $dateTime = $dateTime->format('Y-m-d H:i:s');
                //now
                $today = (new DateTime())->format('Y-m-d H:i:s');

                //if token code is valid
                if($dateTime >= $today){
                    $stmt = $this->runQuery("UPDATE users SET user_pass=AES_ENCRYPT(:new_pass, :aesKey), user_pass_changed= NOW(), user_pass_token=0 WHERE id_users=:id");
                    $stmt->execute(array(
                        ":new_pass" => $newPass,
                        ":aesKey" => $this->aesKey,
                        ":id" => $id
                    ));
                    return true;
                }else{
                    return false;
                }

            }else{

                return false;
            }

        }catch (PDOException $ex) {
            error_log($ex->getMessage());
//            echo "Oops. Neúspech kvôli chybe pri pripojovaní do databázy." . $ex->getMessage();
            return false;
        }
    }

    /**
     * Correct format function
     * if no result FetchColumn == false -> format to 0
     *
     * @param mixed $null
     * @return int
     */
    private function formatToZero($null){
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

    public function selectGraphData($p_Id){
        try{
            $stmt = $this->runQuery("SELECT COUNT(*) FROM projects_users WHERE project_id = :project_id");
            $stmt -> execute(array(
                ":project_id" => $p_Id
            ));
            $allUsers = $this->formatToZero((int)$stmt->fetchColumn());

            $stmt = $this->runQuery("SELECT COUNT(*) FROM projects_users LEFT JOIN tasks_users ON projects_users.user_id = tasks_users.user_id WHERE tasks_users.user_id IS NULL AND projects_users.project_id =:project_id");
            $stmt -> execute(array(
                ":project_id" => $p_Id
            ));
            $noAssigned = $this->formatToZero((int)$stmt->fetchColumn());
            //error_log($noAssigned);

            $stmt = $this->runQuery("SELECT COUNT(*) FROM invitations WHERE project_id = :project_id");
            $stmt -> execute(array(
                ":project_id" => $p_Id
            ));
            $invited = $this->formatToZero((int)$stmt->fetchColumn());

            $result=["noAssigned" => $noAssigned, "all"=>$allUsers, "invited" => $invited];
            return $result;

        }catch (PDOException $ex){

            //echo "Oops. Chyba pri pripojovaní do databázy." .$ex ->getMessage();
            error_log($ex->getMessage());
            return false;
        }
    }

    public function selectAllMembers($projectId)
    {
        try{
            $stmt = $this->runQuery("SELECT * FROM users LEFT JOIN projects_users ON users.id_users = projects_users.user_id WHERE projects_users.project_id = :project_id ORDER BY projects_users.id_pu DESC");
            $stmt -> execute(array(
                ":project_id" => $projectId
            ));

            $result = $stmt ->fetchAll(PDO::FETCH_ASSOC);
            return $result;

        }catch (PDOException $ex){

            //echo "Oops. Chyba pri pripojovaní do databázy.".$ex ->getMessage();
            error_log($ex->getMessage());
            return false;
        }
    }

    public function selectInvitedMemebers($projectId)
    {
        try{
            $stmt = $this->runQuery("SELECT * FROM users LEFT JOIN invitations ON users.user_email = invitations.email WHERE invitations.project_id = :project_id ORDER BY invitations.id_invitations DESC");
            $stmt -> execute(array(
                ":project_id" => $projectId
            ));

            $result = $stmt ->fetchAll(PDO::FETCH_ASSOC);
            return $result;

        }catch (PDOException $ex){

            //echo "Oops. Chyba pri pripojovaní do databázy.".$ex ->getMessage();
            error_log($ex->getMessage());
            return false;
        }
    }


    public function selectNotAssignedMembers($projectId)
    {
        try{

            $stmt = $this->runQuery("SELECT users.* FROM users LEFT JOIN tasks_users ON users.id_users = tasks_users.user_id LEFT JOIN projects_users ON users.id_users = projects_users.user_id WHERE tasks_users.user_id IS NULL AND projects_users.project_id =:project_id");
            $stmt -> execute(array(
                ":project_id" => $projectId
            ));

            $result = $stmt ->fetchAll(PDO::FETCH_ASSOC);
            return $result;

        }catch (PDOException $ex){

            //echo "Oops. Chyba pri pripojovaní do databázy.".$ex ->getMessage();
            error_log($ex->getMessage());
            return false;
        }
    }

    //todo
//    public function selectNotAssignedTaskMembers($projectId, $taskId)
//    {
//        try{
//
//            $stmt = $this->runQuery("SELECT * FROM users LEFT JOIN tasks_users ON users.id_users = tasks_users.user_id LEFT JOIN projects_users ON users.id_users = projects_users.user_id WHERE NOT tasks_users.task_id=:task_id OR tasks_users.user_id IS NULL AND projects_users.project_id =:project_id");
//            $stmt -> execute(array(
//                ":project_id" => $projectId,
//                ":task_id" => $taskId
//            ));
//
//            $result = $stmt ->fetchAll(PDO::FETCH_ASSOC);
//            return $result;
//
//        }catch (PDOException $ex){
//
//            //echo "Oops. Chyba pri pripojovaní do databázy.".$ex ->getMessage();
//            error_log($ex->getMessage());
//            return false;
//        }
//    }

    public function selectTaskMembers($taskId)
    {
        try{

            $stmt = $this->runQuery("SELECT * FROM users LEFT JOIN tasks_users ON users.id_users = tasks_users.user_id WHERE tasks_users.task_id =:task_id");
            $stmt -> execute(array(
                ":task_id" => $taskId
            ));

            $result = $stmt ->fetchAll(PDO::FETCH_ASSOC);
            return $result;

        }catch (PDOException $ex){

            //echo "Oops. Chyba pri pripojovaní do databázy.".$ex ->getMessage();
            error_log($ex->getMessage());
            return false;
        }
    }
}