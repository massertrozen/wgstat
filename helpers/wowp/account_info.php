<?php
require_once("../grabber.php");
require_once("../config.php");
require_once("../urls.php");

if (isset($_POST["search"])) {
    $nickname = $_POST["search"];
    $output = array(
        "error" => "This page in development. See you soon...",
        "game" => "wowp", 
        "nickname" => $nickname
    );

    echo json_encode($output);
}

?>