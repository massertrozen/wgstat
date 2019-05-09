<?php
require_once("../config.php");
require_once("../grabber.php");

if (isset($_POST["access_token"]) && isset($_POST["game"])) {
    $access_token = $_POST["access_token"];
    $game = $_POST["game"];

    mysql_connect("localhost", "root", "");
    mysql_select_db("wgstat");
    $wgn_account_id_query = mysql_query("SELECT $wgn_account_id FROM $wgn_accounts_table WHERE $wgn_access_token='$access_token'");
    $account_id = mysql_fetch_array($wgn_account_id_query)[$wgn_account_id];

    $signature_query = mysql_query("SELECT $sign_background, $sign_data FROM $sign_table WHERE $sign_account_id='$account_id' AND $sign_game='$game'");
    $data = mysql_fetch_array($signature_query);
    $background = $data[$sign_background];
    $signature_data = unserialize($data[$sign_data]);
    $account_info_response = json_decode(grab("$url_wot_account_info&account_id=$account_id"), true);

    echo json_encode(["background" => $background, "signature_data" => $signature_data, "nickname" => $account_info_response["data"][$account_id]["nickname"]]);

    mysql_close();
}

?>