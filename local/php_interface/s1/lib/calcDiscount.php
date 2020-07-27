<?php

    if($_GET["init"] == 1) {
        $_SESSION["init"] = 1;
    }
    if($_GET["init"] == 2) {
        $_SESSION["init"] = 2;
    }

    if ($_SESSION["init"] == 1):
        /*
        if (file_exists($_SERVER["DOCUMENT_ROOT"]."/local/php_interface/s1/lib/calcActionDiscount.php")) {
            require_once($_SERVER["DOCUMENT_ROOT"] . "/local/php_interface/s1/lib/calcActionDiscount.php");
        }
        */


    endif;