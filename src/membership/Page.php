<?php

namespace membership;

class Page {
    
    private $db;
    private $user;
    private $valid;
    
    function __construct($level)
    {
        $this->db = new \membership\Connect();
        $this->db = $this->db->PDO();
        
        $this->user = new User();
        
        $this->Display($level);
    }
    
    private function Display($level)
    {
        global $_SESSION;
        
        if (!isset($_SESSION["username"])) 
        {
            echo $this->showLoginPage();
            $this->valid = false;
        } 
        else
        {
            $this->user->updateLastActive($_SESSION["email"]);

            $check = $this->CheckLevel($level);
            
            if ($check == true) $this->valid = true;
            if ($check == false) $this->valid = false;
        }
    }
    
    private function CheckLevel($level)
    {
        $check = $this->db->prepare("SELECT level FROM membership_users WHERE email = ?");
        $check->bindParam(1, $_SESSION["email"]);
        $check->execute();
        $result = $check->fetch(\PDO::FETCH_ASSOC);  
        
        if ($result["level"] < $level){
            echo "You do not have permission to view this page!";
            return false;
        } else {
            return true;
        }
    }
    
    public function isValid()
    {
        return $this->valid;
    }
    
    public function showLoginPage()
    {
        global $_POST;
               
        if (isset($_POST["posted"])){
            
            $email = $_POST["email"];
            $pass = $_POST["pass"];
            $correct = $this->user->Login($email, $pass);
            if ($correct == true){
                $html = "<a href='index.php'>Go Home</a><br>\n";
            } else {
                goto LoginForm;
            }
            
        } else {
            LoginForm: 
            $ipban = $this->user->ipBanCheck();
            if ($ipban == true){
                $html = '<form action="' . $_SERVER["PHP_SELF"] . '" method="post" id="MainLoginBox">' . "\n";
                $html .= '<input type="hidden" name="posted" value="yes">' . "\n";
                $html .= '<b>Email:</b> <input type="email" name="email" required>' . "\n";
                $html .= '<b>Password:</b> <input type="password" name="pass" required>' . "\n";
                $html .= '<input type="submit" value="Login">' . "\n";
                $html .= '</form>' . "\n";                
            } else {
                $html = '<span style="color: red; font-weight: bold;">You have reached the max login attempts! Try again at a later time.</span>' . "\n";
            }
        }
        
        return $html;
    }
    
}
