<?php

    session_start();
    include_once "vendor/autoload.php";

    $page = new membership\Page(1);

    if ($page->isValid() == true){
        header('Location: index.php');
        exit();
    }
