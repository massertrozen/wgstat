<?php
require_once("../config.php");

if (isset($_POST["background"]) && isset($_POST["access_token"]) && isset($_POST["game"])) {
    $background = $_POST["background"];
    $access_token = $_POST["access_token"];
    $game = $_POST["game"];

    mysql_connect("localhost", "root", "");
    mysql_select_db("wgstat");
    $wgn_account_id_query = mysql_query("SELECT $wgn_account_id FROM $wgn_accounts_table WHERE $wgn_access_token='$access_token'");
    $account_id = mysql_fetch_array($wgn_account_id_query)[$wgn_account_id];
    echo $account_id;

    mysql_query("UPDATE $sign_table SET $sign_background='$background' WHERE $sign_account_id=$account_id AND $sign_game='$game'");

    mysql_close();
}

?>