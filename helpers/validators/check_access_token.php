<?php
require_once("../config.php");
require_once("../grabber.php");

if (isset($_POST["search"])) {
    $access_token = $_POST["search"];

    mysql_connect("localhost", "root", "");
    mysql_select_db("wgstat");

    $wgn_account_id_query = mysql_query("SELECT * FROM $wgn_accounts_table WHERE $wgn_access_token='$access_token'");
    $account_id = mysql_fetch_array($wgn_account_id_query)[$wgn_account_id];
    mysql_close();

    if (isset($account_id)) {
        $wgn_accounts_response = json_decode(grab("$url_wgn_account_info&account_id=$account_id&access_token=$access_token"), true);

        if (isset($wgn_accounts_response["data"][$account_id]["nickname"])) {
            $nickname = $wgn_accounts_response["data"][$account_id]["nickname"];

            echo json_encode(
                array(
                    "ok" => "Token is valid.", 
                    "account_id" => $account_id, 
                    "access_token" => $access_token,
                    "nickname" => $nickname
                )
            );
        } else
            echo json_encode(array("error" => "Token is invalid."));        
        
    } else 
        echo json_encode(array("error" => "Token is invalid."));
}
?>