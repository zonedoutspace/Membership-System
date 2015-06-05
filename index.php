<?php

    session_start();
    include_once "vendor/autoload.php";

    $page = new membership\Page(1);

    if ($page->isValid() == true){
        
        echo "Hello " . $_SESSION["username"] . "!<br>\n";
        
    }
