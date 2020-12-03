<?php
require 'includes/functions.php';

session_start();
if (!isset($_SESSION['loggedFlag'])) {
    header('Location: index.php');
    exit();
}

$user = $_SESSION['username'];
$id = $_GET["id"];
if (findProfile($id, $user)) {
    deleteProfile($id);
}
header('Location: profiles.php');
exit();
?>