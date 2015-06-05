<?php

namespace membership;

class User {
    
    public $db;
    
    //Connects to Database
    function __construct()
    {
        $this->db = new \membership\Connect();
        $this->db = $this->db->PDO();
    }
    
    public function Login($email, $pass)
    {
        $pass = hash("sha256", $pass);
        $login = $this->db->prepare("SELECT username, email FROM membership_users WHERE password = ? AND email = ?");
        $login->bindParam(1, $pass);
        $login->bindParam(2, $email);
        $login->execute();
        
        $check = $login->rowCount();
        
        if ($check == 0) {
            $this->showMSG("<span style='color: red;'>Invalid Username OR Password!</span>");
            $this->failedLogin();
            return false;
        }
        
        if ($check > 0) {
            
            $result = $login->fetch(\PDO::FETCH_ASSOC);
            $_SESSION["username"] = $result["username"];
            $_SESSION["email"] = $result["email"];
            
            $this->updateLastActive($email);
            $this->showMSG("Success!<br>\n");

            return true;
        }
    }
    
    public function Register($name, $pass, $pass2, $email, $email2)
    {
        //verify password's match
        if ($pass != $pass2){
            $this->showMSG("<span style='color: red;'>Your passwords do not match!</span>");
            return false;
        }
        
        //verify emails's match
        if ($email != $email2){
            $this->showMSG("<span style='color: red;'>Your emails do not match!</span>");
            return false;
        }
        
        //verify username is not taken
        $verifyUserName = $this->verifyName($name);
        if ($verifyUserName > 0){
            $this->showMSG("<span style='color: red;'>This username is already in use! Please try another.</span>"); 
            return false;
        }
        
        //verify email is not taken
        $verifyEmail = $this->verifyEmail($email);
        if ($verifyEmail > 0){
            $this->showMSG("<span style='color: red;'>This email address is already in use! Please try another.</span>"); 
            return false;
            
        } else {
            $this->insertUser($name, $pass, $email);
            $this->Login($email, $pass);
            
            return true;
        }
    }
        
    private function insertUser($name, $pass, $email)
    {
        $pass = hash("sha256", $pass);
        $date = date("Y-m-d");
        $level = 1;
        $time = time();
        $ip = $_SERVER['REMOTE_ADDR'];
        
        $login = $this->db->prepare("INSERT INTO membership_users (`username`, `password`, `email`, `joindate`, `level`, `lastactive`, `ip`) VALUES (?,?,?,?,?,?,?)");
        $login->bindParam(1, $name);
        $login->bindParam(2, $pass);
        $login->bindParam(3, $email);
        $login->bindParam(4, $date);
        $login->bindParam(5, $level);
        $login->bindParam(6, $time);
        $login->bindParam(7, $ip);
        $login->execute();
            
        return;
    }
    
    private function verifyName($name)
    {
        $login = $this->db->prepare("SELECT * FROM membership_users WHERE username = ?");
        $login->bindParam(1, $name);
        $login->execute();
        
        return $login->rowCount();
    }
    
    private function verifyEmail($email)
    {
        $login = $this->db->prepare("SELECT * FROM membership_users WHERE email = ?");
        $login->bindParam(1, $email);
        $login->execute();
        
        return $login->rowCount();
    }
    
    private function showMSG($msg)
    {
        echo "<b>" . $msg . "</b><br>\n";
    }
    
    public function updateLastActive($email)
    {
        $time = time();
        
        $login = $this->db->prepare("UPDATE membership_users SET `lastactive` = ? WHERE email = ?");
        $login->bindParam(1, $time);
        $login->bindParam(2, $email);
        $login->execute();
            
        return;
    }
    
    
    
    public function ipBanCheck()
    {
        $ip = $_SERVER['REMOTE_ADDR'];
        
        $login = $this->db->prepare("SELECT ip, attempt, time FROM membership_failed_login WHERE ip = ?");
        $login->bindParam(1, $ip);
        $login->execute();
        
        $exists = $login->rowCount();
        
        if ($exists > 0)
        {
            $result = $login->fetch(\PDO::FETCH_ASSOC);
            if (($result["time"]) > (time() - 60*5)){
                if ($result["attempt"] == "5"){
                    return false;   
                } else {
                    return true;
                }
            } else {
                return true;
            }
        }
        
        return true;
    }
    
    
    private function failedLogin()
    {
        $ip = $_SERVER['REMOTE_ADDR'];
        $time = time();
        $attempt = 1;
        
        $login = $this->db->prepare("SELECT ip, attempt, time FROM membership_failed_login WHERE ip = ?");
        $login->bindParam(1, $ip);
        $login->execute();
        
        $exists = $login->rowCount();
        
        if ($exists >0){
            $result = $login->fetch(\PDO::FETCH_ASSOC);
            
            if (($result["time"]) > (time() - 60*5)){
                $attempt = $result["attempt"] + 1;
            } 
            
            $login = $this->db->prepare("UPDATE membership_failed_login SET attempt = ?, time =? WHERE ip = ?");
            $login->bindParam(1, $attempt);
            $login->bindParam(2, $time);
            $login->bindParam(3, $ip);
            $login->execute();
            
        } else {
            $login = $this->db->prepare("INSERT INTO membership_failed_login (`ip`, `attempt`, `time`) VALUES (?,?,?);");
            $login->bindParam(1, $ip);
            $login->bindParam(2, $attempt);
            $login->bindParam(3, $time);
            $login->execute();
        }
    }
    
}


