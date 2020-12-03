<?php
    session_start();
    session_destroy();
    setcookie("error_message", "", time()-3600);
    header('Location: index.php');
    exit();
?>