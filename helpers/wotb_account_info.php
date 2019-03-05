<?php
require_once("../helpers/grabber.php");
require_once("../helpers/urls.php");

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

        $clan_info = [];
        $clans_accountinfo_response = json_decode(grab("$url_wotb_clans_accountinfo?application_id=$application_id&account_id=$account_id"), true);
        $clan_id = $clans_accountinfo_response["data"][$account_id][clan_id];
        if (isset($clan_id)) {
            $clan_role = $clans_accountinfo_response["data"][$account_id][role];
            $clan_joined_at = $clans_accountinfo_response["data"][$account_id][joined_at];

            $clans_info_response = json_decode(grab("$url_wotb_clans_info?application_id=$application_id&clan_id=$clan_id"), true);
            $clan_name = $clans_info_response["data"][$clan_id]["name"];
            $clan_members_count = $clans_info_response["data"][$clan_id]["members_count"];
            $clan_created_at = $clans_info_response["data"][$clan_id]["created_at"];
            $clan_leader_name = $clans_info_response["data"][$clan_id]["leader_name"];
            $clan_tag = $clans_info_response["data"][$clan_id]["tag"];
            $clan_motto = $clans_info_response["data"][$clan_id]["motto"];
            $clan_description = $clans_info_response["data"][$clan_id]["description"];

            $clan_info += array(
                "id" => $clan_id, "role" => $clan_role, "joined_at" => $clan_joined_at, 
                "name" => $clan_name, "members_count" => $clan_members_count, "created_at" => $clan_created_at,
                "leader_name" => $clan_leader_name, "tag" => $clan_tag, "motto" => $clan_motto,
                "description" => $clan_description            
            );
        }

        $glossary_response = json_decode(grab("$url_wotb_glossary?application_id=$application_id"), true);

        $wgn_accounts_response = json_decode(grab("$url_wgn_account_info?application_id=$application_id&account_id=$account_id"), true);

        $wgn_servers_response = json_decode(grab("$url_wgn_servers_info?application_id=$application_id"), true);

        $statistics_all = $account_info_response["data"][$account_id]
        + array("tanks_stat" => $tanks_stat_response["data"][$account_id])
        + array("tanks_encyclopedia" => $tanks_encyclopedia)
        + array("achievements" => $account_achievements_response["data"][$account_id])
        + array("clan_info" => $clan_info)
        + array("glossary" => $glossary_response["data"]["clans_roles"])
        + array("wgn_accounts" => $wgn_accounts_response["data"][$account_id]["games"])
        + array("wgn_servers" => $wgn_servers_response["data"]["wot"]);

        echo json_encode($statistics_all);
    } else echo json_encode(array("error" => "Such user`s nickname not found."));
}

?>