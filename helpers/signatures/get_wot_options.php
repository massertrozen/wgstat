<?php
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
    $created_at = date("d.m.Y H:i:s", $account_info->created_at);
    $created_days = round((time() - $created_at) / 86400);
    $updated_at = date("d.m.Y H:i:s", $account_info->updated_at);
    $last_battle_time = date("d.m.Y H:i:s", $account_info->last_battle_time);
    $global_rating = $account_info->global_rating;

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
        $clan_emblem = $clan_info->emblems->x195->portal;
    }

    // all;
    $spotted = $statistics_all->spotted;
    $battles_on_stunning_vehicles = $statistics_all->battles_on_stunning_vehicles;
    $max_xp = $statistics_all->max_xp;
    $avg_damage_blocked = $statistics_all->avg_damage_blocked;
    $direct_hits_received = $statistics_all->direct_hits_received;
    $explosion_hits = $statistics_all->explosion_hits;
    $piercings_received = $statistics_all->piercings_received;
    $piercings = $statistics_all->piercings;
    $max_damage_tank_id = $statistics_all->max_damage_tank_id;
    $xp = $statistics_all->xp;
    $survived_battles = $statistics_all->survived_battles; 
    $dropped_capture_points = $statistics_all->dropped_capture_points;
    $hits_percents = $statistics_all->hits_percents;
    $draws = $statistics_all->draws;
    $max_xp_tank_id = $statistics_all->max_xp_tank_id;
    $battles = $statistics_all->battles;
    $damage_received = $statistics_all->damage_received;
    $avg_damage_assisted = $statistics_all->avg_damage_assisted;
    $max_frags_tank_id = $statistics_all->max_frags_tank_id; 
    $avg_damage_assisted_track = $statistics_all->avg_damage_assisted_track;
    $frags = $statistics_all->frags; 
    $stun_number = $statistics_all->stun_number;
    $avg_damage_assisted_radio = $statistics_all->avg_damage_assisted_radio;
    $capture_points = $statistics_all->capture_points;    
    $stun_assisted_damage = $statistics_all->stun_assisted_damage;
    $max_damage = $statistics_all->max_damage; 
    $hits = $statistics_all->hits; 
    $battle_avg_xp = $statistics_all->battle_avg_xp;
    $wins = $statistics_all->wins;
    $losses = $statistics_all->losses; 
    $damage_dealt = $statistics_all->damage_dealt;
    $no_damage_direct_hits_received = $statistics_all->no_damage_direct_hits_received;
    $max_frags = $statistics_all->max_frags;
    $shots = $statistics_all->shots;
    $explosion_hits_received = $statistics_all->explosion_hits_received;
    $tanking_factor = $statistics_all->tanking_factor;
    $masters = 0;

    // calculated values from statistics->all;
    $battles_on_stunning_vehicles_percent = $battles_on_stunning_vehicles / $battles * 100;
    $battles_on_stunning_vehicles_percent_rounded = round($battles_on_stunning_vehicles_percent, 2);
    $wins_percent = $wins / $battles * 100;
    $wins_percent_rounded = round($wins_percent, 2);
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
    $avg_damage_received = round($damage_received / $battles);
    $avg_shots = round($shots / $battles, 2);
    $avg_hits = round($hits / $battles, 2);
    $avg_piercings = round($piercings / $battles, 2);
    $avg_direct_hits_received = round($direct_hits_received / $battles, 2);
    $avg_piercings_received = round($piercings_received / $battles, 2);

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
        $tank_mark_of_mastery = $tank->mark_of_mastery;
        if ($tank_mark_of_mastery == 4) $masters++;

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

    $options = [
        "Ник" => $nickname, 
        "Рейтинг" => $global_rating, 
        "КПД" => $KPD, 
        "WN6" => $WN6, 
        "WN7" => $WN7, 
        "Боёв" => $battles, 
        "Побед" => "{$wins_percent_rounded}%", 
        "Ср.уровень" => $avg_tier_rounded,
        "Ассист" => $avg_damage_assisted, 
        "Ср.урон" => $avg_damage_dealt_rounded, 
        "Коэф.урона" => $damage_rate_rounded, 
        "Коэф.уничтожения" => $destruction_rate_rounded, 
        "Фрагов/бой" => $avg_frags_rounded, 
        "Засветов/бой" => $avg_spotted_rounded,
        "Захвата/бой" => $avg_capture_rounded, 
        "Защиты/бой" => $avg_dropped_capture_rounded, 
        "Попаданий" => "{$hits_percent_rounded}%",
        "Выживаний" => "{$survival_percent_rounded}%", 
        "Ср.опыт" => $battle_avg_xp,
        "Макс.опыт" => $max_xp, 
        "Макс.урон" => $max_damage, 
        "Выстрелов/бой" => $avg_shots, 
        "Попаданий/бой" => $avg_hits, 
        "Пробитий/бой" => $avg_piercings, 
        "Мастеров" => "{$masters}/{$tanks_counter}"
    ];

    echo json_encode($options);
}
?>