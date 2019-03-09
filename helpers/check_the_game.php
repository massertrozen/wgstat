<?php
require_once("../helpers/grabber.php");
require_once("../helpers/urls.php");

$url_game_account_info = array(
    "wot" => $url_wot_account_info,
    "wows" => $url_wows_account_info,
    "wotb" => $url_wotb_account_info,
    "wowp" => $url_wowp_account_info
);

if (isset($_POST["search"])) {
    $nickname = $_POST["search"];

    $wgn_account_list_response = json_decode(grab("$url_wgn_account_list&search=$nickname&limit=1"), true);
    $account_id = $wgn_account_list_response["data"][0]["account_id"];
    
    if (isset($account_id)) {
        $wgn_games = $wgn_account_list_response["data"][0]["games"];
        $output = [];

        foreach ($wgn_games as $game) {
            $account_info_response = json_decode(grab("$url_game_account_info[$game]&account_id=$account_id"), true);
            $last_battle_time = $account_info_response["data"][$account_id]["last_battle_time"];

            $output += array($last_battle_time => $game);
        }

        krsort($output);

        echo json_encode(array("last_game" => array_shift($output)));
    } else echo json_encode(array("error" => "Such user`s nickname not found."));
}

?>