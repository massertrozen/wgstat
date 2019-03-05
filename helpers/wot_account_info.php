<?php
require_once("../helpers/grabber.php");
require_once("../helpers/urls.php");

if (isset($_POST["search"])) {
    $nickname = $_POST["search"];
    $output = array(
        "error" => "This page in development. See you soon",
        "game" => "wot", 
        "nickname" => $nickname
    );

    echo json_encode($output);
}

?>