<?php
require_once("../helpers/config.php");
require_once("../helpers/grabber.php");

mysql_connect("localhost", "root", "");
mysql_select_db("wgstat");

$account_ids_query = mysql_query("SELECT $wot_accounts_account_id FROM $wot_accounts_table");
while ($row = mysql_fetch_array($account_ids_query)) {
    $account_id = $row[$wot_accounts_account_id];
    $account_info_response = json_decode(grab("$url_wot_account_info&account_id=$account_id"), true);
    $tanks_stat_response = json_decode(grab("$url_wot_tanks_stat&account_id=$account_id"), true);

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

        $tanks_encyclopedia_response = json_decode(grab("$url_wot_encyclopedia&tank_id=$tanks_ids&fields=tier,name,tank_id"), true);
        $tanks_encyclopedia += $tanks_encyclopedia_response["data"];
    }

    $clans_accountinfo_response = json_decode(grab("$url_wot_clans_accountinfo&account_id=$account_id"), true);
    $clan_id = $clans_accountinfo_response["data"][$account_id]["clan"]["clan_id"];
    $clan_info = array("id" => $clan_id);

    $account_info = $account_info_response["data"][$account_id]
    + array("tanks_stat" => $tanks_stat_response["data"][$account_id])
    + array("tanks_encyclopedia" => $tanks_encyclopedia)
    + array("clan_info" => $clan_info);

    $statistics_all = $account_info["statistics"]["all"]; 
    $tanks_stat = $account_info["tanks_stat"];
    $tanks_encyclopedia = $account_info["tanks_encyclopedia"];
    $clan_info = $account_info["clan_info"];  

    if (count($clan_info) > 0) {
        $clan_id = $clan_info["id"];
    }

    $global_rating = $account_info["global_rating"];
    $damage_dealt = $statistics_all["damage_dealt"];
    $battles = $statistics_all["battles"];
    $frags = $statistics_all["frags"];
    $spotted = $statistics_all["spotted"];
    $capture_points = $statistics_all["capture_points"]; 
    $dropped_capture_points = $statistics_all["dropped_capture_points"];
    $wins = $statistics_all["wins"];
    $avg_damage_assisted = $statistics_all["avg_damage_assisted"];
    $hits = $statistics_all["hits"]; 
    $survived_battles = $statistics_all["survived_battles"]; 
    $battle_avg_xp = $statistics_all["battle_avg_xp"];
    $avg_damage_blocked = $statistics_all["avg_damage_blocked"];
    $shots = $statistics_all["shots"];
    $piercings = $statistics_all["piercings"];

    $avg_damage_dealt = $damage_dealt / $battles;
    $avg_damage_dealt_rounded = round($avg_damage_dealt);
    $avg_frags = $frags / $battles;
    $avg_frags_rounded = round($avg_frags, 2);
    $avg_spotted = $spotted / $battles;
    $avg_spotted_rounded = round($avg_spotted, 2);
    $avg_capture = $capture_points / $battles;
    $avg_capture_rounded = round($avg_capture, 2);
    $avg_dropped_capture = $dropped_capture_points / $battles;
    $avg_dropped_capture_rounded = round($avg_dropped_capture, 2);
    $wins_percent = $wins / $battles * 100;
    $wins_percent_rounded = round($wins_percent, 2);
    $avg_hits = round($hits / $battles, 2);
    $survival_percent = $survived_battles / $battles * 100;
    $survival_percent_rounded = round($survival_percent, 2);
    $avg_shots = round($shots / $battles, 2);
    $avg_piercings = round($piercings / $battles, 2);
    $stat_date = time();

    $tanks_names = [];
    $tanks_tiers = [];
    foreach ($tanks_encyclopedia as $tank) {
        $tanks_names[$tank["tank_id"]] = $tank["name"];
        $tanks_tiers[$tank["tank_id"]] = $tank["tier"];
    }

    foreach ($tanks_stat as $tank) {
        $tank_id = $tank["tank_id"];
        $tank_battles = $tank["all"]["battles"];
        $tank_tier = $tanks_tiers[$tank_id];

        $total_tier_battles += $tank_tier * $tank_battles;
    }

    $avg_tier = $total_tier_battles / $battles;
    $avg_tier_rounded = round($avg_tier, 2);

    $KPD = round($avg_damage_dealt * (10 / ($avg_tier + 2)) * (0.23 + 2 * $avg_tier / 100) + $avg_frags * 250 + $avg_spotted * 150 + (log($avg_capture + 1, 1.732)) * 150 + $avg_dropped_capture * 150, 2);				
    $WN6 = round((1240 - 1040 / pow(min($avg_tier, 6), 0.164)) * $avg_frags + $avg_damage_dealt * 530 / (184 * exp(0.24 * $avg_tier) + 130) + $avg_spotted * 125 + min($avg_dropped_capture, 2.2) * 100 + ((185 / (0.17 + exp(($wins_percent - 35) * -0.134))) - 500) * 0.45 + (6 - min($avg_tier, 6)) * -60, 2);
    $WN7 = round((1240 - 1040 / (pow(min($avg_tier, 6), 0.164))) * $avg_frags + $avg_damage_dealt * 530 / (184 * exp(0.24 * $avg_tier) + 130) + $avg_spotted * 125 * min($avg_tier, 3) / 3 + min($avg_dropped_capture, 2.2) * 100 + ((185 / (0.17 + exp((($wins_percent) - 35) * -0.134))) - 500) * 0.45 + (-1 * (((5 - min($avg_tier, 5)) * 125) / (1 + exp(($avg_tier - ($battles / 220^(3 / $avg_tier))) * 1.5)))), 2);

    mysql_query("INSERT INTO $wot_accounts_stat_table VALUES ('$account_id', '$clan_id', '$global_rating', '$KPD', '$WN6', '$WN7', '$battles', '$wins_percent_rounded', '$avg_damage_assisted', '$avg_damage_dealt_rounded', '$avg_hits', '$survival_percent_rounded', '$battle_avg_xp', '$avg_damage_blocked', '$avg_shots', '$avg_hits', '$avg_piercings', '$stat_date')");
}

mysql_close();

?>