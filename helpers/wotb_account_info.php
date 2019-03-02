<?php
require_once("../helpers/grabber.php");

$application_id = "85ef22379a59f6a0dba28174be9a0902";
$url_wotb_account_list = "https://api.wotblitz.ru/wotb/account/list/";
$url_wotb_account_info = "https://api.wotblitz.ru/wotb/account/info/";

if (isset($_POST["search"])) {
    $nickname = $_POST["search"];

    $account_list_response = json_decode(grab("$url_wotb_account_list?application_id=$application_id&search=$nickname&limit=1"), true);
    $account_id = $account_list_response["data"][0]["account_id"];
    
    if (isset($account_id)) {
        $account_info_response = json_decode(grab("$url_wotb_account_info?application_id=$application_id&account_id=$account_id"), true);
        $statistics_all = $account_info_response["data"][$account_id]["statistics"]["all"];
        
        echo json_encode($statistics_all);
    } else echo json_encode(array("error" => "Such user`s nickname not found."));
}

?>