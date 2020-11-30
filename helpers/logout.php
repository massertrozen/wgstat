<?php
require_once("config.php");
require_once("grabber.php");

if (isset($_POST["search"])) {
    // $access_token = $_POST["search"];

    // mysql_connect("localhost", "root", "");
    // mysql_select_db("wgstat");

    // $wgn_account_id_query = mysql_query("SELECT * FROM $wgn_accounts_table WHERE $wgn_access_token='$access_token'");
    // $account_id = mysql_fetch_array($wgn_account_id_query)[$wgn_account_id];
    // mysql_close();

    // if (isset($account_id)) {
    //     $wgn_logout_response = json_decode(grab("$url_wgn_logout&account_id=$account_id&access_token=$access_token"), true);
    // }    
}

?>