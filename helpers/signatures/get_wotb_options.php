<?php
require_once("../config.php");

if (isset($_POST["account_info"])) {
    // statitics: info, all, tanks, achievements, max_series, clan, wgn_accounts;
    $account_info = json_decode($_POST["account_info"]);
    $statistics_all = $account_info->statistics->all; 
    $tanks_stat = $account_info->tanks_stat;
    $tanks_encyclopedia = $account_info->tanks_encyclopedia;
    $achievements = $account_info->achievements;
    $account_achievements = $achievements->achievements;
    $account_max_series = $achievements->max_series;
    $clan_info = $account_info->clan_info;  
    $clan_glossary = $account_info->glossary;
    $wgn_accounts = $account_info->wgn_accounts;
    $wgn_games = array("wot" => "World of Tanks", "wows" => "World of Warships", "wotb" => "World of Tanks Blitz", "wowp" => "World of Warplanes");
    $wgn_servers = $account_info->wgn_servers;

    // info;
    $nickname = $account_info->nickname;
    $account_id = $account_info->account_id;
    $created_at = date("d.m.Y", $account_info->created_at);
    $updated_at = date("d.m.Y H:i:s", $account_info->updated_at);
    $last_battle_time = date("d.m.Y в H:i", $account_info->last_battle_time);

    // clan;
    if (count($clan_info) > 0) {
        $clan_id = $clan_info->id;
        $clan_role = $clan_info->role;
        $clan_role_normal = $clan_glossary->$clan_role;
        $clan_joined_at = $clan_info->joined_at;
        $clan_name = $clan_info->name;
        $clan_members_count = $clan_info->members_count;
        $clan_created_at = date("d.m.Y H:i:s", $clan_info->created_at);
        $clan_leader_name = $clan_info->leader_name;
        $clan_tag = $clan_info->tag;
        $clan_motto = $clan_info->motto;
        $clan_description = $clan_info->description;
        $clan_days_joined = round((time() - $clan_joined_at) / 86400);
    }

    // all;
    $spotted = $statistics_all->spotted;
    $max_frags_tank_id = $statistics_all->max_frags_tank_id;
    $hits = $statistics_all->hits; 
    $frags = $statistics_all->frags;
    $max_xp = $statistics_all->max_xp;
    $max_xp_tank_id = $statistics_all->max_xp_tank_id;
    $wins = $statistics_all->wins;
    $losses = $statistics_all->losses;
    $capture_points = $statistics_all->capture_points; 
    $battles = $statistics_all->battles;
    $damage_dealt = $statistics_all->damage_dealt;
    $damage_received = $statistics_all->damage_received;
    $max_frags = $statistics_all->max_frags; 
    $shots = $statistics_all->shots; 
    $frags8p = $statistics_all->frags8p;
    $xp = $statistics_all->xp;
    $win_and_survived = $statistics_all->win_and_survived; 
    $survived_battles = $statistics_all->survived_battles; 
    $dropped_capture_points = $statistics_all->dropped_capture_points;
    $joint_victory = $account_max_series->jointVictory;
    $masters = $account_achievements->markOfMastery;

    // calculated values from statistics->all;
    $wins_percent = $wins / $battles * 100;
    $wins_percent_rounded = round($wins_percent, 2);
    $draw = $battles - $wins - $losses;
    $avg_damage_dealt = $damage_dealt / $battles;
    $avg_damage_dealt_rounded = round($avg_damage_dealt);
    $damage_rate = $damage_dealt / $damage_received;
    $damage_rate_rounded = round($damage_rate, 2);
    $destruction_rate = $frags / $survived_battles;
    $destruction_rate_rounded = round($destruction_rate, 2);
    $avg_frags = $frags / $battles;
    $avg_frags_rounded = round($avg_frags, 2);
    $avg_spotted = $spotted / $battles;
    $avg_spotted_rounded = round($avg_spotted, 2);    
    $avg_capture = $capture_points / $battles;
    $avg_capture_rounded = round($avg_capture, 2);
    $avg_dropped_capture = $dropped_capture_points / $battles;
    $avg_dropped_capture_rounded = round($avg_dropped_capture, 2);
    $hits_percent = $hits / $shots * 100;
    $hits_percent_rounded = round($hits_percent, 2);
    $survival_percent = $survived_battles / $battles * 100;
    $survival_percent_rounded = round($survival_percent, 2);
    $avg_xp = $xp / $battles;
    $avg_xp_rounded = round($avg_xp);
    $avg_damage_received = round($damage_received / $battles);    
    $solo_wins = $wins - $joint_victory;
    $joint_victory_percent = round($joint_victory / $wins * 100, 2);
    $avg_shots = round($shots / $battles, 2);
    $avg_hits = round($hits / $battles, 2);

    // tanks;
    $tanks_names = [];
    $tanks_tiers = [];
    $max_tier = 0;
    $tanks_counter = count($tanks_stat);
    foreach ($tanks_encyclopedia as $tank) {
        if ($tank->tier > $max_tier) $max_tier = $tank->tier;

        $tanks_names[$tank->tank_id] = $tank->name;
        $tanks_tiers[$tank->tank_id] = $tank->tier;
    }

    foreach ($tanks_stat as $tank) {
        $tank_id = $tank->tank_id;
        $tank_battles = $tank->all->battles;
        $tank_tier = $tanks_tiers[$tank_id];

        $total_tier_battles += $tank_tier * $tank_battles;
    }

    $avg_tier = $total_tier_battles / $battles;
    $avg_tier_rounded = round($avg_tier, 2);

    // ratings;
    $KPD = round($avg_damage_dealt * (10 / ($avg_tier + 2)) * (0.23 + 2 * $avg_tier / 100) + $avg_frags * 250 + $avg_spotted * 150 + (log($avg_capture + 1, 1.732)) * 150 + $avg_dropped_capture * 150, 2);				
    $WN6 = round((1240 - 1040 / pow(min($avg_tier, 6), 0.164)) * $avg_frags + $avg_damage_dealt * 530 / (184 * exp(0.24 * $avg_tier) + 130) + $avg_spotted * 125 + min($avg_dropped_capture, 2.2) * 100 + ((185 / (0.17 + exp(($wins_percent - 35) * -0.134))) - 500) * 0.45 + (6 - min($avg_tier, 6)) * -60, 2);
    $WN7 = round((1240 - 1040 / (pow(min($avg_tier, 6), 0.164))) * $avg_frags + $avg_damage_dealt * 530 / (184 * exp(0.24 * $avg_tier) + 130) + $avg_spotted * 125 * min($avg_tier, 3) / 3 + min($avg_dropped_capture, 2.2) * 100 + ((185 / (0.17 + exp((($wins_percent) - 35) * -0.134))) - 500) * 0.45 + (-1 * (((5 - min($avg_tier, 5)) * 125) / (1 + exp(($avg_tier - ($battles / 220^(3 / $avg_tier))) * 1.5)))), 2);
    
    if ($KPD < 0){ $KPD = "0"; }
    if ($WN6 < 0) { $WN6 = "0"; }
    if ($WN7 < 0){ $WN7 = "0"; }
}

$options = [
    "Ник" => $nickname, 
    "КПД" => $KPD, 
    "WN6" => $WN6, 
    "WN7" => $WN7, 
    "Боев" => $battles, 
    "Побед" => "{$wins_percent_rounded}%",  
    "Ср.уровень" => $avg_tier_rounded,
    "Ср.урон" => $avg_damage_dealt_rounded, 
    "Коэф.урона" => $damage_rate_rounded, 
    "Коэф.уничтожения" => $destruction_rate_rounded, 
    "Фрагов/бой" => $avg_frags_rounded, 
    "Засветов/бой" => $avg_spotted_rounded,
    "Захвата/бой" => $avg_capture_rounded, 
    "Защиты/бой" => $avg_dropped_capture_rounded, 
    "Попаданий" => "{$hits_percent_rounded}%",
    "Выживаний" => "{$survival_percent_rounded}%", 
    "Ср.опыт" => $avg_xp_rounded,
    "Макс.опыт" => $max_xp, 
    "Выстрелов/бой" => $avg_shots, 
    "Попаданий/бой" => $avg_hits, 
    "Мастеров" => "{$masters}/{$tanks_counter}",
    "Побед взводом" => $joint_victory_percent
];

mysql_connect("localhost", "root", "");
mysql_select_db("wgstat");
$sign_data_isset_query = mysql_query("SELECT $sign_background FROM $sign_table WHERE $sign_account_id=$account_id AND $sign_game='wotb'");
$data = mysql_fetch_array($sign_data_isset_query);
$signature_bkg = $data[$sign_background];
mysql_close();

echo json_encode(["values" => $options, "wgn_accounts" => $wgn_accounts, "background" => $signature_bkg]);
?>
