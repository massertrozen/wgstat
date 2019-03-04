<?php
require_once("../helpers/grabber.php");

$application_id = "85ef22379a59f6a0dba28174be9a0902";
$url_wotb_account_list = "https://api.wotblitz.ru/wotb/account/list/";
$url_wotb_account_info = "https://api.wotblitz.ru/wotb/account/info/";
$url_wotb_tanks_stat = "https://api.wotblitz.ru/wotb/tanks/stats/";
$url_wotb_encyclopedia = "https://api.wotblitz.ru/wotb/encyclopedia/vehicles/";
$url_wotb_account_achievements = "https://api.wotblitz.ru/wotb/account/achievements/";
$url_wotb_clans_accountinfo = "https://api.wotblitz.ru/wotb/clans/accountinfo/";
// $url_wgn_account_list = "https://api.worldoftanks.ru/wgn/account/list/";
// $url_wgn_servers_info = "https://api.worldoftanks.ru/wgn/servers/info/";

if (isset($_POST["search"])) {
    $nickname = $_POST["search"];

    $account_list_response = json_decode(grab("$url_wotb_account_list?application_id=$application_id&search=$nickname&limit=1"), true);
    $account_id = $account_list_response["data"][0]["account_id"];
    
    if (isset($account_id)) {
        $account_info_response = json_decode(grab("$url_wotb_account_info?application_id=$application_id&account_id=$account_id"), true);
        $tanks_stat_response = json_decode(grab("$url_wotb_tanks_stat?application_id=$application_id&account_id=$account_id"), true);
        $account_achievements_response = json_decode(grab("$url_wotb_account_achievements?application_id=$application_id&account_id=$account_id"), true);

        $slice_offset = ceil(count($tanks_stat_response["data"][$account_id]) / 100);
        $sliced_tanks_array = [];
        for ($i = 0; $i < $slice_offset; $i++) {
            $sliced_tanks_array += array($i => array_slice($tanks_stat_response['data'][$account_id], $i * 100, 100));
        }

        $tanks_encyclopedia = [];
        foreach ($sliced_tanks_array as $tanks_array) {
            $tanks_ids = "";
            foreach ($tanks_array as $tank) {
                $tanks_ids .= ",".$tank["tank_id"];
            }

            $tanks_encyclopedia_response = json_decode(grab("$url_wotb_encyclopedia?application_id=$application_id&tank_id=$tanks_ids&fields=tier,name,tank_id"), true);
            $tanks_encyclopedia += $tanks_encyclopedia_response["data"];
        }

        $statistics_all = $account_info_response["data"][$account_id]
        + array("tanks_stat" => $tanks_stat_response["data"][$account_id])
        + array("tanks_encyclopedia" => $tanks_encyclopedia)
        + array("achievements" => $account_achievements_response["data"][$account_id]);
        // clan
        echo json_encode($statistics_all);
    } else echo json_encode(array("error" => "Such user`s nickname not found."));
}

?>