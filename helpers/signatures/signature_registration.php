<?php
require_once("../config.php");

if (isset($_POST["nickname"]) && isset($_POST["account_id"]) && isset($_POST["game"])) {
    $nickname = $_POST["nickname"];
    $account_id = (int)$_POST["account_id"];
    $game = $_POST["game"];

    mysql_connect("localhost", "root", "");
    mysql_select_db("wgstat");
    $sign_data_isset_query = mysql_query("SELECT $sign_data, $sign_background FROM $sign_table WHERE $sign_account_id=$account_id AND $sign_game='$game'");
    $data = mysql_fetch_array($sign_data_isset_query);
    $signature_data = $data[$sign_data];
    $signature_bkg = $data[$sign_background];
    $response = [];

    if (isset($signature_data)) {
        if ($signature_bkg == "") {
            $response += ["selection" => "Signature registered, not created"];
        } else if ($signature_bkg != "" && $signature_data == "") {
            $response += ["process" => "Signature registered, not created"];
        } else {
            $response += ["ok" => "Signature registered and created"];
        }
    } else {
        mysql_query("INSERT INTO $sign_table VALUES ($account_id, '$game', '', '', 0)");
        if (!file_exists("../../signatures/$game/$nickname/")) {
            mkdir("../../signatures/$game/$nickname/", 0777, true);
        }

        $response += ["registered" => "Signature registered"];
    }  

    echo json_encode($response);

    mysql_close();
}
?>