<?php
require_once("../config.php");

if (isset($_POST["nickname"]) && isset($_POST["account_id"]) && isset($_POST["game"])) {
    $nickname = $_POST["nickname"];
    $account_id = $_POST["account_id"];
    $game = $_POST["game"];

    mysql_connect("localhost", "root", "");
    mysql_select_db("wgstat");
    $sign_registration_isset_query = mysql_query("SELECT $sign_updated FROM $sign_table WHERE $sign_account_id=$account_id AND $sign_game='$game'");
    $sign_updated_date = mysql_fetch_array($sign_registration_isset_query)[$sign_updated];
    $response = array();

    if (isset($sign_updated_date)) {
        if ($sign_updated_date == 0) {
            $response += array(
                "process" => "Signature registered, not created"
            );
        } else {
            $response += array(
                "ok" => "Signature registered and created"
            );
        }
    } else {
        mysql_query("INSERT INTO $sign_table VALUES ($account_id, '', '$game', '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', 0, 0)");
        if (!file_exists("../../signatures/$game/$nickname/")) {
            mkdir("../../signatures/$game/$nickname/", 0777, true);
        }

        $response += array(
            "registered" => "Signature registered"
        );
    }  

    echo json_encode($response);

    mysql_close();
}
?>