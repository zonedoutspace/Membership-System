<?php

    include_once "vendor/autoload.php";

    if (isset($_POST["register"])) 
    {
        $user = new membership\User();
        
        $register = $user->Register($_POST["user"], $_POST["pass"], $_POST["pass2"], $_POST["email"], $_POST["email2"]);
        
        if ($register == false) goto RegisterForm;
    }
    
    else 
    {
    RegisterForm:
        
        $html = '<form action="' . $_SERVER["PHP_SELF"] . '" method="post" id="registerForm">' . "\n";
        $html .= "\t" . '<input type="hidden" name="register" value="yes">' . "\n";
        
        $html .= "\t" . '<fieldset style="border: 1px solid black; width: 350px; font-family: Verdana;">' . "\n";
        $html .= "\t\t" . '<legend>User Information</legend>' . "\n";
        
        $html .= "\t\t" . '<table width="100%" style="font-size: 12px; font-family: Verdana;">' . "\n";
        
        $html .= "\t\t\t" . '<tr>' . "\n";
        $html .= "\t\t\t\t" . '<td align="right"><b>User Name:</b></td>' . "\n";
        $html .= "\t\t\t\t" . '<td align="left"><input type="text" name="user" value="';
        if (isset($_POST["user"])) $html .= $_POST["user"]; 
        $html .= '" required></td>' . "\n";
        $html .= "\t\t\t" . '</tr>' . "\n";
        
        $html .= "\t\t\t" . '<tr>' . "\n";
        $html .= "\t\t\t\t" . '<td align="right"><b>Email Address:</b></td>' . "\n";
        $html .= "\t\t\t\t" . '<td align="left"><input type="email" name="email" value="';
        if (isset($_POST["email"])) $html .= $_POST["email"]; 
        $html .= '" required></td>' . "\n";
        $html .= "\t\t\t" . '</tr>' . "\n";

        $html .= "\t\t\t" . '<tr>' . "\n";
        $html .= "\t\t\t\t" . '<td align="right"><b>Re-Enter Email Address:</b></td>' . "\n";
        $html .= "\t\t\t\t" . '<td align="left"><input type="email" name="email2" value="';
        if (isset($_POST["email2"])) $html .= $_POST["email2"]; 
        $html .= '" required></td>' . "\n";
        $html .= "\t\t\t" . '</tr>' . "\n";

        $html .= "\t\t\t" . '<tr>' . "\n";
        $html .= "\t\t\t\t" . '<td align="right"><b>Password:</b></td>' . "\n";
        $html .= "\t\t\t\t" . '<td align="left"><input type="password" name="pass" value="';
        if (isset($_POST["pass"])) $html .= $_POST["pass"]; 
        $html .= '" required></td>' . "\n";
        $html .= "\t\t\t" . '</tr>' . "\n";
      
        $html .= "\t\t\t" . '<tr>' . "\n";
        $html .= "\t\t\t\t" . '<td align="right"><b>Re-Enter Password:</b></td>' . "\n";
        $html .= "\t\t\t\t" . '<td align="left"><input type="password" name="pass2" value="';
        if (isset($_POST["pass2"])) $html .= $_POST["pass2"]; 
        $html .= '"required></td>' . "\n";
        $html .= "\t\t\t" . '</tr>' . "\n";

        $html .= "\t\t" . '</table>' . "\n";
        
        $html .= "\t" . '</fieldset><br>' . "\n";
                
        $html .= "\t" . '<fieldset style="border: 0; width: 350px; font-family: Verdana;">' . "\n";
        $html .= "\t\t" . '<table width="100%" style="font-size: 12px; font-family: Verdana;">' . "\n";
        $html .= "\t\t\t" . '<tr>' . "\n";
        $html .= "\t\t\t\t" . '<td align="center" colspan="2"><input type="submit" value="Register" style="width: 100%; height: 50px;"></td>' . "\n";
        $html .= "\t\t\t" . '</tr>' . "\n";
        $html .= "\t\t" . '</table>' . "\n";
        $html .= "\t" . '</fieldset><br>' . "\n";
        $html .= '</form>' . "\n";
        
        echo $html;
        
    }
