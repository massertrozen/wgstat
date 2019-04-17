<?php
require_once("../grabber.php");
require_once("../config.php");
require_once("../urls.php");

if (isset($_POST["search"])) {
    $nickname = $_POST["search"];
    $output = array(
        "error" => "Страница в разработке. Скоро...",
        "game" => "wows", 
        "nickname" => $nickname
    );

    echo json_encode($output);
}

?>